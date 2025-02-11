import 'dart:convert';
import 'package:dio/dio.dart'
    show Dio, DioError, DioException, FormData, MultipartFile, Options;
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:university/Home/MyRequestsPage.dart';
import 'package:university/cubit/user_state.dart';
import 'package:university/main.dart';
import 'package:university/user/sign%20in.dart';

class UserCubit extends Cubit<UserState> {
  UserCubit() : super(UserInitial());
  TextEditingController signInEmailPhone = TextEditingController();
  TextEditingController signInPassword = TextEditingController();
  GlobalKey<FormState> signInFormKey = GlobalKey();
  final TextEditingController fullNameController = TextEditingController();
  final TextEditingController phoneController = TextEditingController();
  final TextEditingController emailController = TextEditingController();
  final TextEditingController passwordController = TextEditingController();
  final TextEditingController confirmPasswordController =
      TextEditingController();
  final TextEditingController nationalityController = TextEditingController();
  final TextEditingController birthDateController = TextEditingController();
  final TextEditingController editfullNameController = TextEditingController();
  final TextEditingController editphoneController = TextEditingController();
  final TextEditingController editemailController = TextEditingController();
  final TextEditingController editpasswordController = TextEditingController();
  final TextEditingController editconfirmPasswordController =
      TextEditingController();
  final TextEditingController editnationalityController =
      TextEditingController();
  final TextEditingController editbirthDateController = TextEditingController();
  TextEditingController scoreController = TextEditingController();
  late final TextEditingController editdefaultpaymentmethod =
      TextEditingController();
  List<Map<String, dynamic>> paymentMethods = [];
  String? selectedPaymentMethod;
  String? selectedSpecialization;
  String? selectedLocation;
  String selectedCountry = '';
  String? identityImagePath;
  String? certificateImagePath;
  final TextEditingController questionController =
      TextEditingController(); // TextController
  final TextEditingController identityImageController = TextEditingController();
  final TextEditingController certificateImageController =
      TextEditingController();
  late String specialtyName;
  late String universityName;
  late int typeId;
  // دالة لحفظ مجموع الطالب
  void setScore(String score) {
    scoreController.text = score;
  }

  // دالة لحفظ بلد الشهادة
  void setCountry(String country) {
    selectedCountry = country;
  }

  // دالة لحفظ مسار الصورة
  void setImagePath(bool isIdentity, String path) {
    if (isIdentity) {
      identityImagePath = path;
    } else {
      certificateImagePath = path;
    }
  }

  signIn() async {
    try {
      emit(SignInLoading());

      // طلب تسجيل الدخول
      final response = await Dio().post(
        "http://localhost:8000/api/login",
        data: {
          "identifier": signInEmailPhone.text,
          "password": signInPassword.text,
        },
      );

      // استخراج token من الاستجابة
      final token = response.data[
          'token']; // تأكد من أن "token" هو المفتاح الصحيح للاستجابة من الخادم

      // حفظ token باستخدام shared_preferences
      final prefs = await SharedPreferences.getInstance();
      await prefs.setString('token', token);

      emit(SignInSuccess());
      print(response);
      print("Token saved: $token");
    } on DioError catch (e) {
      // معالجة الأخطاء من الاستجابة
      emit(SignInFailure(
        errMessage: e.response?.data['message'] ?? 'Login failed',
      ));
      print(e.response?.data ?? e.message);
    } catch (e) {
      // معالجة أخطاء أخرى
      emit(SignInFailure(errMessage: e.toString()));
      print(e.toString());
    }
  }

  logout(BuildContext context) async {
    try {
      var headers = {'Authorization': 'Bearer ${prefs.getString("token")}'};
      var dio = Dio();
      var response = await dio.post(
        'http://localhost:8000/api/logout',
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

  signup(BuildContext context) async {
    emit(SignUpLoading()); // الحالة "جارٍ التسجيل"

    var headers = {'Content-Type': 'application/json'};
    var data = json.encode({
      "name": fullNameController.text,
      "phone_number": phoneController.text,
      "email": emailController.text,
      "birth_date": birthDateController.text,
      "password": passwordController.text,
      "nationality": nationalityController.text
    });

    try {
      var dio = Dio();
      var response = await dio.request(
        'http://localhost:8000/api/register',
        options: Options(
          method: 'POST',
          headers: headers,
        ),
        data: data,
      );

      if (response.statusCode == 201) {
        // عند نجاح التسجيل
        emit(SignUpSuccess()); // تغيير الحالة إلى نجاح
        print(json.encode(response.data));
      } else {
        // عند حدوث خطأ
        emit(SignUpFailure(response.statusMessage ?? 'حدث خطأ غير متوقع'));
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content:
                Text('Error: ${response.statusMessage ?? "Unknown error"}'),
            backgroundColor: Colors.red,
            duration: const Duration(seconds: 3),
          ),
        );
        print(response.statusMessage);
      }
    } catch (e) {
      // خطأ في الاتصال بالخادم
      emit(SignUpFailure("رقم الهاتف أو البريد مستخدم مسبقا "));
    }
  }

  showuserprofile() async {
    var headers = {'Authorization': 'Bearer ${prefs.getString("token")}'};
    try {
      emit(UserLoading());
      print("Starting to fetch user profile...");
      print("Headers: $headers");

      var dio = Dio();
      var response = await dio.request(
        'http://localhost:8000/api/user-profile', // تحقق من عنوان الـ API
        options: Options(
          method: 'GET',
          headers: headers,
        ),
      );

      print("Response status: ${response.statusCode}");
      print("Response data: ${response.data}");

      if (response.statusCode == 200 || response.statusCode == 201) {
        // استخراج البيانات من response.data
        var data = response.data;
        var userData = data['user']; // بيانات المستخدم
        var methods = data['payment_methods']; // قائمة طرق الدفع

        print("Assigning data to controllers...");
        editfullNameController.text = userData['name'] ?? '';
        editemailController.text = userData['email'] ?? '';
        editphoneController.text = userData['phone_number'] ?? '';
        editbirthDateController.text = userData['birth_date'] ?? '';
        editnationalityController.text = userData['nationality'] ?? '';

        // تحديث قائمة طرق الدفع
        paymentMethods = methods
            .map<Map<String, dynamic>>(
                (e) => {'id': e['id'], 'method': e['method']})
            .toList();

        // تحديد الخيار الافتراضي
        selectedPaymentMethod =
            userData['default_payment_method_id']?.toString();

        emit(UserSuccess());
      } else {
        emit(UserFailure(response.statusMessage ?? "Unknown error occurred."));
      }
    } catch (e) {
      print("Error: $e");
      emit(UserFailure("Failed to fetch user profile: $e"));
    }
  }

  Future<void> edituserprofail() async {
    emit(UserLoading());

    try {
      var headers = {
        'Authorization': 'Bearer ${prefs.getString("token")}',
      };

      var data = json.encode({
        "name": editfullNameController.text,
        "phone_number": editphoneController.text,
        "email": editemailController.text,
        "birth_date": editbirthDateController.text,
        "password": editpasswordController.text,
        "nationality": editnationalityController.text,
        "default_payment_method_id": selectedPaymentMethod,
      });

      var dio = Dio();
      var response = await dio.request(
        'http://localhost:8000/api/user-profile',
        options: Options(
          method: 'POST',
          headers: headers,
        ),
        data: data,
      );

      if (response.statusCode == 201) {
        // إذا نجح الطلب
        emit(UserSuccess());

        print("Profile updated successfully: ${response.data}");
      } else {
        // إذا كان هناك خطأ في الطلب
        emit(UserFailure(response.statusMessage ?? 'Unknown error'));
        print("Failed to update profile: ${response.statusMessage}");
      }
    } catch (e) {
      // إذا حدث استثناء
      emit(UserFailure(e.toString()));
      print("Exception: $e");
    }
  }

  Future<void> pay() async {
    emit(PayLoading());
    var headers = {'Authorization': 'Bearer ${prefs.getString("token")}'};

    var dio = Dio();

    try {
      var response = await dio.request(
        'http://localhost:8000/api/pay',
        options: Options(
          method: 'GET',
          headers: headers,
        ),
      );

      // التعامل مع الأكواد المختلفة
      switch (response.statusCode) {
        case 200:
          String checkoutUrl = response.data['checkout_url'];
          emit(PaySuccess(checkoutUrl,
              paymentStatus: true, statusCode: 200)); // إضافة checkoutUrl
          break;
        case 401:
          emit(PayFailure('لقد انتهت فترة التقديم.'));
          break;
        case 402:
          emit(PayFailure(
              'لم يتم العثور على طريقة دفع افتراضية. يرجى إضافة طريقة دفع.'));
          break;
        default:
          emit(PayFailure(response.data['message'] ?? 'حدث خطأ أثناء الدفع.'));
          break;
      }
    } catch (e) {
      if (e is DioException) {
        String errorMessage =
            e.response?.data['message'] ?? 'حدث خطأ أثناء الاتصال بالخادم.';
        emit(PayFailure(errorMessage));
      } else {
        emit(PayFailure('حدث خطأ غير متوقع: $e'));
      }
    }
  }

  void requestpage() async {
    try {
      emit(requestpageLoading()); // عرض مؤشر التحميل
      var headers = {
        'Authorization': 'Bearer ${prefs.getString("token")}', // جلب التوكن
      };

      var dio = Dio();
      var response = await dio.request(
        'http://localhost:8000/api/requests',
        options: Options(
          method: 'GET',
          headers: headers,
        ),
      );
      if (response.statusCode == 202) {
        String message =
            response.data['message'] ?? "تم استرجاع الطلبات بنجاح.";
        List<dynamic> requests = response.data['requests'] ?? [];

        // ✅ حفظ الطلبات في SharedPreferences
        SharedPreferences prefs = await SharedPreferences.getInstance();
        await prefs.setString('cached_requests', jsonEncode(requests));

        emit(requestpageSuccess(requests: requests, message: message));
      } else if (response.statusCode == 201) {
        emit(requestpageFailure(
            errMessage: response.data['message'] ??
                'لا توجد طلبات مقدمة من هذا المستخدم'));
      } else if (response.statusCode == 500) {
        emit(requestpageFailure(
            errMessage: 'حدث خطأ في استرجاع الطلبات. نوع الخطأ: 500'));
      } else {
        emit(requestpageFailure(
            errMessage: 'خطأ غير معروف في استرجاع البيانات'));
      }
    } catch (e) {
      print('فشل الاتصال بالخادم: $e');

      // ✅ استرجاع الطلبات المحفوظة محليًا عند فشل الاتصال
      SharedPreferences prefs = await SharedPreferences.getInstance();
      String? cachedRequests = prefs.getString('cached_requests');

      if (cachedRequests != null) {
        try {
          List<dynamic> requests = jsonDecode(cachedRequests);
          emit(requestpageSuccess(
              requests: requests, message: 'تم عرض الطلبات المحفوظة مؤقتًا'));
        } catch (e) {
          emit(requestpageFailure(errMessage: 'حدث خطأ في البيانات المحفوظة'));
        }
      } else {
        emit(requestpageFailure(errMessage: 'لا توجد طلبات محفوظة'));
      }
    }
  }

  submitquestion() async {
    var headers = {
      'Authorization': 'Bearer ${prefs.getString("token")}' // جلب التوكن
    };
    var data = json.encode({"question": questionController.text});
    var dio = Dio();
    var response = await dio.request(
      'http://localhost:8000/api/submit-question',
      options: Options(
        method: 'POST',
        headers: headers,
      ),
      data: data,
    );

    if (response.statusCode == 201) {
      print(json.encode(response.data));
    } else {
      print(response.statusMessage);
    }
  }
}
