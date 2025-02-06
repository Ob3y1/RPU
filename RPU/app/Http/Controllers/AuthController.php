<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Payment_method;

class AuthController extends Controller
{

    //admin 
    //admin 
    //admin 
    //admin 
    //admin 
    //admin 
    //admin 
    //admin 
    //admin 
    //admin 
    //admin 

    public function showAdminLogin()
    {
        return view('login'); 
    }

    public function adminLogin(Request $request)
    {
        $validated = $request->validate([
            'identifier' => 'required',
            'password' => 'required|string',
        ]);
        $identifier = $request->identifier;

        // التحقق ما إذا كان الإدخال بريد إلكتروني أم رقم هاتف
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            // إذا كان بريدًا إلكترونيًا
            $user = User::where('email', $identifier)->first();
        } elseif (preg_match('/^\d+$/', $identifier)) {
            // إذا كان رقم هاتف
            if (substr($identifier, 0, 1) === '0') {
                $formattedNumber = '+963' . substr($identifier, 1);
            } else {
                $formattedNumber = $identifier;
            }
            $user = User::where('phone_number', $formattedNumber)->first();
        } else {
            return redirect()->back()->withErrors([
                'identifier' => 'Invalid identifier format. Must be a valid email or phone number.',
            ]);
        }
        // تحقق من وجود المستخدم وكلمة المرور
        if (!$user || !Hash::check($request->password, $user->password)) {
            return redirect()->back()->withErrors([
                'identifier' => 'Invalid credentials',
            ]);
        }

        // التحقق من أن المستخدم هو مسؤول (Admin)
        if ($user->role_id !== 1) { // role_id = 1 يشير إلى مسؤول
            return redirect()->back()->withErrors([
                'identifier' => 'Access denied. You do not have admin privileges.',
            ]);
        }

        // تسجيل الدخول باستخدام Auth
        Auth::login($user);

        // إعادة التوجيه إلى لوحة التحكم
        return redirect()->route('dashboard')->with('success', 'Login successful');
    }
    public function showAdminProfile()
    {
        $admin = Auth::user();
        $localNumber = '0' . substr($admin->phone_number, 4);
        return view('profile', compact('admin','localNumber'));
    }

    public function updateAdminProfile(Request $request)
    {
        $admin = Auth::user();
        $id=Auth::user()->id;
        $phoneNumber = $request->input('phone_number');
        if (substr($phoneNumber, 0, 1) === '0') {
            $formattedNumber = '+963' . substr($phoneNumber, 1);
        } else {
            $formattedNumber = $phoneNumber;
        }
        $request->merge(['phone_number' => $formattedNumber]);

        $request->validate([
            'name' => 'nullable|string|max:20',
            'phone_number' => 'nullable|string|unique:users,phone_number,' . $id, 
            'email' => 'nullable|email|unique:users,email,' . $id, 
            'birth_date' => 'nullable|date',
            'password' => 'nullable|string', 
            'nationality' => 'nullable|string|max:50'
        ]);

        if ($request->has('name')) {
            $admin->name = $request->input('name');
        }
        if ($request->has('email')) {
            $admin->email = $request->input('email');
        }
        if ($request->has('password') && !empty($request->input('password'))) {
            $admin->password = Hash::make($request->input('password'));
        }
        if ($request->has('phone_number')) {
            $identifier=$request->input('phone_number');
            if (substr($identifier, 0, 1) === '0') {
                $formattedNumber = '+963' . substr($identifier, 1);
            } else {
                $formattedNumber = $identifier;
            }
            $admin->phone_number = $formattedNumber;
        }
        if ($request->has('birth_date')) {
            $admin->birth_date = $request->input('birth_date');
        }
        if ($request->has('nationality')) {
            $admin->nationality = $request->input('nationality');
        }

        $admin->save();

        return back()->with('success', 'Profile updated successfully');
    }
    public function adminLogout()
    {
        Auth::logout(); // تسجيل الخروج
        return redirect()->route('loginForm')->with('success', 'Logged out successfully');
    }
    //user   
    //user
    //user
    //user
    //user
    //user
    //user
    //user
    //user
    //user
    //user

    public function createAccount(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:20',
                'phone_number' => 'required|string|unique:users,phone_number',
                'email' => 'required|email|unique:users,email',
                'birth_date' => 'required|date',
                'password' => 'required|string',
                'nationality' => 'nullable|string|max:50',
            ]);
    
            $phone_number = $request->phone_number;
            $phone_number = preg_replace('/\D/', '', $phone_number);
            if (substr($phone_number, 0, 1) === '0') {
                $phone_number = '+963' . substr($phone_number, 1);
            } else {
                $phone_number = '+963' . substr($phone_number, 4);
            }
    
            $user = new User();
            $user->name = $validated['name'];
            $user->phone_number = $phone_number;
            $user->email = $validated['email'];
            $user->birth_date = $validated['birth_date'];
            $user->password = Hash::make($validated['password']);
            $user->nationality = $validated['nationality'];
            $user->role_id = 3;
            $user->default_payment_method_id = 1;
            $user->save();
    
            return response()->json([
                'message' => 'Account created successfully!',
                'user' => $user,
            ], 201);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(), // Returns an array of errors for each field
            ], 422); // 422 Unprocessable Entity
        }
    }
    
    public function login(Request $request)
    {
        try{
            $validated = $request->validate([
            'identifier' => 'required', 
            'password' => 'required',
        ]);

        $identifier = $validated['identifier'];
        if (preg_match('/^\d+$/', $identifier)) { 
            if (substr($identifier, 0, 1) === '0') {
                $formattedNumber = '+963' . substr($identifier, 1);
            } else {
                $formattedNumber = $identifier;
            }
            $identifier = $formattedNumber;
        }

        $user = User::where('email', $identifier)
                    ->orWhere('phone_number', $identifier)
                    ->where('role_id',3)
                    ->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401); // Unauthorized
        }

        $token = $user->createToken('auth_token', ['user-abilities'])->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
        ], 201); // OK
        }catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(), // Returns an array of errors for each field
            ], 422); // 422 Unprocessable Entity
        }
    }

    public function logout(Request $request)
    {    
        $request->user()->tokens()->delete();
        
        return response()->json([
            'message' => 'Logged out successfully',
        ], 201); // OK
    }
  
    public function userProfile(Request $request)
    {
        $payment_methods = Payment_method::select('id', 'method')->get();
        return response()->json([
            'message' => 'User profile fetched successfully',
            'user' => $request->user(),
            'default_payment_method'=>$request->user()->payment_method,
            'payment_methods'=> $payment_methods
        ], 201); // OK
    }
    public function updateAccount(Request $request)
    {
        try{
            $id=$request->user()->id;
            $request->validate([
                'name' => 'nullable|string|max:20',
                'phone_number' => 'nullable|string|unique:users,phone_number,' . $id, 
                'email' => 'nullable|email|unique:users,email,' . $id, 
                'birth_date' => 'nullable|date',
                'password' => 'nullable|string', 
                'nationality' => 'nullable|string|max:50',
                'default_payment_method_id' =>'nullable|string'
            ]);
            $user = User::findOrFail($id);
            $phone_number = $request->phone_number;
            $phone_number = preg_replace('/\D/', '', $phone_number);
            if (substr($phone_number, 0, 1) === '0') {
                $phone_number = '+963' . substr($phone_number, 1);
            } else {
                $phone_number = '+963' . substr($phone_number, 4);
            }

            if ($request->filled('name')) {
                $user->name = $request->name;
            }
            if ($request->filled('phone_number')) {
                $user->phone_number = $phone_number; 
            }
            if ($request->filled('email')) {
                $user->email = $request->email;
            }
            if ($request->filled('birth_date')) {
                $user->birth_date = $request->birth_date;
            }
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password); 
            }
            if ($request->filled('nationality')) {
                $user->nationality = $request->nationality;
            }
            if ($request->filled('default_payment_method_id')) {
                $user->default_payment_method_id = $request->default_payment_method_id;
            }

            $user->save();

            return response()->json([
                'message' => 'Account updated successfully!',
                'user' => $user,
            ], 201);
        }catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(), // Returns an array of errors for each field
            ], 422); // 422 Unprocessable Entity
        }
    }



    //employee
    //employee
    //employee
    //employee
    //employee
    //employee
    //employee
    //employee
    //employee
    //employee
    //employee
    //employee

    public function employeeLogin(Request $request)
    {
        try{
            $validated = $request->validate([
                'identifier' => 'required', // بريد إلكتروني أو رقم هاتف
                'password' => 'required',
            ]);

            $identifier = $validated['identifier'];
            if (preg_match('/^\d+$/', $identifier)) { 
                if (substr($identifier, 0, 1) === '0') {
                    $formattedNumber = '+963' . substr($identifier, 1);
                } else {
                    $formattedNumber = $identifier;
                }
                $identifier = $formattedNumber;
            }

            // البحث عن الموظف
            $employee = User::where('email', $identifier)
                            ->orWhere('phone_number', $identifier)
                            ->where('role_id',2)
                            ->first();

            if (!$employee || !Hash::check($validated['password'], $employee->password)) {
                return response()->json([
                    'message' => 'Invalid credentials',
                ], 401); // Unauthorized
            }

            $token = $employee->createToken('employee_auth_token', ['employee-abilities'])->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'employee' => $employee,
                'token' => $token,
            ], 201);
        }catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(), // Returns an array of errors for each field
            ], 422); // 422 Unprocessable Entity
        }
    }
    public function employeeLogout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout successful',
        ], 201); 

    }
}