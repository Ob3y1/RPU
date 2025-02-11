import 'package:flutter/material.dart';
import 'package:dio/dio.dart';
import 'dart:convert';
import 'package:universityemployee/main.dart';

class QuestionDetailPage extends StatefulWidget {
  final int questionId;

  QuestionDetailPage({required this.questionId});

  @override
  _QuestionDetailPageState createState() => _QuestionDetailPageState();
}

class _QuestionDetailPageState extends State<QuestionDetailPage> {
  bool isLoading = true;
  String? errorMessage;
  dynamic questionDetail;
  TextEditingController _answerController = TextEditingController();

  @override
  void initState() {
    super.initState();
    fetchQuestionDetails();
  }

  // جلب تفاصيل السؤال
  Future<void> fetchQuestionDetails() async {
    var headers = {
      'Authorization': 'Bearer ${prefs.getString("token")}'
    }; // استخدام التوكن المحفوظ

    var dio = Dio();
    try {
      var response = await dio.get(
        'http://localhost:8000/api/questions/${widget.questionId}',
        options: Options(headers: headers),
      );

      if (response.statusCode == 201) {
        setState(() {
          questionDetail = response.data['question'];
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

  // تابع الإجابة على السؤال
  Future<void> submitAnswer() async {
    var headers = {
      'Authorization': 'Bearer ${prefs.getString("token")}',
      'Content-Type': 'application/json',
    };

    var dio = Dio();
    var data = json.encode({
      "answer": _answerController.text,
    });

    try {
      var response = await dio.request(
        'http://localhost:8000/api/questions/${widget.questionId}/answer',
        options: Options(
          method: 'POST',
          headers: headers,
        ),
        data: data,
      );

      if (response.statusCode == 201) {
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(
          content: Text('Answer submitted successfully!'),
        ));
        setState(() {
          questionDetail['answer'] = _answerController.text;
        });
        _answerController.clear(); // مسح محتوى حقل الإجابة بعد الإرسال
      } else {
        print(response.statusMessage);
      }
    } catch (e) {
      print('Error: $e');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Question Details'),
      ),
      body: isLoading
          ? Center(child: CircularProgressIndicator())
          : errorMessage != null
              ? Center(child: Text('Error: $errorMessage'))
              : Padding(
                  padding: const EdgeInsets.all(16.0),
                  child: SingleChildScrollView(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        // عرض السؤال بشكل منسق
                        Card(
                          elevation: 5,
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(10),
                          ),
                          child: Padding(
                            padding: const EdgeInsets.all(12.0),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                const Text(
                                  'Question:',
                                  style: TextStyle(
                                    fontSize: 16,
                                    fontWeight: FontWeight.bold,
                                    color: Colors.blueGrey,
                                  ),
                                ),
                                SizedBox(height: 6),
                                Text(
                                  '${questionDetail['question']}',
                                  style: TextStyle(fontSize: 16),
                                ),
                                SizedBox(height: 10),
                                Divider(),
                                SizedBox(height: 10),
                                Text(
                                  'Status: ${questionDetail['status']}',
                                  style: TextStyle(fontSize: 16),
                                ),
                                SizedBox(height: 10),
                                if (questionDetail['answer'] != null)
                                  Text(
                                    'Answer: ${questionDetail['answer']}',
                                    style: TextStyle(fontSize: 16),
                                  ),
                              ],
                            ),
                          ),
                        ),
                        SizedBox(height: 20),
                        // عرض معلومات المستخدم في بطاقة مع شكل أفضل
                        Card(
                          elevation: 5,
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(10),
                          ),
                          child: Padding(
                            padding: const EdgeInsets.all(12.0),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                const Text(
                                  'User Info:',
                                  style: TextStyle(
                                    fontSize: 16,
                                    fontWeight: FontWeight.bold,
                                    color: Colors.blueGrey,
                                  ),
                                ),
                                const SizedBox(height: 10),
                                Text('Name: ${questionDetail['user']['name']}'),
                                Text(
                                    'Phone: ${questionDetail['user']['phone_number']}'),
                                Text(
                                    'Email: ${questionDetail['user']['email']}'),
                                Text(
                                    'Birth Date: ${questionDetail['user']['birth_date']}'),
                                Text(
                                    'Nationality: ${questionDetail['user']['nationality']}'),
                                Text(
                                    'Role ID: ${questionDetail['user']['role_id']}'),
                                Text(
                                    'Payment Method ID: ${questionDetail['user']['default_payment_method_id']}'),
                              ],
                            ),
                          ),
                        ),
                        SizedBox(height: 20),
                        // تحسين مظهر حقل الإجابة داخل Container
                        Container(
                          padding: EdgeInsets.all(12.0),
                          decoration: BoxDecoration(
                            borderRadius: BorderRadius.circular(8),
                            border: Border.all(color: Colors.grey),
                          ),
                          child: TextField(
                            controller: _answerController,
                            decoration: const InputDecoration(
                              labelText: 'Enter your answer',
                              border: InputBorder.none,
                            ),
                            maxLines: 4,
                          ),
                        ),
                        const SizedBox(height: 20),
                        // محاذاة زر الإجابة في المنتصف
                        Align(
                          alignment: Alignment.center,
                          child: ElevatedButton(
                            onPressed: submitAnswer,
                            style: ElevatedButton.styleFrom(
                              backgroundColor: Colors.green,
                              padding: EdgeInsets.symmetric(vertical: 12.0),
                            ),
                            child: Text('Submit Answer'),
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
    );
  }
}
