<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\SupportQuestion;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        DB::table('sittings')->insert([
            [
                'key' => 'start date',
                'value' => now()->subDays(10)->format('Y-m-d'), // 10 أيام قبل التاريخ الحالي
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'end date',
                'value' => now()->addDays(10)->format('Y-m-d'), // 10 أيام بعد التاريخ الحالي
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        SupportQuestion::insert([
            [
                'user_id' => 3,
                'question' => 'How can I reset my password?',
                'answer' => 'To reset your password, go to the login page and click "Forgot Password".',
                'is_frequent' => true,
                'status' => 'answered',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'question' => 'How can I update my profile picture?',
                'answer' => null, // لم يتم الرد بعد
                'is_frequent' => false,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'question' => 'What payment methods are accepted?',
                'answer' => 'We accept credit cards, PayPal, and Stripe.',
                'is_frequent' => true,
                'status' => 'answered',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'question' => 'Can I cancel my subscription?',
                'answer' => null, // لم يتم الرد بعد
                'is_frequent' => false,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'question' => 'How to contact customer support?',
                'answer' => 'You can contact customer support through the "Help" section in your account.',
                'is_frequent' => true,
                'status' => 'answered',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    
        DB::table('sittings')->insert([
            [
                'key' => 'request_fee',
                'value'=>10
            ]
        ]);
        DB::table('roles')->insert([
            ['role' => 'Admin'],
            ['role' => 'Support Agent'],
            ['role' => 'User'], // Role 3
        ]);
        DB::table('payment_methods')->insert([
            ['method' => 'card'],
            ['method' => 'paypal'],
            ['method' => 'amazon_pay'],
            ['method' => 'mobilepay'],
            ['method' => 'zip'],
            ['method' => 'us_bank_account'],
            ['method' => 'samsung_pay'],
            ['method' => 'payco'],
            ['method' => 'kakao_pay'],
            ['method' => 'naver_pay'],
            ['method' => 'kr_card'],
            ['method' => 'twint'],
            ['method' => 'alma'],
            ['method' => 'revolut_pay'],
            ['method' => 'wechat_pay'],
            ['method' => 'swish'],
            ['method' => 'sofort'],
            ['method' => 'sepa_debit'],
            ['method' => 'promptpay'],
            ['method' => 'pix'],
            ['method' => 'paynow'],
            ['method' => 'p24'],
            ['method' => 'oxxo'],
            ['method' => 'multibanco'],
            ['method' => 'link'],
            ['method' => 'konbini'],
            ['method' => 'klarna'],
            ['method' => 'ideal'],
            ['method' => 'grabpay'],
            ['method' => 'giropay'],
            ['method' => 'fpx'],
            ['method' => 'eps'],
            ['method' => 'customer_balance'],
            ['method' => 'cashapp'],
            ['method' => 'boleto'],
            ['method' => 'blik'],
            ['method' => 'bancontact'],
            ['method' => 'bacs_debit'],
            ['method' => 'au_becs_debit'],
            ['method' => 'alipay'],
            ['method' => 'afterpay_clearpay'],
            ['method' => 'affirm'],
            ['method' => 'acss_debit'],
        ]);
        DB::table('r_types')->insert([
            ['type' => 'Paid'],       
            ['type' => 'Grant']
        ]);
        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'phone_number' => '+963912345678',
                'email' => 'admin@example.com',
                'birth_date' => '1980-01-01',
                'password' => Hash::make('111q'),
                'nationality' => 'US',
                'role_id' => 1, // Admin
                'default_payment_method_id' => 1, 
            ],
            [
                'name' => 'Support Agent',
                'phone_number' => '+963923456789',
                'email' => 'support@example.com',
                'birth_date' => '1990-01-01',
                'password' => Hash::make('111w'),
                'nationality' => 'US',
                'role_id' => 2, // Admin
                'default_payment_method_id' => 1,   
            ],
            [
                'name' => 'Dlo',
                'phone_number' => '+963911111111',
                'email' => 'dlo@gmail.com',
                'birth_date' => '2000-01-01',
                'password' => Hash::make('111e'),
                'nationality' => 'US',
                'role_id' => 3, // Regular User
                'default_payment_method_id' => 1,
            ],
        ]);

            DB::table('payments')->insert([
                'user_id' => 3, 
                'payment_method_id' => 1, 
                'amount' =>  10000, 
                'payment_status' =>'pending',
                'currency' =>'USD',
                'transaction_id' => 7, 
                'payment_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);



            $universities = [
                ['name' => 'ASPU', 'location' => 'ALTAL', 'details' => 'جامعة خاصة تقع في العاصمة دمشق'],
                ['name' => 'SPU', 'location' => 'Daraa', 'details' => 'جامعة تقع في مدينة درعا'],
                ['name' => 'AIU', 'location' => 'Daraa', 'details' => 'جامعة تقع في مدينة درعا'],
                ['name' => 'IUST', 'location' => 'Daraa', 'details' => 'جامعة تقع في مدينة درعا'],
                ['name' => 'YU', 'location' => 'Daraa', 'details' => 'جامعة تقع في مدينة درعا']
            ];
    
            foreach ($universities as $university) {
                DB::table('universities')->insert($university);
            }
    
            // إدخال بيانات الاختصاصات
            $specializations = [
                ['name' => 'هندسة كمبيوتر', 'details' => 'اختصاص يختص بتطوير البرمجيات والتقنيات'],
                ['name' => 'طب بشري', 'details' => 'اختصاص في الطب البشري'],
                ['name' => 'هندسة مدنية', 'details' => 'اختصاص يهتم بالبناء والهياكل الهندسية'],
                ['name' => 'إدارة أعمال', 'details' => 'اختصاص في إدارة الأعمال وقيادتها'],
                ['name' => 'دراسات قانونية', 'details' => 'اختصاص في الحقوق والقوانين']
            ];
    
            foreach ($specializations as $specialization) {
                DB::table('specializations')->insert($specialization);
            }
    
            // إدخال البيانات في جدول العلاقة بين الجامعات والاختصاصات
            $specializations_per_universities = [
                
                ['university_id' => 1, 'specialization_id' => 1, 'price_per_hour' => 500000, 'num_seats' => 300],
                ['university_id' => 1, 'specialization_id' => 2, 'price_per_hour' => 600000, 'num_seats' => 500],
                ['university_id' => 1, 'specialization_id' => 3, 'price_per_hour' => 450000, 'num_seats' => 400],
    
               
                ['university_id' => 2, 'specialization_id' => 1, 'price_per_hour' => 550000, 'num_seats' => 305],
                ['university_id' => 2, 'specialization_id' => 2, 'price_per_hour' => 650000, 'num_seats' => 405],
    
               
                ['university_id' => 3, 'specialization_id' => 4, 'price_per_hour' => 400000, 'num_seats' => 300],
                ['university_id' => 3, 'specialization_id' => 5, 'price_per_hour' => 700000, 'num_seats' => 200],
    
                
                ['university_id' => 4, 'specialization_id' => 3, 'price_per_hour' => 500000, 'num_seats' => 400],
                ['university_id' => 4, 'specialization_id' => 4, 'price_per_hour' => 550000, 'num_seats' => 600],
    
           
                ['university_id' => 5, 'specialization_id' => 5, 'price_per_hour' => 450000, 'num_seats' => 500],
            ];
    
            foreach ($specializations_per_universities as $data) {
                DB::table('specializations__per__universities')->insert($data);
            }
            $grants = [
                ['unis_id' => 1, 'num_seats' => 20],
                ['unis_id' => 2, 'num_seats' => 25],
                ['unis_id' => 3, 'num_seats' => 15],
                ['unis_id' => 4, 'num_seats' => 30],
                ['unis_id' => 5, 'num_seats' => 10]
            ];
    
            foreach ($grants as $grant) {
                DB::table('grants')->insert($grant);
            }
    }
}
