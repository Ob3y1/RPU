import 'package:dio/dio.dart';
import 'package:flutter/material.dart';
import 'package:university/main.dart';

class UserQuestionsPage extends StatefulWidget {
  const UserQuestionsPage({super.key});

  @override
  _UserQuestionsPageState createState() => _UserQuestionsPageState();
}

class _UserQuestionsPageState extends State<UserQuestionsPage> {
  late Future<List<Map<String, dynamic>>> questionsList;

  @override
  void initState() {
    super.initState();
    questionsList = fetchUserQuestions();
  }

  Future<List<Map<String, dynamic>>> fetchUserQuestions() async {
    var headers = {
      'Authorization': 'Bearer ${prefs.getString("token")}' // جلب التوكن
    };
    var dio = Dio();
    var response = await dio.request(
      'http://localhost:8000/api/user-questions',
      options: Options(
        method: 'GET',
        headers: headers,
      ),
    );

    if (response.statusCode == 200) {
      List<dynamic> data = response.data;
      return List<Map<String, dynamic>>.from(data);
    } else {
      throw Exception('خطأ في جلب البيانات: ${response.statusMessage}');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        flexibleSpace: const Center(
          child: Text(
            'أسئلتي',
            style: TextStyle(fontSize: 20.0, fontWeight: FontWeight.bold),
          ),
        ),
      ),
      body: FutureBuilder<List<Map<String, dynamic>>>(
        future: questionsList,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          } else if (snapshot.hasError) {
            return Center(child: Text('Error: ${snapshot.error}'));
          } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
            return const Center(child: Text('لا توجد أسئلة لعرضها'));
          } else {
            List<Map<String, dynamic>> questions = snapshot.data!;
            return ListView.builder(
              itemCount: questions.length,
              itemBuilder: (context, index) {
                final question = questions[index];
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
                          'السؤال: ${question['question']}',
                          style: const TextStyle(
                            fontSize: 18.0,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        const SizedBox(height: 10.0),
                        Text(
                          'الحالة: ${question['status']}',
                          style: const TextStyle(
                            fontSize: 16.0,
                            color: Colors.black54,
                          ),
                        ),
                        const SizedBox(height: 10.0),
                        Text(
                          'الإجابة: ${question['answer'] ?? 'لا توجد إجابة بعد'}',
                          style: const TextStyle(
                            fontSize: 16.0,
                            color: Colors.black54,
                          ),
                        ),
                      ],
                    ),
                  ),
                );
              },
            );
          }
        },
      ),
    );
  }
}
