@extends('layouts.admin')

@section('title')
    Profile Page
@endsection

@section('content')
                <nav   nav class="navbar bg-body-tertiary">
                    <div class="container-fluid  justify-content-md-between">
                        <a class="navbar-brand fw-normal fs-4 mb-1 mb-md-0 d-none d-sm-block ">Edit Your Information</a>
                    </div>
                </nav>

                <form class="row g-3 mx-auto  my-2 was-validated" style="width: 98%;"  dir="rtl" method="POST" action="{{ route('updateProfile')}}">
                    @csrf
                    @method('PUT')
                    <div class="col-md-6 ">
                    <label for="name" class="form-label">الاسم </label>
                    <input type="text" aria-label="name" pattern="[أ-ي]{3,15}" class="form-control " required
                            id="name" name="name" value="{{$admin->name}}">
                            
                        <div class="invalid-feedback">
                            رجاءً ادخل الاسم الكامل
                        </div>
                    </div>
                    <div class="col-md-6 ">
                        <label for="inputPassword4" class="form-label">كلمة السر</label>
                        <input type="password" class="form-control" id="inputPassword4" maxlength="8"
                            pattern="(?=.*\d)(?=.*[a-z]).{4,8}" name="password"
                            title="Must contain at least one  number and lowercase letter, and between 4 - 8 characters">
                            <input type="checkbox" class="form-check-input" onclick="togglePasswordVisibility()" /> إظهار كلمة المرور

                        <div class="invalid-feedback">
                            يجب أن يحتوي على رقم واحد وحرف صغير على الأقل، وبين 4 - 8 أحرف
                        </div>
                    </div>
                    <div class="col-md-6 ">
                        <label for="inputnum" class="form-label">رقم الهاتف</label>
                        <div class="input-group" dir="ltr">
                            <span class="input-group-text">+963</span>
                            <input type="tel" placeholder="0987654321" maxlength="10" pattern="09+[0-9]{8}"
                                class="form-control rounded-end-2" required id="inputnum" name="phone_number" value="{{$localNumber}}">
                            <div class="invalid-feedback" dir="rtl">
                                رجاء ادخل رقم صحيح
                            </div>
                        </div>
                        @if ($errors->has('phone_number'))
                        <p class="text-danger d-block">{{ $errors->first('phone_number') }}</p>
                        @endif
                    </div>
                    <div class="col-md-6 ">
                        <label for="email" class="form-label">email </label>
                        <input type="email" aria-label="email" class="form-control rounded-end-2" required
                                id="email" name="email" value="{{ old('email', $admin->email) }}">
                            <div class="invalid-feedback">
                                رجاءً ادخل بريد إلكتروني صالح
                            </div>
                            @if ($errors->has('email'))
                            <p class="text-danger d-block">{{ $errors->first('email') }}</p>
                            @endif
                    </div>

                    <div class="col-md-3">
                        <label for="inputdate4" class="form-label">تاريخ الميلاد</label>
                        <input type="date" required class="form-control" id="inputdate4" value="{{$admin->birth_date}}"
                            min="1970-01-01" name="birth_date">

                        <div class="invalid-feedback">
                            رجاءً ادخل تارخ الميلاد الصحيح
                        </div>
                    </div>
                    <div class="col-6">
                    <label for="nationality" class="form-label">البلد </label>
                    <select class="form-select" name="nationality" id="nationality" required>
                            <option value="Syria" {{ $admin->nationality == 'Syria' ? 'selected' : '' }}>Syria</option>
                            <option value="Other" {{ $admin->nationality == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        <div class="invalid-feedback">
                            رجاءً اختر البلد
                        </div>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-start  ">
                        <button type="submit" class="btn btn-primary  me-md-2">Submit</button>
                        <button type="reset" class="btn btn-primary ">Reset</button>
                    </div>
                    
                </form>
@endsection
