<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Teacher;

class TeacherPolicy
{
    /**
     * Determine whether the user can view any teachers.
     */
    public function viewAny(User $user): bool
    {
        return true; // All users can view teachers
    }

    /**
     * Determine whether the user can view the teacher.
     */
    public function view(User $user, Teacher $teacher): bool
    {
        return true; // All users can view teacher details
    }

    /**
     * Determine whether the user can create teachers.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin']);
    }

    /**
     * Determine whether the user can update the teacher.
     */
    public function update(User $user, Teacher $teacher): bool
    {
        // Admins can update any teacher
        // Teachers can update their own information
        return $user->role === 'admin' || $user->teacher?->id === $teacher->id;
    }

    /**
     * Determine whether the user can delete the teacher.
     */
    public function delete(User $user, Teacher $teacher): bool
    {
        // Only admins can delete teachers
        return $user->role === 'admin';
    }
}
