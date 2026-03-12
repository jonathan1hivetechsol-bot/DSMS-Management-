<?php

namespace Database\Factories;

use App\Models\TeacherAttendance;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TeacherAttendance>
 */
class TeacherAttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['present', 'absent', 'late', 'leave'];
        $status = $this->faker->randomElement($statuses);

        return [
            'teacher_id' => Teacher::factory(),
            'attendance_date' => $this->faker->dateTime(),
            'status' => $status,
            'remarks' => $status === 'absent' ? $this->faker->sentence() : null,
            'leave_type' => $status === 'leave' ? $this->faker->randomElement(['medical', 'casual', 'earned', 'unpaid']) : null,
        ];
    }

    /**
     * Indicate the teacher was present.
     */
    public function present(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'present',
            'remarks' => null,
            'leave_type' => null,
        ]);
    }

    /**
     * Indicate the teacher was absent.
     */
    public function absent(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'absent',
            'remarks' => $this->faker->sentence(),
        ]);
    }

    /**
     * Indicate the teacher was on leave.
     */
    public function leave(string $type = 'casual'): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'leave',
            'leave_type' => $type,
        ]);
    }
}
