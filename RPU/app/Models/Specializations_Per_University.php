<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specializations_Per_University extends Model
{
    use HasFactory;
    protected $table = 'specializations__per__universities';

  
    protected $fillable = [
        'university_id',
        'specialization_id',
        'price_per_hour',
        'num_seats',
    ];

   
    public function grant()
    {
        return $this->hasOne(Grant::class, 'unis_id');
    }   
    public function university()
    {
        return $this->belongsTo(University::class, 'university_id');
    }
    public function specialization()
    {
        return $this->belongsTo(Specialization::class, 'specialization_id');
    }
    public function request()
    {
        return $this->hasMany(RequestModel::class, 'unis_id');
    }
}
 