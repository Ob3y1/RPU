import 'package:flutter/material.dart';
import 'package:dio/dio.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:university/cubit/user_cubit.dart';
import 'package:university/main.dart';

class SupportPage extends StatefulWidget {
  const SupportPage({super.key});

  @override
  _SupportPageState createState() => _SupportPageState();
}

class _SupportPageState extends State<SupportPage> {
  late Future<List<Map<String, String>>> frequentQuestions;

  @override
  void initState() {
    super.initState();
    frequentQuestions = frequentquestion();
  }

  Future<List<Map<String, String>>> frequentquestion() async {
    var headers = {
      'Authorization': 'Bearer ${prefs.getString("token")}', // جلب التوكن
    };
    var dio = Dio();
    var response = await dio.request(
      'http://localhost:8000/api/frequent-questions',
      options: Options(
        method: 'GET',
        headers: headers,
      ),
    );

    if (response.statusCode == 200) {
      List<dynamic> data = response.data;
      return data
          .map<Map<String, String>>((item) => {
                'question': item['question'],
                'answer': item['answer'] ?? '',
              })
          .toList();
    } else {
      throw Exception('Failed to load questions');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        flexibleSpace: const Center(
          child: Text(
            'الدعم', // العنوان الذي تود توسيطه
            style: TextStyle(
              fontSize: 20.0,
              fontWeight: FontWeight.bold,
            ),
          ),
        ),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            const SizedBox(height: 20.0),
            // Container يحتوي على TextField للسؤال
            Container(
              padding: const EdgeInsets.all(16.0),
              decoration: BoxDecoration(
                color: Colors.grey[200],
                borderRadius: BorderRadius.circular(12.0),
              ),
              child: TextField(
                controller: context
                    .read<UserCubit>()
                    .questionController, // ربط TextField مع controller
                maxLines: 5,
                decoration: const InputDecoration(
                  hintText: 'اكتب سؤالك هنا...',
                  border: InputBorder.none,
                  alignLabelWithHint: true,
                ),
                keyboardType: TextInputType.text,
              ),
            ),
            const SizedBox(height: 20.0),
            // زر إرسال
            ElevatedButton(
              onPressed: () {
                // تحقق من أن السؤال ليس فارغًا
                if (context.read<UserCubit>().questionController.text.isEmpty) {
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(content: Text('من فضلك أدخل سؤالًا أولاً')),
                  );
                } else {
                  context.read<UserCubit>().submitquestion();
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(content: Text('تم إرسال السؤال بنجاح')),
                  );

                  // حذف النص من الحقل النصي بعد الإرسال
                  context.read<UserCubit>().questionController.clear();
                }
              },
              style: ElevatedButton.styleFrom(
                elevation: 0,
                backgroundColor: const Color(0xFF00BF6D),
                foregroundColor: Colors.white,
                minimumSize: const Size(double.infinity, 48),
                shape: const StadiumBorder(),
              ),
              child: const Text("إرسال"),
            ),
            const SizedBox(height: 20.0),
            // عرض الأسئلة الشائعة
            FutureBuilder<List<Map<String, String>>>(
              future: frequentQuestions,
              builder: (context, snapshot) {
                if (snapshot.connectionState == ConnectionState.waiting) {
                  return const Center(child: CircularProgressIndicator());
                } else if (snapshot.hasError) {
                  return Center(child: Text('Error: ${snapshot.error}'));
                } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
                  return const Center(child: Text('لا توجد أسئلة شائعة'));
                } else {
                  List<Map<String, String>> questions = snapshot.data!;
                  return Expanded(
                    child: ListView.builder(
                      itemCount: questions.length,
                      itemBuilder: (context, index) {
                        final question = questions[index];
                        return Card(
                          margin: const EdgeInsets.only(bottom: 10.0),
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
                                  question['question']!,
                                  style: const TextStyle(
                                    fontSize: 18.0,
                                    fontWeight: FontWeight.bold,
                                  ),
                                ),
                                const SizedBox(height: 10.0),
                                Text(
                                  question['answer']!,
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
                    ),
                  );
                }
              },
            ),
          ],
        ),
      ),
    );
  }
}
