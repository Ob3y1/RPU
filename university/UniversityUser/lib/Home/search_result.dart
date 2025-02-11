import 'package:flutter/material.dart';
import 'package:university/Home/SpecialtiesPage.dart';

class SearchResultPage extends StatelessWidget {
  final List<dynamic> searchResults;

  const SearchResultPage({super.key, required this.searchResults});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Center(
          child: Text(
            'نتائج البحث',
            style: TextStyle(
              fontSize: 20.0,
              fontWeight: FontWeight.bold,
            ),
          ),
        ),
      ),
      body: searchResults.isEmpty
          ? const Center(child: Text('لا توجد نتائج للبحث'))
          : ListView.builder(
              itemCount: searchResults.length,
              itemBuilder: (context, index) {
                final result = searchResults[index];
                return Card(
                  margin: const EdgeInsets.all(10.0),
                  elevation: 5.0,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(10.0),
                  ),
                  child: Padding(
                    padding: const EdgeInsets.all(16.0),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          result['name'],
                          style: const TextStyle(
                            fontSize: 20.0,
                            fontWeight: FontWeight.bold,
                            color: Colors.green,
                          ),
                        ),
                        const SizedBox(height: 8.0),
                        Text(
                          'الموقع: ${result['location']}',
                          style: const TextStyle(
                            fontSize: 16.0,
                            color: Colors.black54,
                          ),
                        ),
                        const SizedBox(height: 8.0),
                        Text(
                          'التفاصيل: ${result['details']}',
                          style: const TextStyle(
                            fontSize: 14.0,
                            color: Colors.black87,
                          ),
                        ),
                        GestureDetector(
                          onTap: () {
                            // انتقل إلى صفحة التخصصات الخاصة بالجامعة
                            Navigator.push(
                              context,
                              MaterialPageRoute(
                                builder: (context) => SpecialtiesPage(
                                  universityId: result['id'],
                                  universityName: result['name'],
                                ),
                              ),
                            );
                          },
                          child: const ListTile(
                            trailing: Icon(
                              Icons.arrow_forward,
                              color: Colors.green,
                            ),
                            title: Text(
                              'عرض التخصصات',
                              style: TextStyle(
                                fontSize: 16.0,
                                color: Colors.green,
                              ),
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                );
              },
            ),
    );
  }
}
