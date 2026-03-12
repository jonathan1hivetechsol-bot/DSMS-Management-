<?php

namespace Database\Factories;

use App\Models\Grade;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Grade>
 */
class GradeFactory extends Factory
{
    protected $model = Grade::class;

    public function definition(): array
    {
        $subjects = ['Mathematics', 'English', 'Science', 'History', 'Geography', 'Computer Science', 'Physics', 'Chemistry', 'Biology'];
        $terms = ['Spring', 'Summer', 'Fall', 'Winter'];
        $examTypes = ['midterm', 'final', 'quiz', 'assignment'];

        return [
            'student_id' => Student::factory(),
            'subject' => $this->faker->randomElement($subjects),
            'marks_obtained' => $this->faker->numberBetween(30, 100),
            'total_marks' => 100,
            'term' => $this->faker->randomElement($terms),
            'exam_type' => $this->faker->randomElement($examTypes),
            'remarks' => $this->faker->optional(0.5)->sentence(),
        ];
    }
}
