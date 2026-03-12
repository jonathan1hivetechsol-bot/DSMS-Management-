<?php

namespace App\Policies;

use App\Models\StudentLeave;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StudentLeavePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'teacher';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, StudentLeave $studentLeave): bool
    {
        if ($user->role === 'admin' || $user->role === 'teacher') {
            return true;
        }
        if ($user->role === 'student') {
            return $user->student?->id === $studentLeave->student_id;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'student';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, StudentLeave $studentLeave): bool
    {
        // Only admin or the student (if pending) can update
        if ($user->role === 'admin') {
            return true;
        }
        if ($user->role === 'student' && $studentLeave->isPending()) {
            return $user->student?->id === $studentLeave->student_id;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, StudentLeave $studentLeave): bool
    {
        // Admin or student can delete if pending
        if ($user->role === 'admin') {
            return true;
        }
        if ($user->role === 'student' && $studentLeave->isPending()) {
            return $user->student?->id === $studentLeave->student_id;
        }
        return false;
    }

    /**
     * Determine whether the user can approve the model.
     */
    public function approve(User $user, StudentLeave $studentLeave): bool
    {
        return $user->role === 'admin' || $user->role === 'teacher';
    }

    /**
     * Determine whether the user can reject the model.
     */
    public function reject(User $user, StudentLeave $studentLeave): bool
    {
        return $user->role === 'admin' || $user->role === 'teacher';
    }

    /**
     * Determine whether the user can force delete the model.
     */
    public function forceDelete(User $user, StudentLeave $studentLeave): bool
    {
        return false;
    }
}
