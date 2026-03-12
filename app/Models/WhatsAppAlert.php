<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsAppAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_id',
        'group_id',
        'recipient_phone',
        'status',
        'message',
        'data',
        'provider',
        'provider_message_id',
        'error_message',
        'retry_count',
        'sent_at',
        'delivered_at',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(WhatsAppTemplate::class, 'template_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(WhatsAppGroup::class, 'group_id');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isSent(): bool
    {
        return $this->status === 'sent';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }
}
