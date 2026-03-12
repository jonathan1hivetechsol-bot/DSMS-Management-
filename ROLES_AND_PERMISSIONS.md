# School Management System - Roles & Permissions

## Overview
The system has three main roles with different levels of access:

---

## 1. ADMIN ROLE

**Access Level**: Full System Access

### Permissions:
- ✅ Full access to all students management (create, read, update, delete)
- ✅ Full access to all teachers management (create, read, update, delete)
- ✅ Full access to all classes management (create, read, update, delete)
- ✅ Full access to attendance (mark, view all, reports)
- ✅ Full access to grades (create, edit, delete, view all)
- ✅ Full access to invoices/fees (create, edit, delete, mark paid)
- ✅ Full access to payroll system (create, approve, mark paid)
- ✅ Full access to teacher attendance (create, mark, view all)
- ✅ Approve/reject student leaves
- ✅ Full access to library (books, loans)
- ✅ Create and manage announcements
- ✅ Create and send WhatsApp alerts
- ✅ Access to all reports and analytics
- ✅ System settings and configuration

### Gates Used:
```php
Gate::allows('admin_only')
Gate::allows('manage_students')
Gate::allows('manage_teachers')
Gate::allows('manage_classes')
Gate::allows('manage_invoices')
Gate::allows('manage_payroll')
Gate::allows('manage_alerts')
Gate::allows('manage_settings')
```

---

## 2. TEACHER ROLE

**Access Level**: Educational Content & Reporting

### Permissions:
- ✅ View all students in their classes
- ✅ Create and manage attendance records
- ✅ Create and manage grades for students
- ✅ View student leaves and approve/reject them
- ✅ View student attendance reports
- ✅ Access to teacher attendance system
- ✅ View payroll information
- ✅ View invoices (read-only)
- ✅ Create announcements
- ✅ Manage library (create books, manage loans)
- ✅ Send messages to students
- ✅ View reports and analytics
- ❌ Cannot create/delete students
- ❌ Cannot manage other teachers
- ❌ Cannot manage invoices (payment operations)
- ❌ Cannot manage payroll (finance operations)
- ❌ Cannot manage system settings
- ❌ Cannot send WhatsApp alerts

### Gates Used:
```php
Gate::allows('teacher_only')
Gate::allows('manage_attendance')
Gate::allows('manage_grades')
Gate::allows('approve_leaves')
Gate::allows('view_all_students')
Gate::allows('create_announcements')
Gate::allows('manage_loans')
```

---

## 3. STUDENT ROLE

**Access Level**: Self-Service Only

### Permissions:
- ✅ View own attendance records
- ✅ View own grades/report card
- ✅ View own invoices
- ✅ Request leaves (medical, personal, etc.)
- ✅ View own student leaves status
- ✅ View own library loans
- ✅ Send messages to teachers
- ✅ View announcements
- ❌ Cannot view other students' data
- ❌ Cannot create/edit attendance
- ❌ Cannot edit grades
- ❌ Cannot create invoices
- ❌ Cannot issue loans
- ❌ Cannot access teacher attendance
- ❌ Cannot access payroll
- ❌ Cannot manage announcements
- ❌ Cannot access reports

### Gates Used:
```php
Gate::allows('student_only')
Gate::allows('request_leave')
Gate::allows('message_anyone') // Can message teachers only
```

---

## Implementation Examples

### Using Gates in Controllers
```php
// Check if user is admin
if (Gate::denies('admin_only')) {
    abort(403, 'Unauthorized');
}

// Check if can manage attendance
if (Gate::denies('manage_attendance')) {
    abort(403, 'Unauthorized');
}

// Check if can approve leaves
if (Gate::allows('approve_leaves')) {
    // Show approval buttons
}
```

### Using Gates in Blade Templates
```blade
@can('admin_only')
    <!-- Only visible to admins -->
@endcan

@can('manage_grades')
    <!-- Only visible to admins and teachers -->
@endcan

@can('student_only')
    <!-- Only visible to students -->
@endcan

@cannot('manage_invoices')
    <!-- Visible to everyone except those who can manage invoices -->
@endcannot
```

### Using Policies
```php
// School Class Policy
$this->authorize('view', $class); // Teacher can view their class
$this->authorize('update', $class); // Only admin can update

// Attendance Policy
$this->authorize('create', Attendance::class);
$this->authorize('view', $attendance); // Student can view only their own

// Grade Policy
$this->authorize('create', Grade::class); // Only teachers/admins
```

---

## Data Isolation by Role

### Students See Only Their Own:
- Attendance records
- Grades/report cards
- Invoices
- Loans
- Student leaves
- Messages (sent to/from them)

### Teachers See:
- All students in their classes
- All attendance records (in their classes)
- All grades (they created or in their classes)
- All student leaves (to approve/reject)
- All messages (sent to/from them)
- Their own payroll information
- All invoices (read-only)

### Admins See:
- Everything in the system
- All reports and analytics
- System-wide statistics
- All financial transactions

---

## Role Hierarchy

```
ADMIN (Highest)
  ├── Full system access
  ├── Can do everything teachers can do
  └── Can do everything students can do

TEACHER (Middle)
  ├── Can manage educational content
  ├── Can approve student leaves
  ├── Can view all student data
  └── Can do everything students can do (for themselves)

STUDENT (Lowest)
  └── Can only see and manage own data
```

---

## Database Schema

User table includes `role` column with enum values:
- `admin` - System administrator
- `teacher` - Teacher/educator
- `student` - Student

```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin', 'teacher', 'student'),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

---

## How to Add New Permissions

To add a new permission, define a gate in `AuthServiceProvider.php`:

```php
Gate::define('my_new_permission', function ($user) {
    return $user->role === 'admin'; // or any logic you need
});
```

Then use it anywhere:
```blade
@can('my_new_permission')
    <!-- Content here -->
@endcan
```

---

## Testing Roles

Use these credentials to test different roles:

**Admin**
- Email: `admin@school.com`
- Password: `password`

**Teachers** (auto-generated from database)
- Look for users with role = 'teacher'
- Password: `password` (for all seeded users)

**Students** (auto-generated from database)
- Look for users with role = 'student'
- Password: `password` (for all seeded users)

---

## References

- Laravel Gates Documentation: https://laravel.com/docs/authorization#gates
- Laravel Policies Documentation: https://laravel.com/docs/authorization#creating-policies
- File: `app/Providers/AuthServiceProvider.php`
