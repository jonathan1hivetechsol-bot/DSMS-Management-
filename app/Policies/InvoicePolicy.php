<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Invoice;

class InvoicePolicy
{
    /**
     * Determine whether the user can view any invoices.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'teacher' || $user->role === 'student';
    }

    /**
     * Determine whether the user can view the invoice.
     */
    public function view(User $user, Invoice $invoice): bool
    {
        // Admins can view any invoice
        if ($user->role === 'admin') {
            return true;
        }

        // Teachers can view invoices
        if ($user->role === 'teacher') {
            return true;
        }

        // Students can only view their own invoices
        if ($user->role === 'student') {
            return $user->student?->id === $invoice->student_id;
        }

        return false;
    }

    /**
     * Determine whether the user can create invoices.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'teacher';
    }

    /**
     * Determine whether the user can update the invoice.
     */
    public function update(User $user, Invoice $invoice): bool
    {
        // Only admins and teachers can update invoices
        return $user->role === 'admin' || $user->role === 'teacher';
    }

    /**
     * Determine whether the user can delete the invoice.
     */
    public function delete(User $user, Invoice $invoice): bool
    {
        // Only admins can delete invoices
        return $user->role === 'admin';
    }
}
