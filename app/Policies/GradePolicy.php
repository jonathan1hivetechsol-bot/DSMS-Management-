<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Grade;

class GradePolicy
{
    /**
     * Determine whether the user can view any grades.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'teacher' || $user->role === 'student';
    }

    /**
     * Determine whether the user can view the grade.
     */
    public function view(User $user, Grade $grade): bool
    {
        // Admins can view any grade
        if ($user->role === 'admin') {
            return true;
        }

        // Teachers can view grades
        if ($user->role === 'teacher') {
            return true;
        }

        // Students can only view their own grades
        if ($user->role === 'student') {
            return $user->student?->id === $grade->student_id;
        }

        return false;
    }

    /**
     * Determine whether the user can create grades.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'teacher';
    }

    /**
     * Determine whether the user can update the grade.
     */
    public function update(User $user, Grade $grade): bool
    {
        // Only admins and teachers can update grades
        return $user->role === 'admin' || $user->role === 'teacher';
    }

    /**
     * Determine whether the user can delete the grade.
     */
    public function delete(User $user, Grade $grade): bool
    {
        // Only admins can delete grades
        return $user->role === 'admin';
    }
}
