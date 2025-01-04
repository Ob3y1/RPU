<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sitting extends Model
{
    use HasFactory;
    protected $table = 'sittings';
    protected $fillable = [
        'key',
        'value'
    ];
}
