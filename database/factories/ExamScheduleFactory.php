<?php

namespace Database\Factories;

use App\Models\ExamSchedule;
use App\Models\Subject;
use App\Models\SchoolClass;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExamSchedule>
 */
class ExamScheduleFactory extends Factory
{
    protected $model = ExamSchedule::class;

    public function definition(): array
    {
        return [
            'subject_id' => Subject::factory(),
            'class_id' => null, // Will be set manually or by seeder
            'exam_date' => $this->faker->dateTimeBetween('now', '+3 months'),
            'description' => null,
        ];
    }
}
