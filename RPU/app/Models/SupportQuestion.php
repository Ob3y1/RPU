<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportQuestion extends Model
{
    use HasFactory;
    protected $table = 'support_questions';
    protected $fillable = [
        'user_id',
        'question',
        'answer',
        'is_faq',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
