import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:university/cubit/user_cubit.dart';
import 'package:university/cubit/user_state.dart';

class UserProfilePage extends StatefulWidget {
  @override
  _EditProfileScreenState createState() => _EditProfileScreenState();
}

class _EditProfileScreenState extends State<UserProfilePage> {
  @override
  void initState() {
    super.initState();
    // جلب البيانات من UserCubit عند تحميل الصفحة
    final userCubit = context.read<UserCubit>();
    userCubit
        .showuserprofile(); // تأكد من أن هذه الدالة تقوم بجلب البيانات وتحديث الحقول
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        title: const Text("My Profile"),
        backgroundColor: const Color(0xFF00BF6D),
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.symmetric(horizontal: 16.0),
          child: BlocBuilder<UserCubit, UserState>(
            builder: (context, state) {
              if (state is UserLoading) {
                return const Center(child: CircularProgressIndicator());
              } else if (state is UserSuccess) {
                return Form(
                  child: Column(
                    children: [
                      const SizedBox(height: 20),

                      // حقل الاسم
                      TextFormField(
                        controller:
                            context.read<UserCubit>().editfullNameController,
                        decoration: const InputDecoration(
                          hintText: 'Full Name',
                          filled: true,
                          fillColor: Color(0xFFF5FCF9),
                          contentPadding: EdgeInsets.symmetric(
                              horizontal: 24.0, vertical: 16.0),
                          border: OutlineInputBorder(
                            borderSide: BorderSide.none,
                            borderRadius: BorderRadius.all(Radius.circular(50)),
                          ),
                        ),
                      ),
                      const SizedBox(height: 16.0),

                      // حقل البريد الإلكتروني
                      TextFormField(
                        controller:
                            context.read<UserCubit>().editemailController,
                        decoration: const InputDecoration(
                          hintText: 'Email',
                          filled: true,
                          fillColor: Color(0xFFF5FCF9),
                          contentPadding: EdgeInsets.symmetric(
                              horizontal: 24.0, vertical: 16.0),
                          border: OutlineInputBorder(
                            borderSide: BorderSide.none,
                            borderRadius: BorderRadius.all(Radius.circular(50)),
                          ),
                        ),
                        keyboardType: TextInputType.emailAddress,
                      ),
                      const SizedBox(height: 16.0),

                      TextFormField(
                        controller:
                            context.read<UserCubit>().editphoneController,
                        decoration: const InputDecoration(
                          hintText: 'Phone Number',
                          filled: true,
                          fillColor: Color(0xFFF5FCF9),
                          contentPadding: EdgeInsets.symmetric(
                            horizontal: 24.0,
                            vertical: 16.0,
                          ),
                          border: OutlineInputBorder(
                            borderSide: BorderSide.none,
                            borderRadius: BorderRadius.all(Radius.circular(50)),
                          ),
                        ),
                        keyboardType: TextInputType.phone,
                      ),

                      const SizedBox(height: 16.0),
                      // حقل الاسم
                      TextFormField(
                        controller:
                            context.read<UserCubit>().editpasswordController,
                        decoration: const InputDecoration(
                          hintText: 'PassWord',
                          filled: true,
                          fillColor: Color(0xFFF5FCF9),
                          contentPadding: EdgeInsets.symmetric(
                              horizontal: 24.0, vertical: 16.0),
                          border: OutlineInputBorder(
                            borderSide: BorderSide.none,
                            borderRadius: BorderRadius.all(Radius.circular(50)),
                          ),
                        ),
                      ),
                      const SizedBox(height: 16.0),
                      // حقل تاريخ الميلاد
                      TextFormField(
                        controller:
                            context.read<UserCubit>().editbirthDateController,
                        readOnly: true,
                        decoration: const InputDecoration(
                          hintText: 'Birth Date',
                          filled: true,
                          fillColor: Color(0xFFF5FCF9),
                          contentPadding: EdgeInsets.symmetric(
                              horizontal: 24.0, vertical: 16.0),
                          border: OutlineInputBorder(
                            borderSide: BorderSide.none,
                            borderRadius: BorderRadius.all(Radius.circular(50)),
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
                                    .editbirthDateController
                                    .text =
                                "${pickedDate.year}-${pickedDate.month}-${pickedDate.day}";
                          }
                        },
                      ),
                      const SizedBox(height: 16.0),

                      TextFormField(
                        controller:
                            context.read<UserCubit>().editnationalityController,
                        decoration: const InputDecoration(
                          hintText: 'Nationality',
                          filled: true,
                          fillColor: Color(0xFFF5FCF9),
                          contentPadding: EdgeInsets.symmetric(
                              horizontal: 24.0, vertical: 16.0),
                          border: OutlineInputBorder(
                            borderSide: BorderSide.none,
                            borderRadius: BorderRadius.all(Radius.circular(50)),
                          ),
                        ),
                      ),
                      DropdownButtonFormField<String>(
                        value: context.read<UserCubit>().selectedPaymentMethod,
                        decoration: const InputDecoration(
                          filled: true,
                          fillColor: Color(0xFFF5FCF9),
                          contentPadding: EdgeInsets.symmetric(
                              horizontal: 24.0, vertical: 16.0),
                          border: OutlineInputBorder(
                            borderSide: BorderSide.none,
                            borderRadius: BorderRadius.all(Radius.circular(50)),
                          ),
                        ),
                        items: context
                            .read<UserCubit>()
                            .paymentMethods
                            .map((method) {
                          return DropdownMenuItem<String>(
                            value: method['id'].toString(),
                            child: Text(method['method']),
                          );
                        }).toList(),
                        onChanged: (String? value) {},
                      ),
                    ],
                  ),
                );
              } else if (state is UserFailure) {
                return Center(child: Text(state.errMessage));
              } else {
                return const Center(child: Text("No data available"));
              }
            },
          ),
        ),
      ),
    );
  }
}
