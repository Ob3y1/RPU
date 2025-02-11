import 'dart:convert';

import 'package:dio/dio.dart';
import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:universityemployee/Employee/SignInScreen.dart';
import 'package:universityemployee/cubit/usrt_state.dart';
import 'package:universityemployee/main.dart';

class UserCubit extends Cubit<UserState> {
  UserCubit() : super(UserInitial());
  TextEditingController signInEmailPhone = TextEditingController();
  TextEditingController signInPassword = TextEditingController();
  GlobalKey<FormState> signInFormKey = GlobalKey();

  signIn() async {
    var headers = {'Content-Type': 'application/json'};
    var data = json.encode(
        {"identifier": signInEmailPhone.text, "password": signInPassword.text});

    var dio = Dio();
    var response = await dio.request(
      'http://localhost:8000/api/employeeLogin',
      options: Options(
        method: 'POST',
        headers: headers,
      ),
      data: data,
    );

    if (response.statusCode == 201) {
      // بعد الحصول على الـ token من الرد
      String token = response.data['token']; // تأكد من اسم المفتاح في الرد

      // حفظ الـ token في SharedPreferences
      SharedPreferences prefs = await SharedPreferences.getInstance();
      await prefs.setString('token', token);

      print("Token saved: $token");

      // يمكن هنا الانتقال إلى صفحة الـ HomePage أو أي منطق آخر بعد تسجيل الدخول
    } else {
      print(response.statusMessage);
    }
  }

  logout(BuildContext context) async {
    try {
      var headers = {'Authorization': 'Bearer ${prefs.getString("token")}'};
      var dio = Dio();
      var response = await dio.post(
        'http://localhost:8000/api/employeeLogout',
        options: Options(
          method: 'POST',
          headers: headers,
        ),
      );

      if (response.statusCode == 201) {
        // استجابة ناجحة، عرض إشعار
        final message = response.data['message'] ?? 'Logged out successfully';
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(message)),
        );

        // حذف الـ token بعد تسجيل الخروج
        prefs.remove('token');

        // الانتقال إلى شاشة تسجيل الدخول
        Navigator.pushReplacement(
          context,
          MaterialPageRoute(builder: (context) => SignInScreen()),
        );
      } else {
        // في حال فشل الاستجابة، عرض خطأ
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Error: ${response.statusMessage}')),
        );
      }
    } catch (e) {
      // في حال حدوث استثناء، عرض إشعار بالخطأ
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('An error occurred: $e')),
      );
    }
  }
}
