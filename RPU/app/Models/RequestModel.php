<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestModel extends Model
{
    use HasFactory;
    protected $table = 'requests';
    protected $fillable = [
        'payment_id',
        'unis_id',
        'request_status',
        'r_type_id',
        'certificate_country',
        'total'
    ];
    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }
    public function specializationPerUniversity()
    {
        return $this->belongsTo(Specializations_Per_University::class, 'unis_id');
    }
    public function r_type()
    {
        return $this->belongsTo(R_type::class, 'r_type_id');
    }
}
 