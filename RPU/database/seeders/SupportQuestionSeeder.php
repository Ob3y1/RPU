<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SupportQuestion;
use Carbon\Carbon;

class SupportQuestionSeeder extends Seeder
{
    public function run()
    {
        $numQuestions = 300;
        $users = range(4, 300);
        $answeredPercentage = 0.9;
        $frequentPercentage = 0.05;

        for ($i = 1; $i <= $numQuestions; $i++) {
            $userId = $users[array_rand($users)];
            $isFrequent = rand(1, 100) <= ($frequentPercentage * 100);
            $status = $this->getRandomStatus($answeredPercentage);

            // إنشاء السؤال
            $question = SupportQuestion::create([
                'user_id' => $userId,
                'question' => 'This is a sample question number ' . $i,
                'answer' => $status === 'answered' ? 'This is the answer for question number ' . $i : null,
                'is_frequent' => $isFrequent,
                'status' => $status,
            ]);
            $randomDate = $this->getRandomDateInLastMonth();

            // إذا كان السؤال مجابًا، نضع `updated_at` ضمن الشهر الماضي
            if ($status === 'answered') {
                $question->update(['updated_at' => $randomDate]);
            }
            $question->update(['created_at' => $randomDate]);
        }
    }

    private function getRandomStatus($answeredPercentage)
    {
        $random = rand(1, 100);
        if ($random <= ($answeredPercentage * 100)) {
            return 'answered';
        }
        return rand(0, 1) ? 'pending' : 'rejected';
    }

    private function getRandomDateInLastMonth()
    {
        $start = Carbon::now()->subMonth()->startOfMonth(); // بداية الشهر الماضي
        $end = Carbon::now()->subMonth()->endOfMonth(); // نهاية الشهر الماضي
        return Carbon::createFromTimestamp(rand($start->timestamp, $end->timestamp))->toDateTimeString();
    }
}
