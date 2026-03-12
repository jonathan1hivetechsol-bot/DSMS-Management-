<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudentLeave>
 */
class StudentLeaveFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fromDate = $this->faker->dateTimeBetween('now', '+30 days');
        $toDate = (clone $fromDate)->modify('+' . $this->faker->numberBetween(1, 7) . ' days');
        $numberOfDays = (int)$toDate->diff($fromDate)->format('%d') + 1;

        return [
            'student_id' => \App\Models\Student::inRandomOrder()->first()?->id ?? \App\Models\Student::factory(),
            'leave_type' => $this->faker->randomElement(['medical', 'personal', 'casual', 'earned', 'unpaid']),
            'from_date' => $fromDate,
            'to_date' => $toDate,
            'number_of_days' => $numberOfDays,
            'reason' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['pending', 'approved']),
            'approved_by' => $this->faker->boolean ? \App\Models\User::where('role', 'admin')->first()?->id : null,
            'approved_at' => $this->faker->boolean ? now() : null,
            'auto_attendance' => true,
        ];
    }
}
