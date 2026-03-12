<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'teacher_id',
        'cnic',
        'phone',
        'subject',
        'hire_date',
        'qualification',
        'qualifications',
        'specialization',
        'years_of_experience',
        'previous_schools',
        'salary',
        'employment_status',
        'salary_review_date',
        'teaching_approach',
        'document_verified_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schoolClasses()
    {
        return $this->hasMany(SchoolClass::class, 'teacher_id');
    }

    // convenience: students across all classes
    public function students()
    {
        return $this->hasManyThrough(Student::class, SchoolClass::class, 'teacher_id', 'class_id');
    }

    // Teacher Attendance and Payroll relationships
    public function attendances()
    {
        return $this->hasMany(TeacherAttendance::class);
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }
}
