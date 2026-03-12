<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\SchoolClass;
use App\Models\Grade;
use App\Models\Attendance;
use App\Models\Invoice;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Subject;
use App\Models\ExamSchedule;
use App\Models\Announcement;
use App\Models\Message;
use App\Models\TeacherAttendance;
use App\Models\Payroll;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user (only if not exists)
        $admin = User::firstOrCreate(
            ['email' => 'admin@school.com'],
            [
                'name' => 'Admin User',
                'email_verified_at' => now(),
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'admin',
                'remember_token' => \Illuminate\Support\Str::random(10),
            ]
        );

        // IMPORTANT: Ensure admin user has correct role (migration default may override)
        $admin->update(['role' => 'admin']);

        // Skip if data already seeded
        if (User::where('role', 'teacher')->count() >= 5) {
            $this->command->info('Database already seeded with test data!');
            return;
        }

        // Create 5 teachers
        $teachers = [];
        for ($i = 0; $i < 5; $i++) {
            $user = User::factory()->create([
                'role' => 'teacher',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]);
            
            $teacher = Teacher::factory()->create(['user_id' => $user->id]);
            $teachers[] = $teacher;
        }

        // Create 5 classes with teachers
        $classes = [];
        foreach ($teachers as $teacher) {
            $class = SchoolClass::factory()->create(['teacher_id' => $teacher->id]);
            $classes[] = $class;
        }

        // Create 50 students
        $students = [];
        for ($i = 0; $i < 50; $i++) {
            $user = User::factory()->create([
                'role' => 'student',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]);
            
            $student = Student::factory()->create([
                'user_id' => $user->id,
                'class_id' => $classes[array_rand($classes)]->id,
            ]);
            $students[] = $student;
        }

        // Create grades for students
        foreach ($students as $student) {
            Grade::factory(8)->create(['student_id' => $student->id]);
        }

        // Create attendance records for students
        foreach ($students as $student) {
            Attendance::factory(20)->create([
                'student_id' => $student->id,
                'class_id' => $student->class_id,
            ]);
        }

        // Create invoices for students
        foreach ($students as $student) {
            Invoice::factory(2)->create([
                'student_id' => $student->id,
                'class_id' => $student->class_id,
            ]);
        }

        // Create books
        $books = Book::factory(20)->create();

        // Create loans for students
        foreach (array_slice($students, 0, 25) as $student) {
            Loan::factory(3)->create([
                'student_id' => $student->id,
                'book_id' => $books->random()->id,
            ]);
        }

        // Create subjects
        $subjects = Subject::factory(10)->create();

        // Create exam schedules
        foreach ($classes as $class) {
            ExamSchedule::factory(5)->create([
                'class_id' => $class->id,
                'subject_id' => $subjects->random()->id,
            ]);
        }

        // Create announcements
        $users = User::all();
        Announcement::factory(15)->create([
            'published_by' => $users->random()->id,
        ]);

        // Create messages
        Message::factory(30)->create();

        // Create teacher attendance records
        foreach ($teachers as $teacher) {
            TeacherAttendance::factory(20)->create([
                'teacher_id' => $teacher->id,
            ]);
        }

        // Create payroll records for teachers
        foreach ($teachers as $teacher) {
            // Get this month attendance
            $attendance = TeacherAttendance::where('teacher_id', $teacher->id)
                ->whereYear('attendance_date', now()->year)
                ->whereMonth('attendance_date', now()->month)
                ->get();

            $presentDays = $attendance->where('status', 'present')->count();
            $leaveDays = $attendance->where('status', 'leave')->count();
            
            $perDayRate = $teacher->salary / 26;
            $grossSalary = ($perDayRate * $presentDays) + ($leaveDays > 0 ? $perDayRate * min($leaveDays, 5) : 0);

            Payroll::factory()->create([
                'teacher_id' => $teacher->id,
                'year' => now()->year,
                'month' => now()->month,
                'base_salary' => $teacher->salary,
                'present_days' => $presentDays,
                'leave_days' => $leaveDays,
                'gross_salary' => $grossSalary,
                'net_salary' => $grossSalary,
                'status' => 'pending',
            ]);
        }

        $this->command->info('Database seeded successfully with test data!');
    }
}

