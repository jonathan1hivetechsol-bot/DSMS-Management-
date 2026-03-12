<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

    public function definition(): array
    {
        $subjects = ['Mathematics', 'English', 'Science', 'History', 'Geography', 'Computer Science', 'Physics', 'Chemistry', 'Biology'];
        $qualifications = ['B.Ed', 'M.Ed', 'B.Sc', 'B.A', 'M.Sc', 'M.A'];

        return [
            'user_id' => null, // Will be set manually or by seeder
            'teacher_id' => 'TEACH-' . $this->faker->unique()->numerify('####'),
            'subject' => $this->faker->randomElement($subjects),
            'qualification' => $this->faker->randomElement($qualifications),
            'hire_date' => $this->faker->dateTimeBetween('-10 years', 'now'),
            'salary' => $this->faker->numberBetween(30000, 80000),
        ];
    }
}

