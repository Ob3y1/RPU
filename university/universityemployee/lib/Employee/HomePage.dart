import 'package:flutter/material.dart';
import 'package:dio/dio.dart';
import 'package:universityemployee/Employee/QuestionDetailPage.dart';
import 'package:universityemployee/Employee/SignInScreen.dart';
import 'package:universityemployee/main.dart';
import 'dart:convert';

class HomePage extends StatefulWidget {
  @override
  _HomePageState createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  List<dynamic> questions = [];
  bool isLoading = true;
  String? errorMessage;

  @override
  void initState() {
    super.initState();
    fetchQuestions();
  }

  // تابع جلب الأسئلة من الـ API
  Future<void> fetchQuestions() async {
    var headers = {
      'Authorization': 'Bearer ${prefs.getString("token")}'
    }; // استخدام التوكن المحفوظ

    var dio = Dio();
    try {
      var response = await dio.get(
        'http://localhost:8000/api/questions',
        options: Options(headers: headers),
      );

      if (response.statusCode == 201) {
        setState(() {
          questions = response.data['questions'];
          isLoading = false;
        });
      } else {
        setState(() {
          errorMessage = 'Error: ${response.statusCode}';
          isLoading = false;
        });
      }
    } catch (e) {
      setState(() {
        errorMessage = e.toString();
        isLoading = false;
      });
    }
  }

  // تابع تحديد السؤال كمكرر
  Future<void> markAsFrequent(int questionId, int index) async {
    var headers = {'Authorization': 'Bearer ${prefs.getString("token")}'};

    var dio = Dio();
    try {
      var response = await dio.post(
        'http://localhost:8000/api/questions/$questionId/mark-frequent',
        options: Options(headers: headers),
      );

      if (response.statusCode == 201) {
        setState(() {
          questions[index]['is_frequent'] = 1; // تحديث القيمة
        });
        print("Question marked as frequent");
      } else {
        print('Error: ${response.statusMessage}');
      }
    } catch (e) {
      print('Error: $e');
    }
  }

  // تابع إزالة السؤال من المكرر
  Future<void> removeFrequentQuestion(int questionId, int index) async {
    var headers = {'Authorization': 'Bearer ${prefs.getString("token")}'};

    var dio = Dio();
    try {
      var response = await dio.post(
        'http://localhost:8000/api/questions/$questionId/remove-frequent',
        options: Options(headers: headers),
      );

      if (response.statusCode == 201) {
        setState(() {
          questions[index]['is_frequent'] = 0; // تحديث القيمة
        });
        print("Question marked as not frequent");
      } else {
        print('Error: ${response.statusMessage}');
      }
    } catch (e) {
      print('Error: $e');
    }
  }

  // تابع تسجيل الخروج
  Future<void> logout() async {
    var headers = {'Authorization': 'Bearer ${prefs.getString("token")}'};
    var dio = Dio();
    var response = await dio.post(
      'http://localhost:8000/api/employeeLogout',
      options: Options(headers: headers),
    );

    if (response.statusCode == 201) {
      print(json.encode(response.data));
      Navigator.pushReplacement(
        context,
        MaterialPageRoute(builder: (context) => SignInScreen()),
      );
    } else {
      print(response.statusMessage);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Questions'),
        actions: [
          IconButton(
            icon: Icon(Icons.logout),
            onPressed: logout,
          ),
        ],
      ),
      body: isLoading
          ? const Center(child: CircularProgressIndicator())
          : errorMessage != null
              ? Center(child: Text('Error: $errorMessage'))
              : ListView.builder(
                  itemCount: questions.length,
                  itemBuilder: (context, index) {
                    final question = questions[index];
                    return Card(
                      margin: EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                      child: ListTile(
                        title: Text(question['question']),
                        subtitle: Text(
                            'Status: ${question['status']}'), // حالة السؤال
                        trailing: question['is_frequent'] == 1
                            ? Icon(Icons.check_circle, color: Colors.green)
                            : Icon(Icons.pending, color: Colors.orange),

                        onTap: () {
                          // الانتقال إلى صفحة تفاصيل السؤال
                          Navigator.push(
                            context,
                            MaterialPageRoute(
                              builder: (context) => QuestionDetailPage(
                                questionId: question['id'],
                              ),
                            ),
                          );
                        },
                        // عند الضغط المطول يتم التحقق من is_frequent واستدعاء التابع المناسب
                        onLongPress: () {
                          if (question['is_frequent'] == 1) {
                            removeFrequentQuestion(question['id'], index);
                          } else {
                            markAsFrequent(question['id'], index);
                          }
                        },
                      ),
                    );
                  },
                ),
    );
  }
}
