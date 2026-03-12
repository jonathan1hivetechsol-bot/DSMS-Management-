<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WhatsAppGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',  // students, teachers, parents, guardians, custom
        'filters', // JSON with conditions
        'member_count',
        'is_active',
    ];

    protected $casts = [
        'filters' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get all alerts sent to this group
     */
    public function sentAlerts(): HasMany
    {
        return $this->hasMany(WhatsAppAlert::class, 'group_id');
    }

    /**
     * Get members based on group type and filters
     */
    public function getMembers()
    {
        return match ($this->type) {
            'students' => $this->getStudentMembers(),
            'teachers' => $this->getTeacherMembers(),
            'parents' => $this->getParentMembers(),
            'guardians' => $this->getGuardianMembers(),
            'custom' => $this->getCustomMembers(),
            default => collect(),
        };
    }

    /**
     * Get student members with phone numbers
     */
    private function getStudentMembers()
    {
        $query = Student::with('user')
            ->whereHas('user', function ($q) {
                $q->where('is_active', true);
            });

        if ($this->filters && isset($this->filters['class_id'])) {
            $query->where('school_class_id', $this->filters['class_id']);
        }

        return $query->get()->filter(function ($student) {
            return $student->user && $student->user->phone;
        });
    }

    /**
     * Get teacher members with phone numbers
     */
    private function getTeacherMembers()
    {
        return Teacher::with('user')
            ->whereHas('user', function ($q) {
                $q->where('is_active', true);
            })
            ->get()
            ->filter(function ($teacher) {
                return $teacher->phone;
            });
    }

    /**
     * Get parent/guardian members with phone numbers
     */
    private function getParentMembers()
    {
        return Student::with('user')
            ->where('guardian_phone', '!=', null)
            ->where('guardian_phone', '!=', '')
            ->get();
    }

    /**
     * Get guardian members
     */
    private function getGuardianMembers()
    {
        return Student::where('guardian_phone', '!=', null)
            ->where('guardian_phone', '!=', '')
            ->get();
    }

    /**
     * Get custom members (user-defined list)
     */
    private function getCustomMembers()
    {
        if (!$this->filters || !isset($this->filters['phone_numbers'])) {
            return collect();
        }

        return collect($this->filters['phone_numbers'])->map(function ($phone) {
            return (object) ['phone' => $phone, 'name' => 'Custom Contact'];
        });
    }

    /**
     * Send message to all group members
     */
    public function broadcastMessage(string $message, ?WhatsAppTemplate $template = null)
    {
        $members = $this->getMembers();
        $sentCount = 0;

        foreach ($members as $member) {
            $phone = $member->phone ?? $member->guardian_phone;
            
            if ($phone) {
                $alert = app(\App\Services\WhatsAppService::class)->sendMessage(
                    $phone,
                    $message,
                    $template
                );

                if ($alert) {
                    $sentCount++;
                    $alert->update(['group_id' => $this->id]);
                }
            }
        }

        $this->update(['member_count' => $sentCount]);
        return $sentCount;
    }
}
