<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_id',
        'registration_number',
        'roll_number',
        'date_of_birth',
        'blood_group',
        'address',
        'phone',
        'parent_name',
        'parent_phone',
        'guardian_cnic',
        'guardian_occupation',
        'guardian_phone_alt',
        'medical_conditions',
        'previous_school',
        'class_id',
        'admission_status',
        'admission_date',
        'remarks',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
    public function grades()
    {
        return $this->hasMany(\App\Models\Grade::class);
    }

    public function invoices()
    {
        return $this->hasMany(\App\Models\Invoice::class);
    }

    public function leaves()
    {
        return $this->hasMany(StudentLeave::class);
    }
}
