<?php

namespace Database\Factories;

use App\Models\Payroll;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payroll>
 */
class PayrollFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $baseSalary = $this->faker->numberBetween(30000, 100000);
        $workingDays = 26;
        $presentDays = $this->faker->numberBetween(20, 26);
        $perDayRate = $baseSalary / $workingDays;
        $allowances = $this->faker->numberBetween(0, 5000);
        $grossSalary = ($perDayRate * $presentDays) + $allowances;
        $deductions = $this->faker->numberBetween(0, 5000);
        $netSalary = $grossSalary - $deductions;

        $now = now();

        return [
            'teacher_id' => Teacher::factory(),
            'year' => $now->year,
            'month' => $now->month,
            'base_salary' => $baseSalary,
            'working_days' => $workingDays,
            'present_days' => $presentDays,
            'absent_days' => $workingDays - $presentDays,
            'leave_days' => 0,
            'deductions' => $deductions,
            'allowances' => $allowances,
            'gross_salary' => $grossSalary,
            'net_salary' => $netSalary,
            'status' => $this->faker->randomElement(['pending', 'approved', 'paid']),
            'payment_date' => $this->faker->optional()->dateTime(),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }

    /**
     * Indicate the payroll is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'payment_date' => null,
        ]);
    }

    /**
     * Indicate the payroll is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'payment_date' => null,
        ]);
    }

    /**
     * Indicate the payroll is paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid',
            'payment_date' => now(),
        ]);
    }
}
