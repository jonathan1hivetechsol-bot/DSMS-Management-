<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'year',
        'month',
        'base_salary',
        'working_days',
        'present_days',
        'absent_days',
        'leave_days',
        'deductions',
        'allowances',
        'gross_salary',
        'net_salary',
        'status',
        'payment_date',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    // Scopes for filtering
    public function scopeByTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    public function scopeByMonth($query, $year, $month)
    {
        return $query->where('year', $year)->where('month', $month);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    // Calculate net salary
    public function calculateNetSalary()
    {
        $this->net_salary = $this->gross_salary - $this->deductions;
        return $this->net_salary;
    }

    // Calculate gross salary from per day rate
    public function calculateGrossSalary()
    {
        $perDayRate = $this->base_salary / $this->working_days;
        $salaryFromAttendance = $perDayRate * $this->present_days;
        $this->gross_salary = $salaryFromAttendance + $this->allowances;
        return $this->gross_salary;
    }
}
