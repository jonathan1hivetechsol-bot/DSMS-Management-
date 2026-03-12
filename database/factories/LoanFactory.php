<?php

namespace Database\Factories;

use App\Models\Loan;
use App\Models\Book;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Loan>
 */
class LoanFactory extends Factory
{
    protected $model = Loan::class;

    public function definition(): array
    {
        $loanedAt = $this->faker->dateTimeBetween('-30 days', 'now');
        $dueAt = \Carbon\Carbon::instance($loanedAt)->addDays(14);
        
        return [
            'book_id' => Book::factory(),
            'student_id' => Student::factory(),
            'loaned_at' => $loanedAt,
            'due_at' => $dueAt,
            'returned_at' => $this->faker->optional(0.4)->dateTimeBetween($loanedAt, $dueAt),
        ];
    }
}
