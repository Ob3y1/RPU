<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RequestModel;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserRequestSeeder extends Seeder
{
    public function run()
    {
        // عدد المستخدمين الذين سنقوم بإنشائهم
        $numUsers = 400;

        // النسبة المئوية للطلبات المدفوعة والمنح
        $paidPercentage = 0.8;  // 80% مدفوعة
        $grantPercentage = 0.2; // 20% منحة

        for ($i = 1; $i <= $numUsers; $i++) {
            // إنشاء المستخدم
            $user = User::factory()->create();  // تأكد من أنك تملك Factory للمستخدم

            // توليد علامة عشوائية للمستخدم
            $totalScore = rand(50, 100);

            for ($j = 1; $j <= 10; $j++) {
                // إنشاء transaction_id فريد
                $transactionId = 'TXN-' . uniqid() . '-' . $i . $j;

                $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth(); // بداية الشهر الماضي
                $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();     // نهاية الشهر الماضي
                
                $paymentDate = Carbon::createFromTimestamp(rand($startOfLastMonth->timestamp, $endOfLastMonth->timestamp))
                                     ->format('Y-m-d H:i:s');
                

                // إنشاء عملية دفع بقيمة 10
                $payment = Payment::create([
                    'user_id' => $user->id,
                    'payment_method_id' => $user->default_payment_method_id,
                    'amount' => 10,
                    'payment_status' => 'completed',
                    'currency' => 'USD',
                    'is_used' => 1,
                    'transaction_id' => $transactionId,
                    'payment_date' => $paymentDate,
                ]);

                // تحديد unis_id عشوائي بين 1 و 19
                $unis_id = rand(1, 19);

                // تحديد نوع الطلب (80% مدفوعة و20% منحة)
                $r_type_id = (rand(1, 100) <= ($paidPercentage * 100)) ? 1 : 2;

                // إنشاء الطلب المرتبط بعملية الدفع
                RequestModel::create([
                    'payment_id' => $payment->id,
                    'unis_id' => $unis_id,
                    'r_type_id' => $r_type_id,
                    'request_status' => 'pending',
                    'certificate_country' => 'Syria', // يمكنك تغييره حسب الحاجة
                    'total' => $totalScore,
                ]);
            }
        }
    }
}
