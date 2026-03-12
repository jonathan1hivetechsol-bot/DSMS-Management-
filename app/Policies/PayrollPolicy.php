<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Payroll;

class PayrollPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Payroll $payroll): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Payroll $payroll): bool
    {
        return $user->role === 'admin' && $payroll->status !== 'paid';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Payroll $payroll): bool
    {
        return $user->role === 'admin' && $payroll->status !== 'paid';
    }

    /**
     * Determine whether the user can approve payroll.
     */
    public function approve(User $user, Payroll $payroll): bool
    {
        return $user->role === 'admin';
    }
}
