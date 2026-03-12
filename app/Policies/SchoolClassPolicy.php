<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SchoolClass;

class SchoolClassPolicy
{
    /**
     * Determine whether the user can view any classes.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'teacher' || $user->role === 'student';
    }

    /**
     * Determine whether the user can view the class.
     */
    public function view(User $user, SchoolClass $schoolClass): bool
    {
        return true; // All authenticated users can view classes
    }

    /**
     * Determine whether the user can create classes.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the class.
     */
    public function update(User $user, SchoolClass $schoolClass): bool
    {
        // Only admins can update classes
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the class.
     */
    public function delete(User $user, SchoolClass $schoolClass): bool
    {
        // Only admins can delete classes
        return $user->role === 'admin';
    }
}
