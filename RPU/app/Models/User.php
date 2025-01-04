<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'phone_number',
        'birth_date',
        'nationality',
        'default_payment_method'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
    
    public function payment_method()
    {
        return $this->belongsTo(Payment_method::class, 'default_payment_method_id');
    }
    public function supportQuestion()
    {
        return $this->hasMany(SupportQuestion::class, 'user_id');
    }
    public function payment()
    {
        return $this->hasMany(Payment::class, 'user_id');
    }
    public function document()
    {
        return $this->hasMany(UDocument::class, 'user_id');
    }
}
  