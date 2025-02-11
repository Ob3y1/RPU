import 'package:flutter/material.dart';
import 'package:dio/dio.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'dart:convert';

import 'search_result.dart';

class SearchPage extends StatefulWidget {
  const SearchPage({super.key});

  @override
  _SearchPageState createState() => _SearchPageState();
}

class _SearchPageState extends State<SearchPage> {
  List<dynamic> specializations = [];
  List<dynamic> locations = [];
  bool isLoading = true;

  Set<String> selectedSpecializations = {};
  String? selectedLocation;

  @override
  void initState() {
    super.initState();
    _fetchSearchData();
  }

  Future<void> _fetchSearchData() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString("token");

      if (token == null) {
        throw Exception("Token not found");
      }

      var headers = {'Authorization': 'Bearer $token'};
      var dio = Dio();

      var response = await dio.request(
        'http://localhost:8000/api/search',
        options: Options(
          method: 'GET',
          headers: headers,
        ),
      );

      if (response.statusCode == 201) {
        setState(() {
          specializations = response.data['Specialization'] ?? [];
          locations = response.data['locations'] ?? [];
          isLoading = false;
        });
      } else {
        throw Exception('Failed to load data: ${response.statusMessage}');
      }
    } catch (e) {
      setState(() {
        isLoading = false;
      });
      print("Error: $e");
      _showSnackBar('حدث خطأ أثناء تحميل البيانات');
    }
  }

  Future<void> searchRequest() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString("token");

      if (token == null) {
        throw Exception("Token not found");
      }

      var headers = {'Authorization': 'Bearer $token'};
      var data = json.encode({
        "locations": selectedLocation != null ? [selectedLocation] : [],
        "specializations": selectedSpecializations.toList(),
      });

      var dio = Dio();
      var response = await dio.request(
        'http://localhost:8000/api/search',
        options: Options(
          method: 'POST',
          headers: headers,
        ),
        data: data,
      );

      if (response.statusCode == 200) {
        print(json.encode(response.data));
        Navigator.push(
          context,
          MaterialPageRoute(
            builder: (context) => SearchResultPage(
              searchResults: response.data['data'],
            ),
          ),
        );
      } else {
        throw Exception('Failed to search: ${response.statusMessage}');
      }
    } catch (e) {
      print("Error: $e");
      _showSnackBar('حدث خطأ أثناء تنفيذ البحث');
    }
  }

  void _showSnackBar(String message) {
    ScaffoldMessenger.of(context)
        .showSnackBar(SnackBar(content: Text(message)));
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Center(
          child: Text(
            'صفحة البحث',
            style: TextStyle(
              fontSize: 20.0,
              fontWeight: FontWeight.bold,
            ),
          ),
        ),
      ),
      body: isLoading
          ? const Center(child: CircularProgressIndicator())
          : Padding(
              padding: const EdgeInsets.all(16.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'التخصصات:',
                    style:
                        TextStyle(fontSize: 18.0, fontWeight: FontWeight.bold),
                  ),
                  const SizedBox(height: 8.0),
                  // Specializations Section
                  Expanded(
                    child: ListView.builder(
                      itemCount: specializations.length,
                      itemBuilder: (context, index) {
                        final specialization = specializations[index];
                        final isSelected = selectedSpecializations
                            .contains(specialization['name']);
                        return ListTile(
                          title: Text(specialization['name']),
                          leading: GestureDetector(
                            onTap: () {
                              setState(() {
                                if (isSelected) {
                                  selectedSpecializations
                                      .remove(specialization['name']);
                                } else {
                                  selectedSpecializations
                                      .add(specialization['name']);
                                }
                              });
                            },
                            child: Container(
                              width: 24,
                              height: 24,
                              decoration: BoxDecoration(
                                shape: BoxShape.circle,
                                border:
                                    Border.all(color: Colors.black, width: 2),
                                color:
                                    isSelected ? Colors.purple : Colors.white,
                              ),
                              child: isSelected
                                  ? const Icon(Icons.circle_outlined,
                                      size: 16, color: Colors.white)
                                  : null,
                            ),
                          ),
                        );
                      },
                    ),
                  ),
                  const Divider(),
                  const Text(
                    'المواقع:',
                    style:
                        TextStyle(fontSize: 18.0, fontWeight: FontWeight.bold),
                  ),
                  const SizedBox(height: 8.0),
                  // Locations Section (RadioListTile for uniformity)
                  Expanded(
                    child: ListView.builder(
                      itemCount: locations.length,
                      itemBuilder: (context, index) {
                        final location = locations[index];
                        return RadioListTile<String>(
                          title: Text(location),
                          value: location,
                          groupValue: selectedLocation,
                          onChanged: (value) {
                            setState(() {
                              selectedLocation = value;
                            });
                          },
                          activeColor:
                              Colors.purple, // Same color for selection
                        );
                      },
                    ),
                  ),
                  const SizedBox(height: 16.0),
                  Align(
                    alignment: Alignment.topCenter,
                    child: ElevatedButton(
                      onPressed: () {
                        if (selectedSpecializations.isNotEmpty &&
                            selectedLocation != null) {
                          searchRequest();
                        } else {
                          _showSnackBar('يرجى اختيار التخصص والموقع قبل البحث');
                        }
                      },
                      style: ElevatedButton.styleFrom(
                        backgroundColor:
                            Colors.green, // Matching the selected color
                        padding: const EdgeInsets.symmetric(
                            horizontal: 32.0, vertical: 16.0),
                      ),
                      child: const Text('بحث'),
                    ),
                  ),
                  const SizedBox(height: 16.0),
                ],
              ),
            ),
    );
  }
}
