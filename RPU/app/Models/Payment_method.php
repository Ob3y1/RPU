<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment_method extends Model
{
    use HasFactory;
    protected $table = 'payment_methods';
    protected $fillable = [
        'method'
    ];
    public function payment()
    {
        return $this->hasMany(Payment::class, 'payment_method_id');
    }
    public function user()
    {
        return $this->hasMany(User::class, 'default_payment_method_id');
    }
}
  