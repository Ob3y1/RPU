/******/ (() => { // webpackBootstrap
/*!******************************!*\
  !*** ./resources/js/myjs.js ***!
  \******************************/
if (localStorage.getItem("isSmall") === "yes") {
  sidebarId.classList.add("small-sidebar");
} else {
  sidebarId.classList.remove("small-sidebar");
}
var toggleSidebar = function toggleSidebar() {
  if (localStorage.getItem("isSmall") === "no") {
    localStorage.setItem("isSmall", "yes");
    sidebarId.classList.add("small-sidebar");
  } else {
    localStorage.setItem("isSmall", "yes");
  }
};
var toggleSidebar1 = function toggleSidebar1() {
  if (localStorage.getItem("isSmall") === "yes") {
    localStorage.setItem("isSmall", "no");
    sidebarId.classList.remove("small-sidebar");
  } else {
    localStorage.setItem("isSmall", "no");
  }
};
function validate(event) {
  var password = document.getElementById('inputPassword4').value;
  var confirmPassword = document.getElementById('confirm').value;
  if (password === confirmPassword) {
    return true;
  } else {
    alert('كلمة المرور غير متطابقة');
    event.preventDefault(); // يمنع إرسال النموذج في حالة عدم تطابق كلمة المرور
    return false;
  }
}
function togglePasswordVisibility() {
  var passwordInput = document.getElementById("inputPassword4");
  passwordInput.type = passwordInput.type === "password" ? "text" : "password";
}
document.getElementById('sign-out-button').addEventListener('click', function () {
  // حذف البيانات من localStorage
  localStorage.removeItem('users');
  localStorage.removeItem('selectedValue');

  // حذف البيانات من sessionStorage
  sessionStorage.clear();

  // إعادة توجيه المستخدم إلى صفحة تسجيل الدخول
  window.location.href = '/dashboard/login.html'; // افترض أن 'login.html' هو مسار صفحة تسجيل الدخول
});
/******/ })()
;