<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'class_id',
        'amount',
        'due_date',
        'description',
        'paid_at',
    ];

    protected $dates = ['due_date', 'paid_at'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function markPaid()
    {
        $this->paid_at = now();
        $this->save();
    }

    public function isPaid()
    {
        return $this->paid_at !== null;
    }
}
