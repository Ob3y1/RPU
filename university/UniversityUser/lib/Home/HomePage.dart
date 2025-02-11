import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:university/Home/SearchPage.dart';
import 'package:university/Home/MyRequestsPage.dart';
import 'package:university/Home/SupportPage.dart';
import 'package:university/Home/UniversitiesPage.dart';
import 'package:university/Home/myquestion.dart';
import 'package:university/Home/profile.dart';
import 'package:university/cubit/user_cubit.dart';
import 'package:university/user/Edit%20information.dart';

class HomePage extends StatelessWidget {
  const HomePage({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      title: 'Drawer Example',
      theme: ThemeData(
        primarySwatch: Colors.green,
      ),
      home: const HomeScreen(),
    );
  }
}

class HomeScreen extends StatelessWidget {
  const HomeScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        flexibleSpace: const Center(
          child: Text(
            'RPU', // العنوان الذي تود توسيطه
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
                    builder: (context) => MyRequestsPage(requestData: {}),
                  ),
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
              title: const Text('الأسئلة الخاصة بي'),
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
              "image/photo_2025-01-31_14-49-53.jpg",
            ),
            fit: BoxFit.cover,
          ),
        ),
        alignment: Alignment.topCenter, // يجعل النص في الأعلى بالمنتصف
        padding: const EdgeInsets.only(top: 20), // لإضافة مسافة من الأعلى
        child: const Text(
          '!مرحباً بك في التطبيق',
          style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
        ),
      ),
    );
  }
}
