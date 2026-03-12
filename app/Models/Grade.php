<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject',
        'marks_obtained',
        'total_marks',
        'term',
        'exam_type',
        'remarks',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function getPercentageAttribute()
    {
        return $this->total_marks > 0 ? round(($this->marks_obtained / $this->total_marks) * 100, 2) : 0;
    }

    public function getGradeAttribute()
    {
        $percentage = $this->percentage;
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B';
        if ($percentage >= 60) return 'C';
        if ($percentage >= 50) return 'D';
        return 'F';
    }
}
