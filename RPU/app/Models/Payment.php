<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'payment_method_id',
        'amount',
        'payment_status',
        'currency',
        'transaction_id',
        'payment_date',
    ];

    const PAYMENT_STATUSES = ['pending', 'completed', 'failed'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function payment_method()
    {
        return $this->belongsTo(Payment_method::class, 'payment_method_id');
    }
    public function request()
    {
        return $this->hasone(RequestModel::class, 'payment_id');
    }
} 
