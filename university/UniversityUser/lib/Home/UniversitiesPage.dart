import 'package:flutter/material.dart';
import 'package:dio/dio.dart';
import 'package:university/Home/SpecialtiesPage.dart';
import 'package:university/main.dart';

class UniversitiesPage extends StatefulWidget {
  const UniversitiesPage({super.key});

  @override
  _UniversitiesPageState createState() => _UniversitiesPageState();
}

class _UniversitiesPageState extends State<UniversitiesPage> {
  List<Map<String, dynamic>> universitiesData = []; // تخزين id و name لكل جامعة
  bool isLoading = true;
  String errorMessage = "";

  // تابع لجلب الجامعات من الـ backend
  Future<void> university() async {
    var headers = {'Authorization': 'Bearer ${prefs.getString("token")}'};
    var dio = Dio();

    try {
      var response = await dio.get(
        'http://localhost:8000/api/universities',
        options: Options(headers: headers),
      );

      if (response.statusCode == 201) {
        if (response.data is Map && response.data['universities'] is List) {
          var universities = response.data['universities'];

          if (universities is List) {
            setState(() {
              universitiesData = universities
                  .map<Map<String, dynamic>>((university) => {
                        'id': university['id'],
                        'name': university['name'],
                      })
                  .toList();
              isLoading = false;
            });
          } else {
            setState(() {
              isLoading = false;
              errorMessage = "البيانات غير صحيحة: الجامعات ليست قائمة.";
            });
          }
        } else {
          setState(() {
            isLoading = false;
            errorMessage = "استجابة غير متوقعة: المفتاح 'universities' مفقود.";
          });
        }
      } else {
        setState(() {
          isLoading = false;
          errorMessage = "حدث خطأ أثناء تحميل البيانات.";
        });
      }
    } catch (e) {
      setState(() {
        isLoading = false;
        errorMessage = "حدث استثناء: $e";
      });
    }
  }

  @override
  void initState() {
    super.initState();
    university(); // استدعاء التابع لجلب الجامعات عند تحميل الصفحة
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        flexibleSpace: const Center(
          child: Text(
            'الجامعات',
            style: TextStyle(
              fontSize: 20.0,
              fontWeight: FontWeight.bold,
            ),
          ),
        ),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: isLoading
            ? const Center(
                child: CircularProgressIndicator()) // عرض مؤشر التحميل
            : errorMessage.isNotEmpty
                ? Center(child: Text(errorMessage)) // عرض رسالة الخطأ
                : GridView.builder(
                    gridDelegate:
                        const SliverGridDelegateWithFixedCrossAxisCount(
                      crossAxisCount: 2,
                      crossAxisSpacing: 12.0,
                      mainAxisSpacing: 12.0,
                      childAspectRatio: 3 / 2,
                    ),
                    itemCount: universitiesData.length,
                    itemBuilder: (context, index) {
                      var university = universitiesData[index];
                      return GestureDetector(
                        onTap: () {
                          // الانتقال إلى صفحة التخصصات عند النقر على الجامعة
                          Navigator.push(
                            context,
                            MaterialPageRoute(
                              builder: (context) => SpecialtiesPage(
                                universityName: university['name'],
                                universityId: university['id'].toString(),
                              ),
                            ),
                          );
                        },
                        child: Card(
                          color: const Color.fromARGB(255, 9, 241, 140),
                          shape: RoundedRectangleBorder(
                            borderRadius:
                                BorderRadius.circular(10), // حواف مستديرة
                          ),
                          child: Center(
                            child: Padding(
                              padding: const EdgeInsets.all(16.0),
                              child: Text(
                                university['name'], // اسم الجامعة
                                textAlign: TextAlign.center,
                                style: const TextStyle(
                                  fontSize: 16.0,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                            ),
                          ),
                        ),
                      );
                    },
                  ),
      ),
    );
  }
}
