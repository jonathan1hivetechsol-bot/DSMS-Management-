<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TeacherAttendance;

class TeacherAttendancePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'principal';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TeacherAttendance $teacherAttendance): bool
    {
        return $user->role === 'admin' || $user->role === 'principal';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'principal';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TeacherAttendance $teacherAttendance): bool
    {
        return $user->role === 'admin' || $user->role === 'principal';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TeacherAttendance $teacherAttendance): bool
    {
        return $user->role === 'admin';
    }
}
