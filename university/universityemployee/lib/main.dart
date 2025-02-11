import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:universityemployee/Employee/HomePage.dart';
import 'package:universityemployee/Employee/SignInScreen.dart';
import 'package:universityemployee/cubit/user_cubit.dart';

late SharedPreferences prefs;

void main() async {
  WidgetsFlutterBinding.ensureInitialized(); // تأكد من تهيئة Flutter
  prefs = await SharedPreferences.getInstance(); // تحميل SharedPreferences
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return BlocProvider(
      create: (context) => UserCubit(),
      child: MaterialApp(
        title: 'Flutter App',
        theme: ThemeData(
          primarySwatch: Colors.blue,
        ),
        debugShowCheckedModeBanner: false,
        home: prefs.getString("token") == null ? SignInScreen() : HomePage(),
      ),
    );
  }
}
