<?php

namespace Database\Factories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        return [
            'student_id' => null, // Will be set manually or by seeder
            'class_id' => null, // Will be set manually or by seeder
            'amount' => $this->faker->numberBetween(10000, 50000),
            'description' => 'School Fees - ' . $this->faker->randomElement(['1st Term', '2nd Term', '3rd Term', 'Annual']),
            'due_date' => $this->faker->dateTimeBetween('now', '+3 months'),
            'paid_at' => $this->faker->optional(0.6)->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
