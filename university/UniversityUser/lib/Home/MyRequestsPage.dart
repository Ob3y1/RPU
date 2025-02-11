import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:university/Home/HomePage.dart';
import 'package:university/Home/SearchPage.dart';
import 'package:university/Home/SupportPage.dart';
import 'package:university/Home/UniversitiesPage.dart';
import 'package:university/Home/myquestion.dart';
import 'package:university/Home/profile.dart';
import 'package:university/cubit/user_state.dart';
import 'package:university/user/Edit%20information.dart';
import '../cubit/user_cubit.dart';

class MyRequestsPage extends StatefulWidget {
  final Map<String, String> requestData;

  const MyRequestsPage({super.key, required this.requestData});

  @override
  _MyRequestsPageState createState() => _MyRequestsPageState();
}

class _MyRequestsPageState extends State<MyRequestsPage> {
  @override
  void initState() {
    super.initState();
    context
        .read<UserCubit>()
        .requestpage(); // استدعاء البيانات عند تحميل الصفحة
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        flexibleSpace: const Center(
          child: Text(
            'طلباتي',
            style: TextStyle(
              fontSize: 20.0,
              fontWeight: FontWeight.bold,
            ),
          ),
        ),
      ),
      drawer: Drawer(
        child: ListView(
          padding: EdgeInsets.zero,
          children: <Widget>[
            const DrawerHeader(
              decoration: BoxDecoration(
                color: Color(0xFF00BF6D),
              ),
              child: Text(
                'القائمة الجانبية',
                style: TextStyle(
                  color: Colors.white,
                  fontSize: 24,
                ),
              ),
            ),
            ListTile(
              leading: const Icon(Icons.home),
              title: const Text('الصفحة الرئيسية'),
              onTap: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(builder: (context) => const HomePage()),
                );
              },
            ),
             ListTile(
              leading: const Icon(Icons.account_circle),
              title: const Text('الملف الشخصي'), // الاسم الجديد للصفحة
              onTap: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(
                      builder: (context) =>
                          UserProfilePage()), // الصفحة المطلوبة
                );
              },
            ),
            ListTile(
              leading: const Icon(Icons.school),
              title: const Text('الجامعات'),
              onTap: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(builder: (context) => UniversitiesPage()),
                );
              },
            ),
            ListTile(
              leading: const Icon(Icons.request_page),
              title: const Text('طلباتي'),
              onTap: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(
                      builder: (context) => const MyRequestsPage(
                            requestData: {},
                          )),
                );
              },
            ),
            ListTile(
              leading: const Icon(Icons.support),
              title: const Text('الدعم'),
              onTap: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(builder: (context) => const SupportPage()),
                );
              },
            ),
            ListTile(
              leading: const Icon(Icons.support),
              title: const Text('الأسألة الخاصة بي'),
              onTap: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(
                      builder: (context) => const UserQuestionsPage()),
                );
              },
            ),
            ListTile(
              leading: const Icon(Icons.search),
              title: const Text('صفحة البحث'),
              onTap: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(builder: (context) => const SearchPage()),
                );
              },
            ),
            const Divider(), // إضافة فاصل بين العناصر
            ListTile(
              leading: const Icon(Icons.logout),
              title: const Text('تسجيل الخروج'),
              onTap: () {
                context.read<UserCubit>().logout(context);
              },
            ),
            ListTile(
              leading: const Icon(Icons.edit),
              title: const Text('تعديل المعلومات الشخصية'),
              onTap: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(builder: (context) => EditProfileScreen()),
                );
              },
            ),
          ],
        ),
      ),
      body: Container(
        decoration: const BoxDecoration(
          image: DecorationImage(
            image: AssetImage(
              "image/photo_2025-02-05_18-26-27.jpg",
            ),
            fit: BoxFit.cover,
          ),
        ),
        child: BlocBuilder<UserCubit, UserState>(
          builder: (context, state) {
            if (state is requestpageLoading) {
              return const Center(
                  child: CircularProgressIndicator()); // أثناء التحميل
            } else if (state is requestpageSuccess) {
              // إظهار الإشعار من الـ Backend
              WidgetsBinding.instance.addPostFrameCallback((_) {
                ScaffoldMessenger.of(context).showSnackBar(
                  SnackBar(
                    content: Text(state.message),
                    duration: Duration(seconds: 3),
                    backgroundColor: Colors.green,
                  ),
                );
              });

              if (state.requests.isEmpty) {
                return const Center(
                    child: Text('لا توجد طلبات مقدمة من هذا المستخدم'));
              }

              return ListView.builder(
                itemCount: state.requests.length,
                itemBuilder: (context, index) {
                  final request = state.requests[index];
                  final university =
                      request['specialization_per_university']['university'];
                  final specialization =
                      request['specialization_per_university']
                          ['specialization'];
                  final payment = request['payment'];
                  final paymentMethod = payment['payment_method'];

                  return Card(
                    margin: const EdgeInsets.all(10),
                    elevation: 4,
                    shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(10)),
                    child: Padding(
                      padding: const EdgeInsets.all(10),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text('📌 طلب رقم: ${request['id']}',
                              style: TextStyle(
                                  fontWeight: FontWeight.bold, fontSize: 18)),
                          Divider(),
                          Text(
                              '📍 الجامعة: ${university['name']} - ${university['location']}'),
                          Text('📚 التخصص: ${specialization['name']}'),
                          Text('🛠️ حالة الطلب: ${request['request_status']}',
                              style: TextStyle(color: Colors.blue)),
                          Divider(),
                          Text(
                              '💵 المبلغ المدفوع: ${payment['amount']} ${payment['currency']}'),
                          Text('💳 طريقة الدفع: ${paymentMethod['method']}'),
                          Text('📅 تاريخ الدفع: ${payment['payment_date']}'),
                        ],
                      ),
                    ),
                  );
                },
              );
            } else if (state is requestpageFailure) {
              return Center(
                  child: Text('خطأ: ${state.errMessage}',
                      style: TextStyle(color: Colors.red)));
            }
            return const Center(child: Text('لا توجد بيانات'));
          },
        ),
      ),
    );
  }
}
