import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:university/Home/HomePage.dart';
import 'package:university/cubit/user_cubit.dart';
import 'package:university/cubit/user_state.dart';
import 'package:university/user/sign%20up.dart';

class SignInScreen extends StatelessWidget {
  final _formKey = GlobalKey<FormState>();

  SignInScreen({super.key});

  // دالة للتحقق إذا كان المدخل بريد إلكتروني
  bool _isEmail(String value) {
    String pattern = r"^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$";
    RegExp regex = RegExp(pattern);
    return regex.hasMatch(value);
  }

  // دالة للتحقق إذا كان المدخل رقم هاتف يبدأ بـ 09
  bool _isPhone(String value) {
    String pattern = r'^09\d{8}$'; // تحقق من رقم هاتف محلي يبدأ بـ 09
    RegExp regex = RegExp(pattern);
    return regex.hasMatch(value);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: BlocConsumer<UserCubit, UserState>(
        listener: (context, state) {
          if (state is SignInSuccess) {
            ScaffoldMessenger.of(context).showSnackBar(
              const SnackBar(content: Text("Success")),
            );
            Navigator.pushAndRemoveUntil(
              context,
              MaterialPageRoute(
                builder: (context) => const HomePage(),
              ),
              (route) => false,
            );
          } else if (state is SignInFailure) {
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(content: Text(state.errMessage)),
            );
          }
        },
        builder: (context, state) {
          return SafeArea(
            child: SingleChildScrollView(
              child: ConstrainedBox(
                constraints: BoxConstraints(
                  minHeight: MediaQuery.of(context).size.height,
                ),
                child: IntrinsicHeight(
                  child: Padding(
                    padding: const EdgeInsets.symmetric(horizontal: 16.0),
                    child: Column(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        const SizedBox(height: 100),
                        const Text(
                          "Sign In",
                          style: TextStyle(
                            fontSize: 32,
                            fontWeight: FontWeight.bold,
                            color: Colors.black,
                          ),
                        ),
                        const SizedBox(height: 40),
                        Form(
                          key: _formKey,
                          child: Column(
                            children: [
                              // حقل الهاتف أو البريد الإلكتروني
                              Container(
                                margin:
                                    const EdgeInsets.symmetric(vertical: 8.0),
                                child: TextFormField(
                                  controller: context
                                      .read<UserCubit>()
                                      .signInEmailPhone,
                                  decoration: const InputDecoration(
                                    hintText: 'Phone or Email',
                                    filled: true,
                                    fillColor: Color(0xFFF5FCF9),
                                    contentPadding: EdgeInsets.symmetric(
                                        horizontal: 24.0, vertical: 16.0),
                                    border: OutlineInputBorder(
                                      borderSide: BorderSide.none,
                                      borderRadius:
                                          BorderRadius.all(Radius.circular(50)),
                                    ),
                                  ),
                                  keyboardType: TextInputType.text,
                                  validator: (value) {
                                    if (value == null || value.isEmpty) {
                                      return 'Please enter your phone number or email';
                                    }
                                    if (!_isEmail(value) && !_isPhone(value)) {
                                      return 'Please enter a valid phone number (starting with 09) or email';
                                    }
                                    return null;
                                  },
                                ),
                              ),
                              const SizedBox(height: 16.0),

                              // حقل كلمة السر
                              Container(
                                margin:
                                    const EdgeInsets.symmetric(vertical: 8.0),
                                child: TextFormField(
                                  controller:
                                      context.read<UserCubit>().signInPassword,
                                  obscureText: true,
                                  decoration: const InputDecoration(
                                    hintText: 'Password',
                                    filled: true,
                                    fillColor: Color(0xFFF5FCF9),
                                    contentPadding: EdgeInsets.symmetric(
                                        horizontal: 24.0, vertical: 16.0),
                                    border: OutlineInputBorder(
                                      borderSide: BorderSide.none,
                                      borderRadius:
                                          BorderRadius.all(Radius.circular(50)),
                                    ),
                                  ),
                                  validator: (value) {
                                    if (value == null || value.isEmpty) {
                                      return 'Please enter your password';
                                    }
                                    return null;
                                  },
                                ),
                              ),
                              const SizedBox(height: 16.0),

                              // زر تسجيل الدخول
                              state is SignInLoading
                                  ? const CircularProgressIndicator()
                                  : ElevatedButton(
                                      onPressed: () {
                                        if (_formKey.currentState!.validate()) {
                                          context.read<UserCubit>().signIn();
                                        }
                                      },
                                      style: ElevatedButton.styleFrom(
                                        elevation: 0,
                                        backgroundColor:
                                            const Color(0xFF00BF6D),
                                        foregroundColor: Colors.white,
                                        minimumSize:
                                            const Size(double.infinity, 48),
                                        shape: const StadiumBorder(),
                                      ),
                                      child: const Text("Sign in"),
                                    ),
                              const SizedBox(height: 16.0),

                              // رابط للتسجيل
                              TextButton(
                                onPressed: () {
                                  Navigator.push(
                                    context,
                                    MaterialPageRoute(
                                        builder: (context) => SignUpScreen()),
                                  );
                                },
                                child: Text.rich(
                                  const TextSpan(
                                    text: "Don’t have an account? ",
                                    children: [
                                      TextSpan(
                                        text: "Sign Up",
                                        style:
                                            TextStyle(color: Color(0xFF00BF6D)),
                                      ),
                                    ],
                                  ),
                                  style: Theme.of(context)
                                      .textTheme
                                      .bodyMedium!
                                      .copyWith(
                                        color: Theme.of(context)
                                            .textTheme
                                            .bodyLarge!
                                            .color!
                                            .withOpacity(0.64),
                                      ),
                                ),
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
              ),
            ),
          );
        },
      ),
    );
  }
}
