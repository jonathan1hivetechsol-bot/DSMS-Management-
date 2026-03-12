<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        $users = User::all();
        
        if ($users->count() < 2) {
            // Create users if they don't exist
            $sender = User::factory()->create();
            $receiver = User::factory()->create();
        } else {
            $sender = $users->random();
            $receiver = $users->where('id', '!=', $sender->id)->random();
        }

        $createdAt = $this->faker->dateTimeBetween('-30 days', 'now');

        return [
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'subject' => $this->faker->sentence(),
            'body' => $this->faker->paragraph(3),
            'read_at' => $this->faker->optional(0.6)->dateTimeBetween($createdAt, 'now'),
            'created_at' => $createdAt,
            'updated_at' => now(),
        ];
    }
}
