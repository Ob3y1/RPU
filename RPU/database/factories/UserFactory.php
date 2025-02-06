<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->firstName,
            'phone_number' => '+963' . $this->faker->unique()->numerify('9########'), // رقم هاتف يبدأ بـ +963
            'email' => $this->faker->unique()->safeEmail,
            'birth_date' => $this->faker->date('Y-m-d', '2005-01-01'), // تاريخ ميلاد حتى 2005
            'password' => Hash::make('password123'), // كلمة مرور افتراضية مشفرة
            'nationality' => $this->faker->country,
            'role_id' => 3, // ثابت
            'default_payment_method_id' => $this->determinePaymentMethod(),
            'remember_token' => \Str::random(10),
        ];
    }

    // دالة لاختيار payment_method_id بنسبة 95% بين 1 و 4
    private function determinePaymentMethod()
    {
        return $this->faker->boolean(95) 
            ? $this->faker->numberBetween(1, 4) 
            : $this->faker->numberBetween(5, 10); // 5% احتمال لاختيار قيمة أخرى
    }
}
