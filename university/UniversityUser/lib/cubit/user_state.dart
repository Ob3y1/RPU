class UserState {}

final class UserInitial extends UserState {}

final class SignInSuccess extends UserState {}

final class SignInLoading extends UserState {}

final class SignInFailure extends UserState {
  final String errMessage;
  SignInFailure({required this.errMessage});
}

final class SignUpLoading extends UserState {}

final class SignUpSuccess extends UserState {}

final class SignUpFailure extends UserState {
  final String errMessage;

  SignUpFailure(this.errMessage); // تمرير قيمة errMessage بشكل مباشر
}

final class UserLoading extends UserState {}

final class UserSuccess extends UserState {}

final class UserFailure extends UserState {
  final String errMessage;

  UserFailure(this.errMessage); // تمرير قيمة errMessage بشكل مباشر
}

final class EditUserLoading extends UserState {}

final class EditUserSuccess extends UserState {}

final class EditUserFailure extends UserState {
  final String errMessage;

  EditUserFailure(this.errMessage); // تمرير قيمة errMessage بشكل مباشر
}
class PaySuccess extends UserState {
  final String checkoutUrl;
  final bool paymentStatus;
  final int? statusCode; // إضافة statusCode مع القيم الافتراضية

  PaySuccess(this.checkoutUrl, {required this.paymentStatus, this.statusCode});
}

class PayFailure extends UserState {
  final String message;

  PayFailure(this.message);
}

class PayLoading extends UserState {}




class requestpageLoading extends UserState {}

class requestpageSuccess extends UserState {
  final List<dynamic> requests;
  final String message;

  requestpageSuccess({required this.requests, required this.message});
}

class requestpageFailure extends UserState {
  final String errMessage;

  requestpageFailure({required this.errMessage});
}
