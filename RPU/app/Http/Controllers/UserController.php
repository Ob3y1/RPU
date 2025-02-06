<?php

namespace App\Http\Controllers;

use App\Models\University;
use App\Models\Specialization;
use App\Models\RequestModel; 
use App\Models\Sitting;
use App\Models\Payment;
use App\Models\Grant;

use Carbon\Carbon;

use App\Models\Specializations_Per_University;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function isWithinDateRange()
    {
        $start = Sitting::where('key', 'start date')->first();
        $end = Sitting::where('key', 'end date')->first();

        $startDate = Carbon::parse($start->value);
        $endDate = Carbon::parse($end->value);
        $currentDate = Carbon::now();

        if ($currentDate->between($startDate, $endDate)) {
            return 1;
        } else {
            return 0;
        }
    }

    public function search1()
    {
        $locations=University::distinct()->pluck('location');
        $Specialization = Specialization::select('id', 'name')->get();
        return response()->json([
            'Specialization' => $Specialization,
            'locations'=>$locations
        ], 201);
    }
    public function search(Request $request)
    {
        // الحصول على القيم المدخلة من المستخدم (كمصفوفات)
        $locations = $request->input('locations', []); // مواقع متعددة
        $specializations = $request->input('specializations', []); // تخصصات متعددة

        // الاستعلام الأساسي
        $query = University::query();

        // البحث بناءً على أكثر من موقع (إذا وُجدت قيم)
        if (!empty($locations)) {
            $query->whereIn('location', $locations);
        }

        // البحث بناءً على أكثر من تخصص (إذا وُجدت قيم)
        if (!empty($specializations)) {
            $query->whereHas('specializationsPerUniversity.specialization', function ($subQuery) use ($specializations) {
                $subQuery->whereIn('name', $specializations);
            });
        }

        // جلب النتائج مع التخصصات
        $universities = $query->with('specializationsPerUniversity.specialization')->get();

        // إرجاع النتائج
        return response()->json([
            'success' => true,
            'data' => $universities,
        ]);
    }

    public function getUniversities()
    {
        $universities = University::all();
        return response()->json([
            'universities' => $universities
        ], 201);
    }

    public function getSpecializationsForUniversity($universityId)
    {
        $university = University::find($universityId);

        if (!$university) {
            return response()->json([
                'message' => 'University not found'
            ], 404); // 404 - Not Found
        }

        $university = University::with([
            'specializationsPerUniversity.specialization', 
            'specializationsPerUniversity.grant'
        ])
        ->findOrFail($universityId);
    
        $response = [
            'university' => $university->name,
            'specializations' => [],   
            'grants' => [],
        ];
    
        foreach ($university->specializationsPerUniversity as $specializationPerUniversity) {
            $specialization = $specializationPerUniversity->specialization;
            $response['specializations'][] = [
                'specialization_id'=>$specialization->id,
                'specialization_name' => $specialization->name,
                'specialization_details' => $specialization->details,
                'price_per_hour' => $specializationPerUniversity->price_per_hour,
                'num_seats' => $specializationPerUniversity->num_seats,
            ];
    
            if ($specializationPerUniversity->grant) {
                $response['grants'][] = [
                    'specialization_id'=>$specialization->id,
                    'specialization_name' => $specialization->name,
                    'specialization_details' => $specialization->details,
                    'num_seats' => $specializationPerUniversity->num_seats,
                    'grant_num_seats' => $specializationPerUniversity->grant->num_seats,
                ];
            }
        }
    
        return response()->json($response);
    }
    public function submitRequest(Request $request)
    {
        try {
        $response = $this->isWithinDateRange();
        if ($response==0) {
            return response()->json([
                'message' => 'You are out of request period',
            ], 401);
        }

        $user = $request->user();

        $payment = Payment::where('user_id', $user->id)
        ->where('payment_status', 'completed')
        ->where('is_used', false) 
        ->latest()
        ->first();
        
        if (!$payment) {
            return response()->json([
                'error' => 'No available payment found. Please complete a payment before submitting a request.',
            ], 402);
        }
    
        $jsonData = json_decode($request->getContent(), true); 
        if ($jsonData) {
            $request->merge($jsonData); 
        }
       
            $validated = $request->validate([
                'university' => 'required|exists:universities,name', 
                'specialization' => 'required|exists:specializations,name', 
                'r_type_id' => 'required|exists:r_types,id', 
                'certificate_country' => 'required|string|in:Syria,Other',
                'total' => 'required|numeric',
                'personal_id' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048', // صورة الهوية
                'Bachelors_certificate' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048', // صورة شهادة البكالوريا
            ]);

            $university=University::where('name',$validated['university'])->first();
            $specialization=Specialization::where('name',$validated['specialization'])->first();
            $unis = Specializations_Per_University::where('university_id',$university->id )
            ->where('specialization_id',$specialization->id )
            ->first();

            if (!$unis) {
                return response()->json([
                    'error' => 'The specified specialization is not available at this university',
                ], 406); // Bad Request
            }
            if($validated['r_type_id']==2){
                $gr=Grant::where('unis_id',$unis->id)->first();
                if (!$gr) {
                    return response()->json([
                        'error' => 'The specified specialization is not available at this university as grant',
                    ], 404); 
                }
            }

            $existingRequest = RequestModel::whereHas('payment', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('unis_id', $unis->id)  
            ->where('r_type_id', $validated['r_type_id'])  
            ->where('request_status', 'pending')  
            ->first();

            if ($existingRequest) {
                return response()->json([
                    'error' => 'You have already submitted a request for this university and specialization.',
                ], 405);
            }
            // رفع الصور إلى المجلد المناسب
            $personalIdPath = $request->file('personal_id')->store('documents/personal_ids', 'public');
            $bachelorsCertificatePath = $request->file('Bachelors_certificate')->store('documents/Bachelors_certificates', 'public');

            $user->document()->create([
                'document_type' => 'personal_id',
                'image' => $personalIdPath,
            ]);

            $user->document()->create([
                'document_type' => 'Bachelors_certificate',
                'image' => $bachelorsCertificatePath,
            ]);

            $newRequest = RequestModel::create([
                'payment_id' => $payment->id,
                'unis_id' => $unis->id,
                'r_type_id' => $validated['r_type_id'],
                'request_status' => 'pending',
                'certificate_country' => $validated['certificate_country'],
                'total' => $validated['total'],
            ]);
            

            $payment->is_used=true;
            $payment->update();
            return response()->json([
                'message' => 'Request submitted successfully',
                'request' => $newRequest,
            ], 201);
        }
         catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }
    }
    public function getUserRequests(Request $request)
    {
        try {
            $user = $request->user();
            $requests = RequestModel::with([
                'specializationPerUniversity.university', 
                'specializationPerUniversity.specialization', 
                'r_type', 
                'payment.payment_method'
            ])->whereHas('payment', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();


            if ($requests->isEmpty()) {
                return response()->json([
                    'message' => 'لا توجد طلبات مقدمة من هذا المستخدم.',
                ], 201);
            }
            return response()->json([
                'message' => 'تم استرجاع الطلبات بنجاح.',
                'requests' => $requests
            ], 202);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'حدث خطأ أثناء استرجاع الطلبات: ' . $e->getMessage(),
            ], 500);
        }
    }


}
