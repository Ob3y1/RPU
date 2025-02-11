import 'dart:io';
import 'dart:typed_data';

import 'package:dio/dio.dart';
import 'package:flutter/foundation.dart'; // للاستفادة من kIsWeb
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:image_picker/image_picker.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:university/Home/MyRequestsPage.dart';
import 'package:university/cubit/user_cubit.dart';
import 'package:university/cubit/user_state.dart';
import 'package:url_launcher/url_launcher.dart';

class RequestSpecialtyPage extends StatefulWidget {
  final String specialtyName;
  final String universityName;
  final int typeId;

  const RequestSpecialtyPage({
    super.key,
    required this.specialtyName,
    required this.universityName,
    required this.typeId,
  });

  @override
  _RequestSpecialtyPageState createState() => _RequestSpecialtyPageState();
}

class _RequestSpecialtyPageState extends State<RequestSpecialtyPage> {
  final _formKey = GlobalKey<FormState>();
  String? selectedCountry;

  // متغيرات لاختيار الصور
  final ImagePicker _picker = ImagePicker();
  XFile? _certificateImage;
  XFile? _identityImage;

  bool get isValid => _formKey.currentState!.validate();

  // دالة اختيار صورة الشهادة من المعرض
  Future<void> _pickCertificateImage() async {
    final XFile? pickedFile =
        await _picker.pickImage(source: ImageSource.gallery);
    if (pickedFile != null) {
      setState(() {
        _certificateImage = pickedFile;
      });
    }
  }

  // دالة اختيار صورة الهوية من المعرض
  Future<void> _pickIdentityImage() async {
    final XFile? pickedFile =
        await _picker.pickImage(source: ImageSource.gallery);
    if (pickedFile != null) {
      setState(() {
        _identityImage = pickedFile;
      });
    }
  }

  // تابع إرسال الطلب إلى السيرفر مع تضمين الصور (إن وُجدت)
  Future<void> _sendRequestToServer({
    required String specialtyName,
    required String universityName,
    required int typeId,
    required String country,
    required String totalScore,
  }) async {
    final dio = Dio();
    try {
      final SharedPreferences prefs = await SharedPreferences.getInstance();
      String? token = prefs.getString("token");
      if (token == null || token.isEmpty) {
        print('⚠️ خطأ: لم يتم العثور على التوكن');
        _showSnackBar('حدث خطأ: لم يتم العثور على التوكن');
        return;
      }
      var headers = {'Authorization': 'Bearer $token'};

      // تجهيز بيانات النموذج مع الصور (إن وُجدت)
      var data = FormData.fromMap({
        'university': universityName,
        'specialization': specialtyName,
        'r_type_id': typeId.toString(),
        'certificate_country': country,
        'total': totalScore,
      });

      print("📤 إرسال البيانات...");
      var response = await dio.post(
        'http://localhost:8000/api/requests',
        options: Options(headers: headers),
        data: data,
      );

      // التعامل مع الاستجابة بناءً على الأكواد المختلفة
      switch (response.statusCode) {
        case 201:
          print('✅ تم إرسال الطلب بنجاح');
          print(response.data);
          _showSnackBar('تم إرسال الطلب بنجاح');
          Navigator.pushAndRemoveUntil(
            context,
            MaterialPageRoute(
              builder: (context) => MyRequestsPage(
                requestData: {
                  'specialtyName': specialtyName,
                  'universityName': universityName,
                  'certificateCountry': country,
                  'totalScore': totalScore,
                },
              ),
            ),
            (route) => false,
          );
          break;
        case 405:
          print('❌ خطأ: ${response.statusCode}');
          print('📌 تفاصيل الخطأ: ${response.data}');
          _showSnackBar(response.data['error'] ??
              'لقد قدمت طلبًا بالفعل لهذا التخصص والجامعة.');
          break;
        case 401:
          print('❌ لم يعد بإمكانك تقديم الطلب، انتهت فترة التقديم');
          _showSnackBar('لقد انتهت فترة التقديم.');
          break;
        case 402:
          print('❌ لم يتم العثور على عملية دفع. يرجى إتمام الدفع أولاً.');
          _showSnackBar(
              'No available payment found. Please complete a payment before submitting a request.');
          break;
        case 404:
          print('❌ التخصص غير متاح في هذه الجامعة كمنحة.');
          _showSnackBar(
              'The specified specialization is not available at this university as grant.');
          break;
        case 406:
          print('❌ التخصص غير متاح في هذه الجامعة.');
          _showSnackBar(
              'The specified specialization is not available at this university.');
          break;
        case 422:
          print('❌ خطأ في التحقق من البيانات.');
          _showSnackBar('Validation error. Please try again.');
          break;
        default:
          print('❌ حدث خطأ غير متوقع: ${response.statusMessage}');
          _showSnackBar('Unexpected error: ${response.statusMessage}');
          break;
      }
    } catch (e) {
      if (e is DioException) {
        print('❌ خطأ DioException: ${e.response?.statusCode}');
        print('📌 تفاصيل الخطأ: ${e.response?.data}');
        _showSnackBar('Error connecting to server.');
      } else {
        print('❌ خطأ أثناء إرسال البيانات: $e');
        _showSnackBar('Error sending data. Please try again later.');
      }
    }
  }

  // تابع عرض الإشعارات
  void _showSnackBar(String message) {
    ScaffoldMessenger.of(context)
        .showSnackBar(SnackBar(content: Text(message)));
  }

  // تابع فتح الرابط
  Future<void> _launchURL(String url) async {
    if (await canLaunch(url)) {
      await launch(url);
    } else {
      throw 'تعذر فتح الرابط: $url';
    }
  }

  // تابع لبناء زر عام
  Widget _buildButton({
    required String label,
    required VoidCallback onPressed,
    Color? color,
  }) {
    return Center(
      child: ElevatedButton(
        style: ElevatedButton.styleFrom(
          backgroundColor: color ?? const Color(0xFF00BF6D),
          minimumSize: const Size(200, 50),
        ),
        onPressed: onPressed,
        child: Text(label,
            style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
      ),
    );
  }

  // بناء حقول الإدخال الخاصة بالمجموع وبلد الشهادة
  Widget _buildFormFields() {
    return Row(
      children: [
        Expanded(
          child: TextFormField(
            controller: context.read<UserCubit>().scoreController,
            decoration: const InputDecoration(
              border: OutlineInputBorder(
                borderRadius: BorderRadius.all(Radius.circular(50)),
              ),
              labelText: 'مجموع الطالب',
              hintText: 'بالأرقام',
              prefixIcon: Icon(Icons.score),
            ),
            keyboardType: TextInputType.number,
            validator: (value) => value == null || value.isEmpty
                ? 'يرجى إدخال مجموع الطالب'
                : null,
          ),
        ),
        const SizedBox(width: 16),
        Expanded(
          child: DropdownButtonFormField<String>(
            decoration: const InputDecoration(
              border: OutlineInputBorder(
                borderRadius: BorderRadius.all(Radius.circular(50)),
              ),
              labelText: 'بلد الشهادة',
              prefixIcon: Icon(Icons.flag),
            ),
            items: const [
              DropdownMenuItem(value: 'Syria', child: Text('Syria')),
              DropdownMenuItem(value: 'Other', child: Text('Other')),
            ],
            onChanged: (value) => setState(() => selectedCountry = value),
            validator: (value) =>
                value == null ? 'يرجى اختيار بلد الشهادة' : null,
          ),
        ),
      ],
    );
  }

  // بناء حقل اختيار الصورة مع عرض معاينة للصورة المختارة مع دعم Flutter Web
  Widget _buildImagePickerField({
    required String label,
    required XFile? image,
    required VoidCallback onTap,
  }) {
    return InkWell(
      onTap: onTap,
      child: Container(
        height: 150,
        width: double.infinity,
        decoration: BoxDecoration(
          border: Border.all(color: Colors.grey),
          borderRadius: BorderRadius.circular(8),
        ),
        child: image != null
            ? kIsWeb
                ? FutureBuilder<Uint8List>(
                    future: image.readAsBytes(),
                    builder: (context, snapshot) {
                      if (snapshot.hasData) {
                        return Image.memory(
                          snapshot.data!,
                          fit: BoxFit.cover,
                        );
                      } else {
                        return const Center(
                          child: CircularProgressIndicator(),
                        );
                      }
                    },
                  )
                : Image.file(
                    File(image.path),
                    fit: BoxFit.cover,
                  )
            : Center(
                child: Text(
                  label,
                  style: const TextStyle(fontSize: 16, color: Colors.grey),
                ),
              ),
      ),
    );
  }

  // تابع لإظهار إشعار الخطأ
  void _showErrorSnackBar(String message) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(message),
        duration: const Duration(seconds: 3),
      ),
    );
  }

  // بناء أزرار الـ Bloc الخاصة بالدفع
  Widget _buildBlocButtons() {
    return BlocBuilder<UserCubit, UserState>(builder: (context, state) {
      if (state is PayLoading) {
        return const Center(child: CircularProgressIndicator());
      }
      if (state is PaySuccess) {
        // عند نجاح الدفع، يتم فتح رابط الدفع مباشرةً
        WidgetsBinding.instance.addPostFrameCallback((_) {
          _launchURL(state.checkoutUrl);
        });
        return Column(
          children: [
            const SizedBox(height: 10),
            TextButton(
              onPressed: () => _launchURL(state.checkoutUrl),
              child: const Text('افتح رابط الدفع'),
            ),
          ],
        );
      }
      if (state is PayFailure) {
        WidgetsBinding.instance.addPostFrameCallback((_) {
          _showErrorSnackBar(state.message);
        });
      }
      return _buildButton(
        label: 'الدفع',
        onPressed: () {
          if (isValid) {
            context.read<UserCubit>().pay();
          } else {
            _showErrorSnackBar('يرجى تعبئة جميع الحقول');
          }
        },
      );
    });
  }

  // زر إرسال الطلب
  Widget _buildSendRequestButton() {
    return _buildButton(
      label: 'إرسال الطلب',
      onPressed: () async {
        print('تم الضغط على زر إرسال الطلب');
        if (isValid) {
          String specialtyName = widget.specialtyName;
          String universityName = widget.universityName;
          String country = selectedCountry ?? '';
          String totalScore = context.read<UserCubit>().scoreController.text;
          print(
              'بيانات الطلب: $specialtyName, $universityName, $country, $totalScore');

          var userState = context.read<UserCubit>().state;
          if (userState is PaySuccess && userState.statusCode == 200) {
            print('✅ الدفع تم بنجاح');
            _showSnackBar('تم الدفع بنجاح');
            // استدعاء تابع إرسال الطلب
            await _sendRequestToServer(
              specialtyName: specialtyName,
              universityName: universityName,
              country: country,
              totalScore: totalScore,
              typeId: widget.typeId,
            );
            // الانتقال إلى صفحة MyRequestsPage بعد إرسال الطلب
            Navigator.pushAndRemoveUntil(
              context,
              MaterialPageRoute(
                builder: (context) => MyRequestsPage(
                  requestData: {
                    'specialtyName': specialtyName,
                    'universityName': universityName,
                    'certificateCountry': country,
                    'totalScore': totalScore,
                  },
                ),
              ),
              (route) => false,
            );
          } else if (userState is PayFailure) {
            print('❌ لم يتم الدفع بنجاح');
            _showSnackBar(
                'لم يتم الدفع بنجاح. يرجى التأكد من أنك أكملت عملية الدفع.');
          } else {
            print('❌ لم تبدأ عملية الدفع أو الاستجابة كانت غير 200');
            _showSnackBar('يرجى إتمام عملية الدفع أولاً');
          }
        } else {
          _showSnackBar('يرجى تعبئة جميع الحقول');
        }
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Center(
          child: Text(
            'طلب تخصص ${widget.specialtyName} - ${widget.universityName}',
            style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
          ),
        ),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: ListView(
            children: [
              const SizedBox(height: 20),
              const Center(
                child: Text(
                  ':املأ البيانات المطلوبة',
                  style: TextStyle(fontSize: 18.0, fontWeight: FontWeight.bold),
                ),
              ),
              const SizedBox(height: 20),
              _buildFormFields(),
              const SizedBox(height: 24),
              // حقل اختيار صورة الشهادة مع معاينة الصورة
              _buildImagePickerField(
                label: 'اختر صورة الشهادة',
                image: _certificateImage,
                onTap: _pickCertificateImage,
              ),
              const SizedBox(height: 16),
              // حقل اختيار صورة الهوية مع معاينة الصورة
              _buildImagePickerField(
                label: 'اختر صورة الهوية',
                image: _identityImage,
                onTap: _pickIdentityImage,
              ),
              const SizedBox(height: 24),
              _buildBlocButtons(),
              const SizedBox(height: 24),
              _buildSendRequestButton(),
            ],
          ),
        ),
      ),
    );
  }
}
