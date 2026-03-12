<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsAppRecipient extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone_number',
        'recipient_type',
        'recipient_id',
        'name',
        'email',
        'is_active',
        'opt_in',
        'alert_preferences',
        'verified_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'opt_in' => 'boolean',
        'alert_preferences' => 'array',
        'verified_at' => 'datetime',
    ];

    public function getRecipient()
    {
        return match ($this->recipient_type) {
            'student' => Student::find($this->recipient_id),
            'teacher' => Teacher::find($this->recipient_id),
            'parent' => User::find($this->recipient_id),
            'admin' => User::find($this->recipient_id),
            default => null,
        };
    }
}
