<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grant extends Model
{
    use HasFactory;
    protected $table = 'grants';

   
    protected $fillable = [
        'unis_id', 
        'num_seats',
    ];

    
    public function specializationPerUniversity()
    {
        return $this->belongsTo(SpecializationPerUniversity::class, 'unis_id');
    }
}
