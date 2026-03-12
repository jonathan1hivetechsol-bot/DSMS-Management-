<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subject>
 */
class SubjectFactory extends Factory
{
    protected $model = Subject::class;

    public function definition(): array
    {
        $subjects = ['Mathematics', 'English', 'Science', 'History', 'Geography', 'Computer Science', 'Physics', 'Chemistry', 'Biology', 'Art', 'Physical Education'];

        return [
            'name' => $this->faker->randomElement($subjects),
            'code' => $this->faker->unique()->bothify('SUB-####'),
            'description' => $this->faker->paragraph(),
        ];
    }
}
