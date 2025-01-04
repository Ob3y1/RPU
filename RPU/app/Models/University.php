<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    use HasFactory;
    protected $table = 'universities';

    protected $fillable = [
        'name',
        'location',
        'details',
    ];

   
    public function specializationsPerUniversity()
    {
        return $this->hasMany(Specializations_Per_University::class, 'university_id');
    }
}
