<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colaborator extends Model
{
    protected $fillable = [
        'document_type',
        'document_number',
        'first_name',
        'last_name',
        'gender',
        'birth_date',
        'personal_email',
        'corporate_email',
        'mobile',
        'phone',
        'address',
        'residential_city',
        'education_level',
        'job_position',
        'hire_date',
        'status',
        'eps',
        'arl',
        'notes',            
    ];

}
