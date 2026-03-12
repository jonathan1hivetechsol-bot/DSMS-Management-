<?php

namespace Database\Factories;

use App\Models\Attendance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition(): array
    {
        return [
            'student_id' => null, // Will be set manually or by seeder
            'class_id' => null, // Will be set manually or by seeder
            'attendance_date' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'status' => $this->faker->randomElement(['present', 'absent', 'late', 'excused']),
            'remarks' => $this->faker->optional(0.3)->sentence(),
        ];
    }
}
