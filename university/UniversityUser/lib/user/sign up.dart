import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:university/cubit/user_cubit.dart';
import 'package:university/cubit/user_state.dart';
import 'package:university/user/sign%20in.dart';

class SignUpScreen extends StatelessWidget {
  final _formKey = GlobalKey<FormState>();

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        appBar: AppBar(),
        body: BlocConsumer<UserCubit, UserState>(listener: (context, state) {
          if (state is SignUpSuccess) {
            ScaffoldMessenger.of(context).showSnackBar(
              const SnackBar(content: Text("Success")),
            );
            Navigator.pushAndRemoveUntil(
              context,
              MaterialPageRoute(
                builder: (context) => SignInScreen(),
              ),
              (route) => false,
            );
          } else if (state is SignUpFailure) {
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(
                content: Text(state.errMessage),
                backgroundColor: Colors.grey,
              ),
            );
          }
        }, builder: (context, state) {
          return SafeArea(
            child: SingleChildScrollView(
              padding: const EdgeInsets.symmetric(horizontal: 16.0),
              child: Column(
                children: [
                  const Text(
                    "Sign Up",
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
                        // حقل الاسم الكامل
                        TextFormField(
                          controller:
                              context.read<UserCubit>().fullNameController,
                          decoration: const InputDecoration(
                            hintText: 'Full Name',
                            filled: true,
                            fillColor: Color(0xFFF5FCF9),
                            contentPadding: EdgeInsets.symmetric(
                                horizontal: 16.0 * 1.5, vertical: 16.0),
                            border: OutlineInputBorder(
                              borderSide: BorderSide.none,
                              borderRadius:
                                  BorderRadius.all(Radius.circular(50)),
                            ),
                          ),
                          validator: (value) {
                            if (value == null || value.isEmpty) {
                              return 'Please enter your full name';
                            }
                            return null;
                          },
                          onSaved: (name) {
                            // Save full name
                          },
                        ),
                        const SizedBox(height: 16.0),

                        // حقل رقم الهاتف
                        TextFormField(
                          controller: context.read<UserCubit>().phoneController
                            ..text = context
                                    .read<UserCubit>()
                                    .phoneController
                                    .text
                                    .isEmpty
                                ? '09'
                                : context
                                    .read<UserCubit>()
                                    .phoneController
                                    .text,
                          decoration: const InputDecoration(
                            hintText: 'Phone Number',
                            filled: true,
                            fillColor: Color(0xFFF5FCF9),
                            contentPadding: EdgeInsets.symmetric(
                              horizontal: 16.0 * 1.5,
                              vertical: 16.0,
                            ),
                            border: OutlineInputBorder(
                              borderSide: BorderSide.none,
                              borderRadius:
                                  BorderRadius.all(Radius.circular(50)),
                            ),
                          ),
                          keyboardType: TextInputType.phone,
                          validator: (value) {
                            if (value == null || value.isEmpty) {
                              return 'Please enter your phone number';
                            }
                            // تحقق إذا كان رقم الهاتف يحتوي على 10 أرقام
                            if (value.length != 10) {
                              return 'Phone number must be exactly 10 digits';
                            }
                            // تحقق إذا كانت البداية بـ "09"
                            if (!value.startsWith('09')) {
                              return 'Phone number must start with 09';
                            }
                            // تحقق من أن الرقم يحتوي فقط على أرقام
                            if (!RegExp(r'^[0-9]+$').hasMatch(value)) {
                              return 'Phone number must contain only digits';
                            }
                            return null;
                          },
                          onChanged: (value) {
                            // إذا حاول المستخدم حذف البادئة "09"، يتم إعادتها تلقائيًا
                            if (!value.startsWith('09')) {
                              context.read<UserCubit>().phoneController.text =
                                  '09';
                              context
                                  .read<UserCubit>()
                                  .phoneController
                                  .selection = TextSelection.fromPosition(
                                TextPosition(
                                    offset: context
                                        .read<UserCubit>()
                                        .phoneController
                                        .text
                                        .length),
                              );
                            }
                          },
                          onSaved: (phone) {
                            // حفظ رقم الهاتف
                          },
                        ),

                        const SizedBox(height: 16.0),

                        // حقل البريد الإلكتروني
                        TextFormField(
                          controller: context.read<UserCubit>().emailController,
                          decoration: const InputDecoration(
                            hintText: 'Email',
                            filled: true,
                            fillColor: Color(0xFFF5FCF9),
                            contentPadding: EdgeInsets.symmetric(
                                horizontal: 16.0 * 1.5, vertical: 16.0),
                            border: OutlineInputBorder(
                              borderSide: BorderSide.none,
                              borderRadius:
                                  BorderRadius.all(Radius.circular(50)),
                            ),
                          ),
                          keyboardType: TextInputType.emailAddress,
                          validator: (value) {
                            if (value == null || value.isEmpty) {
                              return 'Please enter your email';
                            }
                            if (!RegExp(
                                    r"^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$")
                                .hasMatch(value)) {
                              return 'Please enter a valid email address';
                            }

                            return null;
                          },
                          onSaved: (email) {
                            // Save email
                          },
                        ),
                        const SizedBox(height: 16.0),
// حقل تاريخ الميلاد
                        TextFormField(
                          controller:
                              context.read<UserCubit>().birthDateController,
                          readOnly: true,
                          decoration: const InputDecoration(
                            hintText: 'Birth Date',
                            filled: true,
                            fillColor: Color(0xFFF5FCF9),
                            contentPadding: EdgeInsets.symmetric(
                                horizontal: 16.0 * 1.5, vertical: 16.0),
                            border: OutlineInputBorder(
                              borderSide: BorderSide.none,
                              borderRadius:
                                  BorderRadius.all(Radius.circular(50)),
                            ),
                          ),
                          onTap: () async {
                            DateTime? pickedDate = await showDatePicker(
                              context: context,
                              initialDate: DateTime.now(),
                              firstDate: DateTime(1900),
                              lastDate: DateTime.now(),
                            );
                            if (pickedDate != null) {
                              context
                                      .read<UserCubit>()
                                      .birthDateController
                                      .text =
                                  "${pickedDate.year}-${pickedDate.month}-${pickedDate.day}";
                            }
                          },
                          validator: (value) {
                            if (value == null || value.isEmpty) {
                              return 'Please select your birth date';
                            }
                            return null;
                          },
                        ),
                        const SizedBox(height: 16.0),
                        // حقل كلمة المرور
                        TextFormField(
                          controller:
                              context.read<UserCubit>().passwordController,
                          obscureText: true,
                          decoration: const InputDecoration(
                            hintText: 'Password',
                            filled: true,
                            fillColor: Color(0xFFF5FCF9),
                            contentPadding: EdgeInsets.symmetric(
                                horizontal: 16.0 * 1.5, vertical: 16.0),
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
                            if (value.length < 4) {
                              return 'Password must be at least 6 characters';
                            }
                            return null;
                          },
                          onSaved: (password) {
                            // Save password
                          },
                        ),
                        const SizedBox(height: 16.0),

                        // حقل تأكيد كلمة المرور
                        TextFormField(
                          controller: context
                              .read<UserCubit>()
                              .confirmPasswordController,
                          obscureText: true,
                          decoration: const InputDecoration(
                            hintText: 'Confirm Password',
                            filled: true,
                            fillColor: Color(0xFFF5FCF9),
                            contentPadding: EdgeInsets.symmetric(
                                horizontal: 16.0 * 1.5, vertical: 16.0),
                            border: OutlineInputBorder(
                              borderSide: BorderSide.none,
                              borderRadius:
                                  BorderRadius.all(Radius.circular(50)),
                            ),
                          ),
                          validator: (value) {
                            if (value == null || value.isEmpty) {
                              return 'Please confirm your password';
                            }
                            if (value !=
                                context
                                    .read<UserCubit>()
                                    .passwordController
                                    .text) {
                              return 'Passwords do not match';
                            }
                            return null;
                          },
                          onSaved: (confirmPassword) {
                            // Save confirm password
                          },
                        ),
                        const SizedBox(height: 32.0),
                        // حقل الجنسية
                        TextFormField(
                          controller:
                              context.read<UserCubit>().nationalityController,
                          decoration: const InputDecoration(
                            hintText: 'Nationality',
                            filled: true,
                            fillColor: Color(0xFFF5FCF9),
                            contentPadding: EdgeInsets.symmetric(
                                horizontal: 16.0 * 1.5, vertical: 16.0),
                            border: OutlineInputBorder(
                              borderSide: BorderSide.none,
                              borderRadius:
                                  BorderRadius.all(Radius.circular(50)),
                            ),
                          ),
                          validator: (value) {
                            if (value == null || value.isEmpty) {
                              return 'Please enter your nationality';
                            }
                            return null;
                          },
                        ),
                        const SizedBox(height: 16.0),
                        // زر إنشاء حساب
                        state is SignUpLoading
                            ? const CircularProgressIndicator() // إذا كانت الحالة هي تحميل يظهر دائرة التحميل
                            : ElevatedButton(
                                onPressed: () {
                                  if (_formKey.currentState!.validate()) {
                                    context.read<UserCubit>().signup(context);
                                  }
                                },
                                style: ElevatedButton.styleFrom(
                                  elevation: 0,
                                  backgroundColor: const Color(0xFF00BF6D),
                                  foregroundColor: Colors.white,
                                  minimumSize: const Size(double.infinity, 48),
                                  shape: const StadiumBorder(),
                                ),
                                child: const Text("Sign Up"),
                              ),
                        const SizedBox(height: 16.0),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          );
        }));
  }
}
