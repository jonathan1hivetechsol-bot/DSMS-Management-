<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'user_id' => null, // Will be set manually or by seeder
            'class_id' => null, // Will be set manually or by seeder
            'student_id' => 'STU-' . $this->faker->unique()->numerify('####'),
            'date_of_birth' => $this->faker->dateTimeBetween('-18 years', '-5 years'),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'parent_name' => $this->faker->name(),
            'parent_phone' => $this->faker->phoneNumber(),
        ];
    }
}
