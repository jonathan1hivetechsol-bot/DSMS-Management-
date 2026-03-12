<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Message;

class MessagePolicy
{
    /**
     * Determine whether the user can view any messages.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view their own messages
    }

    /**
     * Determine whether the user can view the message.
     */
    public function view(User $user, Message $message): bool
    {
        // User can view message if they are sender or receiver
        return $user->id === $message->sender_id || $user->id === $message->receiver_id;
    }

    /**
     * Determine whether the user can create messages.
     */
    public function create(User $user): bool
    {
        return true; // All authenticated users can create messages
    }

    /**
     * Determine whether the user can delete the message.
     */
    public function delete(User $user, Message $message): bool
    {
        // User can delete message if they are sender or receiver
        return $user->id === $message->sender_id || $user->id === $message->receiver_id;
    }
}
