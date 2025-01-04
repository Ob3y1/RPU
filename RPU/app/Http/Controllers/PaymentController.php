<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Payment;
use App\Models\Payment_method;
use App\Models\Sitting;
use App\Http\Controllers\UserController;


class PaymentController extends Controller
{
    public function createPayment(Request $request)
    {
        $dateController = app(UserController::class);
        $response = $dateController->isWithinDateRange();
        if ($response==0) {
            return response()->json([
                'message' => 'You are out of request period',
            ], 401);
        }
        $user = $request->user();
        Stripe::setApiKey(config('services.stripe.secret'));
    
        // الحصول على السعر من إعدادات النظام
        $price = Sitting::where('key', 'request_fee')->first();
    
        // التحقق من وجود وسيلة الدفع الخاصة بالمستخدم
        if (!$user->payment_method) {
            return response()->json([
                'error' => 'No default payment method found for the user.'
            ], 402);
        }
    
        // إنشاء جلسة الدفع عبر Stripe
        $session = Session::create([
            'payment_method_types' => [$user->payment_method->method],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Request Fee',
                    ],
                    'unit_amount' => $price->value * 100, // Stripe يتعامل بالسنت
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => url('/api/payment/success?session_id={CHECKOUT_SESSION_ID}'), // تحديث حالة الدفع بعد النجاح
            'cancel_url' => url('/api/payment/cancel?session_id={CHECKOUT_SESSION_ID}'), // تحديث حالة الدفع بعد الإلغاء
        ]);
    
        Payment::create([
            'user_id' => $user->id,
            'payment_method_id' => $user->default_payment_method_id,
            'amount' => $price->value,
            'payment_status' => 'pending',
            'currency' => 'USD',
            'transaction_id' => $session->id,
            'payment_date' => now(),
        ]);
    
        return response()->json([
            'checkout_url' => $session->url,
        ]);
    }
    
  
    public function success(Request $request)
    {
        // الحصول على session_id من الطلب
        $sessionId = $request->query('session_id');

        // التحقق من الدفع وتحديث حالته
        $payment = Payment::where('transaction_id', $sessionId)->first();

        if ($payment) {
            $payment->update([
                'payment_status' => 'completed',
            ]);
            $paymentStatus=1;
            return view('error',compact('paymentStatus'));
        }

        $paymentStatus=2;
        return view('error',compact('paymentStatus'));
    }

    public function cancel(Request $request)
    {
        // الحصول على session_id من الطلب
        $sessionId = $request->query('session_id');

        // تحديث حالة الدفع إلى "failed" أو إلغائها
        $payment = Payment::where('transaction_id', $sessionId)->first();

        if ($payment) {
            $payment->update([
                'payment_status' => 'failed',
            ]);
            $paymentStatus=0;
            return view('error',compact('paymentStatus'));
        }
        $paymentStatus=2;
        return view('error',compact('paymentStatus'));
    }
}
