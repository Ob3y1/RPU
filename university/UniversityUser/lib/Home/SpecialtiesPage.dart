import 'package:flutter/material.dart';
import 'package:dio/dio.dart';
import 'package:university/Home/RequestSpecialtyPage.dart';
import 'package:university/main.dart';

class SpecialtiesPage extends StatefulWidget {
  final String universityName;

  const SpecialtiesPage(
      {super.key, required this.universityName, required universityId});

  @override
  _SpecialtiesPageState createState() => _SpecialtiesPageState();
}

class _SpecialtiesPageState extends State<SpecialtiesPage> {
  Map<String, List<Map<String, dynamic>>> specialtiesMap = {};
  bool isLoading = true;
  String errorMessage = "";

  @override
  void initState() {
    super.initState();
    _fetchUniversitiesAndSpecialties();
  }

  Future<void> _fetchUniversitiesAndSpecialties() async {
    var headers = {'Authorization': 'Bearer ${prefs.getString("token")}'};
    var dio = Dio();

    try {
      var response = await dio.get(
        'http://localhost:8000/api/universities',
        options: Options(headers: headers),
      );

      if (response.statusCode == 201) {
        var universitiesData = response.data['universities'];

        if (universitiesData is List) {
          for (var university in universitiesData) {
            var universityId = university['id'];
            var universityName = university['name'];

            var specializationsResponse = await dio.get(
              'http://localhost:8000/api/universities/$universityId/specializations',
              options: Options(headers: headers),
            );

            if (specializationsResponse.statusCode == 200) {
              var data = specializationsResponse.data;
              if (data is Map &&
                  data['specializations'] is List &&
                  data['grants'] is List) {
                var specializations = data['specializations'];
                var grants = data['grants'];

                setState(() {
                  specialtiesMap[universityName] = [];
                  for (var specialization in specializations) {
                    specialtiesMap[universityName]!.add({
                      'type': 'specialization',
                      'name': specialization['specialization_name'],
                      'details': specialization['specialization_details'],
                      'num_seats': specialization['num_seats'],
                      'price_per_hour': specialization['price_per_hour']
                    });
                  }

                  for (var grant in grants) {
                    specialtiesMap[universityName]!.add({
                      'type': 'grant',
                      'name': grant['specialization_name'],
                      'num_seats': grant['num_seats'],
                      'grant_num_seats': grant['grant_num_seats']
                    });
                  }
                });
              }
            }
          }
          setState(() {
            isLoading = false;
          });
        } else {
          setState(() {
            isLoading = false;
            errorMessage = "البيانات غير صحيحة.";
          });
        }
      } else {
        setState(() {
          isLoading = false;
          errorMessage =
              "حدث خطأ أثناء تحميل البيانات. حالة الاستجابة: ${response.statusCode}";
        });
      }
    } catch (e) {
      setState(() {
        isLoading = false;
        errorMessage = "حدث خطأ: $e";
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('اختصاصات ${widget.universityName}'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: isLoading
            ? const Center(child: CircularProgressIndicator())
            : errorMessage.isNotEmpty
                ? Center(child: Text(errorMessage))
                : specialtiesMap.containsKey(widget.universityName)
                    ? ListView.builder(
                        itemCount:
                            specialtiesMap[widget.universityName]?.length ?? 0,
                        itemBuilder: (context, index) {
                          var item =
                              specialtiesMap[widget.universityName]![index];

                          return Card(
                            elevation: 3,
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(10),
                            ),
                            margin: const EdgeInsets.symmetric(
                                vertical: 8, horizontal: 4),
                            child: ExpansionTile(
                              leading: Icon(
                                item['type'] == 'specialization'
                                    ? Icons.school
                                    : Icons.card_giftcard,
                                color: item['type'] == 'specialization'
                                    ? Colors.blue
                                    : Colors.green,
                              ),
                              title: Text(
                                item['name'],
                                style: TextStyle(
                                  fontWeight: FontWeight.bold,
                                  color: item['type'] == 'specialization'
                                      ? Colors.blue
                                      : Colors.green,
                                ),
                              ),
                              children: [
                                Padding(
                                  padding: const EdgeInsets.symmetric(
                                      horizontal: 16, vertical: 8),
                                  child: Column(
                                    crossAxisAlignment:
                                        CrossAxisAlignment.start,
                                    children: [
                                      if (item['type'] == 'specialization') ...[
                                        Text(
                                          "تفاصيل: ${item['details']}",
                                          style: const TextStyle(fontSize: 14),
                                        ),
                                        Text(
                                          "عدد المقاعد: ${item['num_seats']}",
                                          style: const TextStyle(fontSize: 14),
                                        ),
                                        Text(
                                          "السعر لكل ساعة: ${item['price_per_hour']}",
                                          style: const TextStyle(fontSize: 14),
                                        ),
                                      ] else ...[
                                        Text(
                                          "عدد المقاعد للمنحة: ${item['grant_num_seats']}",
                                          style: const TextStyle(fontSize: 14),
                                        ),
                                      ],
                                      const Divider(),
                                      Align(
                                        alignment: Alignment.centerRight,
                                        child: TextButton(
                                          onPressed: () {
                                            Navigator.push(
                                              context,
                                              MaterialPageRoute(
                                                builder: (context) =>
                                                    RequestSpecialtyPage(
                                                  specialtyName: item['name'],
                                                  universityName:
                                                      widget.universityName,
                                                  typeId: item['type'] ==
                                                          'specialization'
                                                      ? 1
                                                      : 2,
                                                ),
                                              ),
                                            );
                                          },
                                          child: const Text(
                                            "التقديم الآن",
                                            style: TextStyle(
                                              color: Colors.blue,
                                              fontWeight: FontWeight.bold,
                                            ),
                                          ),
                                        ),
                                      ),
                                    ],
                                  ),
                                ),
                              ],
                            ),
                          );
                        },
                      )
                    : const Center(child: Text("لا توجد بيانات لهذه الجامعة")),
      ),
    );
  }
}
