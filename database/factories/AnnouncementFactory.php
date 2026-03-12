<?php

namespace Database\Factories;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Announcement>
 */
class AnnouncementFactory extends Factory
{
    protected $model = Announcement::class;

    public function definition(): array
    {
        $types = ['notice', 'alert', 'event', 'holiday'];
        $priorities = ['low', 'medium', 'high'];

        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'type' => $this->faker->randomElement($types),
            'priority' => $this->faker->randomElement($priorities),
            'published_by' => User::factory(),
            'published_at' => $this->faker->optional(0.8)->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
