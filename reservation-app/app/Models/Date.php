<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Date extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'patient_id',
        'start_time',
        'end_time',
        'reserved',
        'reason',
        'cnp',
        'birthdate',
        'phone',
        'section_in_institution_id',
    ];
    /*
    protected $hidden = [
        'doctor_id',
        'patient_id',
        'start_time',
        'end_time',
        'reserved',
        'reason',
        'cnp',
        'birthdate',
        'phone',
        'section_in_institution_id',
    ];
    */
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}
