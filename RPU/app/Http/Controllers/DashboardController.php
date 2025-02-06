<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sitting;
use App\Models\University;
use App\Models\Specialization;
use App\Models\RequestModel; 
use App\Models\Payment;
use App\Models\Grant;
use App\Models\Specializations_Per_University;
use App\Models\User;
use App\Models\Payment_method;
use App\Models\SupportQuestion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $emp = User::whereHas('role', function ($query) {
            $query->where('role', 'Support Agent');
        })->first();
        $localNumber = '0' . substr($emp->phone_number, 4);

        $startdate = Sitting::where('key', 'start date')->value('value');
        $enddate = Sitting::where('key', 'end date')->value('value');

        $usersCount = User::count(); // عدد المستخدمين
        $pendingRequests = RequestModel::where('request_status', 'pending')->count(); // عدد الطلبات بحالة الانتظار
        $acceptedRequests = RequestModel::where('request_status', 'Accepted')->count(); // عدد الطلبات المقبولة
        $rejectedRequests = RequestModel::where('request_status', 'rejected')->count(); // عدد الطلبات المرفوضة
        $totalPayments = Payment::where('payment_status', 'completed')->sum('amount'); // مجموع المدفوعات
        $universitiesCount = University::count(); // عدد الجامعات
        $specializationsCount = Specializations_Per_University::distinct('specialization_id')->count(); // عدد الاختصاصات المفتوحة
        // أعلى 8 طلبات بالمجموع مع بيانات المستخدم والجامعة والتخصص
        $topRequests = RequestModel::with([
            'payment.user:id,name', // تحميل اسم المستخدم
            'r_type:id,type',
            'specializationPerUniversity.university:id,name', // تحميل اسم الجامعة
            'specializationPerUniversity.specialization:id,name' // تحميل اسم التخصص
        ])
        ->orderByDesc('total')
        ->take(10)
        ->get();

        // أحدث 8 طلبات بنفس التفاصيل
        $latestRequests = RequestModel::with([
            'payment.user:id,name',
            'r_type:id,type',
            'specializationPerUniversity.university:id,name',
            'specializationPerUniversity.specialization:id,name'
        ])
        ->orderByDesc('created_at')
        ->take(8)
        ->get();

        $requestsData = RequestModel::selectRaw('
                specializations__per__universities.university_id,
                universities.name as university_name,
                specializations__per__universities.specialization_id,
                specializations.name as specialization_name,
                COUNT(requests.id) as total_requests
            ')
            ->join('specializations__per__universities', 'requests.unis_id', '=', 'specializations__per__universities.id')
            ->join('universities', 'specializations__per__universities.university_id', '=', 'universities.id')
            ->join('specializations', 'specializations__per__universities.specialization_id', '=', 'specializations.id')
            ->groupBy(
                'specializations__per__universities.university_id', 
                'universities.name', 
                'specializations__per__universities.specialization_id', 
                'specializations.name'
            )
            ->orderBy('universities.name')
            ->get();




        $start = Sitting::where('key', 'start date')->first();
        $end = Sitting::where('key', 'end date')->first();
    
        if (!$start || !$end) {
            return view('calculate')->with('result', null); // إرجاع صفحة مع نتيجة فارغة
        }
    
        $startDate = Carbon::parse($start->value);
        $endDate = Carbon::parse($end->value);
        $currentDate = Carbon::now();
    
        // التحقق مما إذا كان التاريخ الحالي بعد تاريخ النهاية
        $result = $currentDate->greaterThan($endDate) ? 1 : 0;




        return view('dashboard', compact('requestsData','result',
        'emp','localNumber','startdate','enddate','usersCount','pendingRequests','acceptedRequests',
        'rejectedRequests','totalPayments','universitiesCount','specializationsCount','topRequests','latestRequests',)); 
    }

    public function index1()
    {
        $requestFee = Sitting::where('key', 'request_fee')->value('value');
        $totalPayments = Payment::where('payment_status', 'completed')->sum('amount'); // مجموع المدفوعات
        $totalPayments1 = Payment::count();
        $successfulPayments = Payment::where('payment_status', 'completed')->count();;
        $pendingPayments = Payment::where('payment_status', 'pending')->count();
        $failedPayments = Payment::where('payment_status', 'failed')->count();
        $usedPayments = Payment::where('is_used', true)->count();
    
        // 🔹 **جلب بيانات الدفع حسب التاريخ**
        $paymentsByDate = Payment::select(
            DB::raw('DATE(payment_date) as date'),
            DB::raw('SUM(amount) as total')
        )
        ->where('payment_status', 'completed') // فقط المدفوعات الناجحة
        ->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get();
        $dates = $paymentsByDate->pluck('date');
        $totals = $paymentsByDate->pluck('total');
        $paymentMethods = Payment_method::withCount('payment')->get();
        $labels = $paymentMethods->pluck('method');
        $data = $paymentMethods->pluck('payment_count');
        return view('dashmony', compact('requestFee','labels','data','totalPayments1', 'successfulPayments', 'pendingPayments', 'failedPayments', 'usedPayments',
        'dates', 'totals','totalPayments')); 
    }


    public function fee(Request $request)
    {
        $requestFee = Sitting::where('key', 'request_fee')->first();
        $requestFee->value=$request->input('fee');
        $requestFee->save();
        return back()->with('success', 'Fee updated successfully');
    }
    public function date(Request $request)
    {
        $startdate = Sitting::where('key', 'start date')->first();
        $enddate = Sitting::where('key', 'end date')->first();
        if ($request->has('startdate')) {
            $startdate->value=$request->input('startdate');
            $startdate->save();
        }
        if ($request->has('enddate')) {
            $enddate->value=$request->input('enddate');
            $enddate->save();
        }
        return back()->with('success', 'Dates updated successfully');
    }

    
    public function empinf(Request $request)
    {
        $admin = User::whereHas('role', function ($query) {
            $query->where('role', 'Support Agent');
        })->first();
        $phoneNumber = $request->input('phone_number');
        if (substr($phoneNumber, 0, 1) === '0') {
            $formattedNumber = '+963' . substr($phoneNumber, 1);
        } else {
            $formattedNumber = $phoneNumber;
        }
        $request->merge(['phone_number' => $formattedNumber]);

        $request->validate([
            'name' => 'nullable|string|max:20',
            'phone_number' => 'nullable|string|unique:users,phone_number,' . $admin->id, 
            'email' => 'nullable|email|unique:users,email,' . $admin->id, 
            'birth_date' => 'nullable|date',
            'password' => 'nullable|string', 
            'nationality' => 'nullable|string|max:50'
        ]);
        if (!$admin) {
            $validated = $request->validate([
                'name' => 'required|string|max:20',
                'phone_number' => 'required|string|unique:users,phone_number',
                'email' => 'required|email|unique:users,email',
                'birth_date' => 'required|date',
                'password' => 'required|string',
            ]);
            $user = new User();
            $user->name = $validated['name'];
            $user->phone_number = $validated['phone_number'];
            $user->email = $validated['email'];
            $user->birth_date = $validated['birth_date'];
            $user->password = Hash::make($validated['password']);
            $user->role_id = 2;
            $user->default_payment_method_id = 1;
            $user->save();
            return back()->with('success', 'Profile created successfully');

        }
        else{
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

        $admin->save();
    
        return back()->with('success', 'Profile updated successfully');
    }
    }
 



    public function index2()
    {
        $emp = User::whereHas('role', function ($query) {
            $query->where('role', 'Support Agent');
        })->first();
        $localNumber = '0' . substr($emp->phone_number, 4);
        

        $faqQuestions = SupportQuestion::where('is_frequent', true)
        ->where('status', 'answered')
        ->with('user:id,name') // جلب اسم المستخدم فقط لتوفير الأداء
        ->latest()
        ->take(10)
        ->get();


        $statusCounts = SupportQuestion::groupBy('status')
        ->selectRaw('status, count(*) as count')
        ->pluck('count', 'status');

        $answered = $statusCounts['answered'] ?? 0;
        $unanswered = ($statusCounts['pending'] ?? 0) + ($statusCounts['rejected'] ?? 0);
        $total = $answered + $unanswered;

        // إحصائيات الإجابات اليومية
        $dailyAnswers = SupportQuestion::where('status', 'answered')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->selectRaw('DATE(created_at) as date, count(*) as count')
            ->orderBy('date')
            ->get();


        return view('dashemp', compact('emp','localNumber','faqQuestions','answered', 'unanswered', 'total','dailyAnswers')); // تمرير النتيجة إلى الواجهة
    }
    


    public function rabat()
    {
        $universities = University::all();
        $specialization = Specialization::all();

        return view('rabat', compact('universities','specialization'));        
    }
    public function adduni(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string',
            'details' => 'nullable|string'
        ]);
        University::create($request->all());
        return back()->with('success', 'University created successfully');
    }
    public function addspe(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'details' => 'nullable|string'
        ]);
        Specialization::create($request->all());
        return back()->with('success', 'University created successfully');
    }
 
    public function addinf(Request $request)
    {
        // التحقق من صحة البيانات
        $validated = $request->validate([
            'university_id' => 'required|exists:universities,id',
            'specialization_id' => 'required|exists:specializations,id',
            'price_per_hour' => 'required|numeric|min:0',
            'num_seats' => 'required|integer|min:1',
            'grant' => 'required|boolean',
            'grant_seats' => 'nullable|integer|min:0'
        ]);

        // حفظ بيانات الاختصاص للجامعة
        $ss=Specializations_Per_University::create([
            'university_id' => $validated['university_id'],
            'specialization_id' => $validated['specialization_id'],
            'price_per_hour' => $validated['price_per_hour'],
            'num_seats' => $validated['num_seats'],
        ]);
        if($validated['grant']==1)
        {
            Grant::create([
                'unis_id' => $ss->id,
                'num_seats' => $validated['grant_seats'],
            ]);
        }
        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('infouni', ['university' => $validated['university_id']])
                        ->with('success', 'Specialization added successfully.');
    }


    
    public function edituni($id)
    {
        $university = University::findOrFail($id);
        return view('edituni', compact('university'));
    } 
    public function editspe($id)
    {
        $specialization = Specialization::find($id);
        return view('editspe', compact('specialization'));
    }
    public function editinf($id)
    {
        $specializationInfo = Specializations_Per_University::with('specialization', 'grant')
        ->where('id', $id)
        ->firstOrFail();

        // استرجاع جميع الاختصاصات المتاحة
        $allSpecializations = Specialization::all();
        return view('editinf', compact('specializationInfo','allSpecializations'));
    }
    public function updateuni(Request $request, $id)
    {
        $university = University::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'details' => 'nullable|string',
        ]);

        $university->update($validatedData);
        return redirect('admin/rabat')->with('success', 'University Updated successfully');

    }

    public function updatespe(Request $request,$id)
    {
        $specialization = Specialization::find($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'details' => 'nullable|string'
        ]);
            $specialization->update($validated);
            return redirect('admin/rabat')->with('success', 'Specialization Updated successfully');
    }
    public function updateinf(Request $request,$id)
    {
        $specialization = Specializations_Per_University::with('grant')->findOrFail($id);

        // تحديث بيانات الاختصاص
        $specialization->update([
            'specialization_id' => $request->specialization_id,
            'price_per_hour' => $request->price_per_hour,
            'num_seats' => $request->num_seats,
        ]);
    
        // إذا كان هناك منحة، نقوم بتحديثها أو إضافتها
        if ($request->grant_seats > 0) {
            $specialization->grant()->updateOrCreate(
                ['unis_id' => $specialization->id],
                ['num_seats' => $request->grant_seats]
            );
        } else {
            // إذا لم تكن هناك منحة نلغيها إن كانت موجودة
            $specialization->grant()->delete();
        }
    
        return redirect()->route('infouni', $specialization->university_id)
                         ->with('success', 'Specialization updated successfully!');
    }
    public function deleteuni($id)
    {
        $university = University::findOrFail($id);
        $university->delete();
        return redirect('admin/rabat')->with('success', 'University deleted successfully');

    }
    public function deletespe($id)
    {
        $specialization = Specialization::find($id);
        $specialization->delete();
        return redirect('admin/rabat')->with('success', 'Specialization deleted successfully');
    }
    public function deleteinf($id)
    {
        $specialization = Specializations_Per_University::find($id);
        $specialization->delete();
        return back()->with('success', 'Specialization deleted successfully');
    }
    public function infouni($id)
    {
        $university = University::findOrFail($id); // جلب الجامعة أو إرجاع خطأ إذا لم تكن موجودة
        $allspecializations = Specialization::all();
        $specializations = Specializations_Per_University::with('specialization', 'grant')
            ->where('university_id', $id)
            ->get(); // جلب جميع التخصصات المرتبطة بالجامعة مع المنح

        return view('information', compact('university','specializations','allspecializations'));        

    }

    public function calculate(Request $request)
    {
        // إحضار جميع الجامعات مع الاختصاصات والمنح والطلبات
        $universities = University::with('specializationsPerUniversity.grant', 'specializationsPerUniversity.request')->get();

        foreach ($universities as $university) {
            foreach ($university->specializationsPerUniversity as $specialization) {
                $acceptedCount = 0;

                // قبول الطلبات ضمن المنحة إذا كانت موجودة
                if ($specialization->grant) {
                    $grantSeats = $specialization->grant->num_seats;
                    $requests1 = $specialization->request()->where('request_status', 'pending')->where('r_type_id', 2)->orderByDesc('total')->get();
                    $grantRequests = $requests1->take($grantSeats);

                    foreach ($grantRequests as $request) {
                        $request->update(['request_status' => 'Accepted']);
                        $acceptedCount++;
                    }

                    // إزالة الطلبات المقبولة من القائمة
                    $requests1 = $requests1->slice($grantSeats);
                    foreach ($requests1 as $request) {
                        $request->update(['request_status' => 'rejected']);
                    }
                }
                // الطلبات المرتبطة بهذا الاختصاص مرتبة حسب المعدل من الأعلى إلى الأدنى
                $requests = $specialization->request()->where('request_status', 'pending')->where('r_type_id', 1)->orderByDesc('total')->get();

                // قبول الطلبات المدفوعة حتى اكتمال عدد المقاعد الكلي
                $remainingSeats = $specialization->num_seats - $acceptedCount;
                $paidRequests = $requests->take($remainingSeats);

                foreach ($paidRequests as $request) {
                    $request->update(['request_status' => 'Accepted']);
                }

                // رفض الطلبات المتبقية
                $rejectedRequests = $requests->slice($remainingSeats);
                foreach ($rejectedRequests as $request) {
                    $request->update(['request_status' => 'rejected']);
                }
            }
        }

        return redirect('admin/dashboard')->with('success', 'Application process completed successfully.');
    }

}
