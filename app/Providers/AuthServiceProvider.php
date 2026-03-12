<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Grade;
use App\Models\Message;
use App\Models\SchoolClass;
use App\Models\Invoice;
use App\Models\Attendance;
use App\Models\TeacherAttendance;
use App\Models\Payroll;
use App\Models\StudentLeave;
use App\Policies\StudentPolicy;
use App\Policies\TeacherPolicy;
use App\Policies\GradePolicy;
use App\Policies\MessagePolicy;
use App\Policies\SchoolClassPolicy;
use App\Policies\InvoicePolicy;
use App\Policies\AttendancePolicy;
use App\Policies\TeacherAttendancePolicy;
use App\Policies\PayrollPolicy;
use App\Policies\StudentLeavePolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Student::class => StudentPolicy::class,
        Teacher::class => TeacherPolicy::class,
        Grade::class => GradePolicy::class,
        Message::class => MessagePolicy::class,
        SchoolClass::class => SchoolClassPolicy::class,
        Invoice::class => InvoicePolicy::class,
        Attendance::class => AttendancePolicy::class,
        TeacherAttendance::class => TeacherAttendancePolicy::class,
        Payroll::class => PayrollPolicy::class,
        StudentLeave::class => StudentLeavePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Allow admins to do everything (before all other gates)
        Gate::before(function ($user, $ability) {
            if ($user->role === 'admin') {
                return true;
            }
        });

        // ============================================
        // ROLE-BASED GATES
        // ============================================

        // Admin Role Gates
        Gate::define('admin_only', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('admin_or_teacher', function ($user) {
            return in_array($user->role, ['admin', 'teacher']);
        });

        Gate::define('admin_or_principal', function ($user) {
            return in_array($user->role, ['admin', 'teacher']);
        });

        // Teacher Role Gates
        Gate::define('teacher_only', function ($user) {
            return $user->role === 'teacher';
        });

        Gate::define('teacher_or_admin', function ($user) {
            return in_array($user->role, ['teacher', 'admin']);
        });

        // Student Role Gates
        Gate::define('student_only', function ($user) {
            return $user->role === 'student';
        });

        // ============================================
        // FEATURE-BASED GATES
        // ============================================

        // Student Management
        Gate::define('manage_students', function ($user) {
            return in_array($user->role, ['admin', 'teacher']);
        });

        Gate::define('view_all_students', function ($user) {
            return in_array($user->role, ['admin', 'teacher']);
        });

        // Teacher Management
        Gate::define('manage_teachers', function ($user) {
            return $user->role === 'admin';
        });

        // Attendance Management
        Gate::define('manage_attendance', function ($user) {
            return in_array($user->role, ['admin', 'teacher']);
        });

        Gate::define('view_attendance_reports', function ($user) {
            return in_array($user->role, ['admin', 'teacher']);
        });

        // Grade Management
        Gate::define('manage_grades', function ($user) {
            return in_array($user->role, ['admin', 'teacher']);
        });

        Gate::define('view_all_grades', function ($user) {
            return in_array($user->role, ['admin', 'teacher']);
        });

        // Invoice/Fee Management
        Gate::define('manage_invoices', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('view_all_invoices', function ($user) {
            return in_array($user->role, ['admin', 'teacher']);
        });

        // Payroll Management
        Gate::define('manage_payroll', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('view_payroll', function ($user) {
            return $user->role === 'admin';
        });

        // Teacher Attendance Management
        Gate::define('manage_teacher_attendance', function ($user) {
            return in_array($user->role, ['admin', 'teacher']);
        });

        // Leave Management
        Gate::define('approve_leaves', function ($user) {
            return in_array($user->role, ['admin', 'teacher']);
        });

        Gate::define('request_leave', function ($user) {
            return in_array($user->role, ['student', 'teacher', 'admin']);
        });

        // Class Management
        Gate::define('manage_classes', function ($user) {
            return $user->role === 'admin';
        });

        // Announcement Management
        Gate::define('create_announcements', function ($user) {
            return in_array($user->role, ['admin', 'teacher']);
        });

        Gate::define('manage_announcements', function ($user) {
            return $user->role === 'admin';
        });

        // Book/Library Management
        Gate::define('manage_library', function ($user) {
            return in_array($user->role, ['admin', 'teacher']);
        });

        Gate::define('manage_loans', function ($user) {
            return in_array($user->role, ['admin', 'teacher']);
        });

        // Messaging
        Gate::define('message_anyone', function ($user) {
            return in_array($user->role, ['admin', 'teacher']);
        });

        // WhatsApp Alerts
        Gate::define('manage_alerts', function ($user) {
            return $user->role === 'admin';
        });

        // System Settings
        Gate::define('manage_settings', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('view_reports', function ($user) {
            return in_array($user->role, ['admin', 'teacher']);
        });
    }
}
