<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Attendance;

class AttendancePolicy
{
    /**
     * Determine whether the user can view any attendance records.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'teacher' || $user->role === 'student';
    }

    /**
     * Determine whether the user can view the attendance.
     */
    public function view(User $user, Attendance $attendance): bool
    {
        // Admins can view any attendance
        if ($user->role === 'admin') {
            return true;
        }

        // Teachers can view attendance for their classes
        if ($user->role === 'teacher') {
            return true;
        }

        // Students can only view their own attendance
        if ($user->role === 'student') {
            return $user->student?->id === $attendance->student_id;
        }

        return false;
    }

    /**
     * Determine whether the user can create attendance records.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'teacher';
    }

    /**
     * Determine whether the user can update attendance records.
     */
    public function update(User $user, Attendance $attendance): bool
    {
        // Only admins and teachers can update attendance
        return $user->role === 'admin' || $user->role === 'teacher';
    }

    /**
     * Determine whether the user can delete attendance records.
     */
    public function delete(User $user, Attendance $attendance): bool
    {
        // Only admins can delete attendance records
        return $user->role === 'admin';
    }
}
