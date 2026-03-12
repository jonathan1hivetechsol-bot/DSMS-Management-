# Lahomes School Management System - Comprehensive Codebase Analysis Report

**Analysis Date:** March 9, 2026  
**Status:** ⚠️ CRITICAL ISSUES FOUND - 13+ Issues Identified  
**Severity Breakdown:** 4 Critical | 5 High | 4 Medium

---

## 🔴 CRITICAL ISSUES (Blocking Functionality)

### 1. **User Model Missing Relationships to Student & Teacher**
**Location:** [app/Models/User.php](app/Models/User.php)  
**Severity:** 🔴 CRITICAL  
**Status:** BROKEN ❌

**What's Wrong:**
- User model has NO relationships defined to Student and Teacher models
- Multiple controllers use `Auth::user()->student` and `Auth::user()->teacher` which will fail
- This breaks entire student/teacher profile access chain

**Affected Controllers:**
- [ProfileController.php](app/Http/Controllers/ProfileController.php) - Lines 23-46 (dashboard, editProfile)
- [StudentLeaveController.php](app/Http/Controllers/StudentLeaveController.php) - Lines 31, 51, 70, 150, 160, etc.
- [InvoiceController.php](app/Http/Controllers/InvoiceController.php) - Lines 19-23
- [StudentController.php](app/Http/Controllers/StudentController.php) - Line 64 (students.show check)
- [TeacherController.php](app/Http/Controllers/TeacherController.php) - Used in update methods

**Error Scenario:**
```php
// This will ALWAYS return null, breaking features:
Auth::user()->student  // ❌ Null - no relationship defined
Auth::user()->teacher  // ❌ Null - no relationship defined
```

**Quick Fix:**
Add relationships to User model:
```php
public function student()
{
    return $this->hasOne(Student::class);
}

public function teacher()
{
    return $this->hasOne(Teacher::class);
}
```

**Tests to Verify:**
- Create student user, login, verify profile loads
- Create teacher user, login, verify profile loads
- Access student-leaves as student (currently broken)

---

### 2. **PayrollPolicy Using Non-Existent Roles**
**Location:** [app/Policies/PayrollPolicy.php](app/Policies/PayrollPolicy.php)  
**Severity:** 🔴 CRITICAL  
**Status:** BROKEN ❌

**What's Wrong:**
- PayrollPolicy checks for roles `'principal'` and `'finance'` which don't exist
- System only has `'admin'`, `'teacher'`, `'student'` roles
- ALL payroll access is blocked except super admins

**Code Issue:**
```php
// Lines 12-13, 21: Checks for roles that never exist
public function viewAny(User $user): bool
{
    return $user->role === 'admin' || $user->role === 'principal' || $user->role === 'finance';
}
```

**Impact:**
- Finance staff cannot access payroll
- Principals cannot approve payroll
- Teachers cannot view their own payroll

**Quick Fix:**
```php
// Change all role checks to use only valid roles:
public function viewAny(User $user): bool
{
    return $user->role === 'admin'; // Only admins can view payroll
}

public function create(User $user): bool
{
    return $user->role === 'admin';
}

public function update(User $user, Payroll $payroll): bool
{
    return $user->role === 'admin' && $payroll->status !== 'paid';
}

public function approve(User $user, Payroll $payroll): bool
{
    return $user->role === 'admin';
}
```

---

### 3. **PortalSelectorController Logic Error**
**Location:** [app/Http/Controllers/Auth/PortalSelectorController.php](app/Http/Controllers/Auth/PortalSelectorController.php)  
**Severity:** 🔴 CRITICAL  
**Status:** BROKEN ❌

**What's Wrong:**
- `show()` method logic is backwards: redirects authenticated users away
- `select()` method also rejects authenticated users
- These methods are unreachable or contradictory

**Code Issue:**
```php
// Line 18: Currently rejects authenticated users!
public function show()
{
    if (!Auth::check()) {  // ← Should this be Auth::check()?
        return redirect()->route('login');
    }
    // If already authenticated, this is unreachable!
    return view('auth.portal-selector');
}

// Lines 33-34: Same issue
public function select(Request $request, $portal)
{
    if (!Auth::check()) {  // ← Rejects authenticated users
        return redirect()->route('login');
    }
    // Rest of logic...
}
```

**Why It's Broken:**
- Route `portal.selector.view` is accessed by `guest` middleware
- But controller tries to require authentication
- Middleware conflict

**Quick Fix Option 1** (If for authenticated users):
```php
public function show()
{
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    return view('auth.portal-selector');
}
```

**Quick Fix Option 2** (If for guest selection):
```php
public function show()
{
    return view('auth.portal-selector');
}
```

---

### 4. **StudentLeavePolicy Contradicts Controller Implementation** 
**Location:** [app/Policies/StudentLeavePolicy.php](app/Policies/StudentLeavePolicy.php) vs [app/Http/Controllers/StudentLeaveController.php](app/Http/Controllers/StudentLeaveController.php)  
**Severity:** 🔴 CRITICAL  
**Status:** BROKEN ❌

**What's Wrong:**
- Policy `viewAny()` only allows admin/teacher (line 11)
- Controller `index()` allows students to view their own leaves (line 31-37)
- Contradiction causes either:
  - Policy prevents students from seeing their own leaves list, OR
  - Controller bypasses policy authorization

**Code Mismatch:**
```php
// StudentLeavePolicy.php:11 - BLOCKS students
public function viewAny(User $user): bool
{
    return $user->role === 'admin' || $user->role === 'teacher';
}

// StudentLeaveController.php:31 - ALLOWS students
$query = StudentLeave::with('student', 'approvedBy');
if (auth()->user()->role === 'student') {
    $studentId = auth()->user()->student?->id;
    $query->where('student_id', $studentId);
}
```

**Impact:**
- If policy is enforced: Students get 403 Forbidden on their own leaves
- If controller bypasses: Authorization gets inconsistent

**Quick Fix:**
Update StudentLeavePolicy:
```php
public function viewAny(User $user): bool
{
    return $user->role === 'admin' || $user->role === 'teacher' || $user->role === 'student';
}
```

---

## 🟠 HIGH SEVERITY ISSUES (Major Authorization Problems)

### 5. **TeacherController.index() Missing Authorization**
**Location:** [app/Http/Controllers/TeacherController.php](app/Http/Controllers/TeacherController.php#L16)  
**Severity:** 🟠 HIGH  
**Status:** VULNERABLE ⚠️

**What's Wrong:**
- `index()` method displays ALL teachers to ANYONE
- No authorization check or gate verification
- Violates principle of least privilege

**Code Issue:**
```php
public function index()
{
    $teachers = Teacher::with('user')->get();
    return view('teachers.index', compact('teachers'));
    // ❌ NO AUTHORIZATION CHECK!
}
```

**Impact:**
- Students can view list of all teachers
- Policy allows ANY authenticated user to view all teachers
- Consistent with TeacherPolicy but inconsistent with typical RBAC

**Quick Fix:**
```php
public function index()
{
    // Option 1: Only admin/teachers can see full list
    if (Gate::denies('view_all_students')) { // Reuse existing gate
        abort(403, 'Unauthorized');
    }
    
    $teachers = Teacher::with('user')->get();
    return view('teachers.index', compact('teachers'));
}
```

OR update TeacherPolicy to restrict access:
```php
public function viewAny(User $user): bool
{
    return $user->role === 'admin' || $user->role === 'teacher';
}
```

---

### 6. **StudentLeaveController.show() Missing Authorization**
**Location:** [app/Http/Controllers/StudentLeaveController.php](app/Http/Controllers/StudentLeaveController.php#L125)  
**Severity:** 🟠 HIGH  
**Status:** VULNERABLE ⚠️

**What's Wrong:**
- `show()` method has NO authorization check
- Any authenticated user could potentially view any leave record
- Violates RBAC principles

**Code Issue:**
```php
public function show(StudentLeave $studentLeave): View
{
    return view('student-leaves.show', [
        'leave' => $studentLeave->load('student', 'approvedBy'),
    ]);
    // ❌ NO AUTHORIZATION!
}
```

**Impact:**
- Teacher could view student's private leave reasons
- Students could view other students' leave requests
- No access control on sensitive data

**Quick Fix:**
```php
public function show(StudentLeave $studentLeave): View
{
    $this->authorize('view', $studentLeave);  // ← Add this
    
    return view('student-leaves.show', [
        'leave' => $studentLeave->load('student', 'approvedBy'),
    ]);
}
```

---

### 7. **InvoicePolicy Contradicts InvoiceController Gate Checks**
**Location:** [app/Policies/InvoicePolicy.php](app/Policies/InvoicePolicy.php) vs [app/Http/Controllers/InvoiceController.php](app/Http/Controllers/InvoiceController.php)  
**Severity:** 🟠 HIGH  
**Status:** INCONSISTENT ⚠️

**What's Wrong:**
- InvoicePolicy `create()` allows teachers
- InvoiceController `create()` only allows admins (via manage_invoices gate)
- Different authorization rules

**Code Mismatch:**
```php
// InvoicePolicy.php - Allows teachers
public function create(User $user): bool
{
    return $user->role === 'admin' || $user->role === 'teacher';
}

// InvoiceController.php - Only allows admins
public function create()
{
    if (Gate::denies('manage_invoices')) {  // manage_invoices = admin only
        abort(403, '...');
    }
}
```

**Impact:**
- Policy says teachers can create invoices
- Controller prevents teachers from creating invoices
- Inconsistent behavior

**Quick Fix:**
Align policy with controller intent:
```php
// InvoicePolicy.php - Update to admin-only
public function create(User $user): bool
{
    return $user->role === 'admin';  // Match controller
}
```

---

### 8. **BookController.index() Missing Authorization**
**Location:** [app/Http/Controllers/BookController.php](app/Http/Controllers/BookController.php#L12)  
**Severity:** 🟠 HIGH  
**Status:** VULNERABLE ⚠️

**What's Wrong:**
- `index()` has no authorization check
- Any authenticated user can view all books
- Should be public (library is shared) but inconsistent with other controllers

**Code Issue:**
```php
public function index()
{
    $books = Book::orderBy('title')->get();
    return view('books.index', compact('books'));
    // ❌ NO AUTHORIZATION CHECK
}
```

**Impact:**
- Policy allows any user to view books
- Consistent with library being shared resource
- But other controllers consistently check auth

**Note:** This may be intentional (shared library), but should be documented and consistent.

---

### 9. **Missing Middleware in Bootstrap**
**Location:** [bootstrap/app.php](bootstrap/app.php)  
**Severity:** 🟠 HIGH  
**Status:** INCOMPLETE ⚠️

**What's Wrong:**
- Middleware registration is empty
- `RedirectToRoleBasedLogin` middleware exists but never registered
- Middleware won't be applied to routes

**Code Issue:**
```php
->withMiddleware(function (Middleware $middleware) {
    //  ← EMPTY! No middleware registered
})
```

**Impact:**
- Middleware dependency but unused
- Portal-based login routing might not work as intended
- `RedirectToRoleBasedLogin` is defined but inactive

**Quick Fix:**
```php
->withMiddleware(function (Middleware $middleware) {
    // Register global middleware
    // Note: RedirectToRoleBasedLogin is currently only defined but not applied
})
```

---

## 🟡 MEDIUM SEVERITY ISSUES

### 10. **StudentLeaveController Destroy Method - Duplicate Authorization Logic**
**Location:** [app/Http/Controllers/StudentLeaveController.php](app/Http/Controllers/StudentLeaveController.php#L260)  
**Severity:** 🟡 MEDIUM  
**Status:** REDUNDANT ⚠️

**What's Wrong:**
- `destroy()` has conflicting authorization logic
- Policy authorization mixed with manual Gate check
- Creates confusion and potential bypass

**Code Issue:**
```php
public function destroy(StudentLeave $studentLeave): RedirectResponse
{
    $this->authorize('delete', $studentLeave);  // ← Uses policy
    
    // ❌ Then checks gate again redundantly:
    if (Gate::denies('admin_only') && auth()->user()->student?->id !== $studentLeave->student_id) {
        abort(403, 'You are not authorized to delete this leave request.');
    }
    
    // Then checks role again:
    if (auth()->user()->role === 'student') {
        $studentId = auth()->user()->student?->id;
        if (!$studentId || $studentLeave->student_id != $studentId) {
            return back()->with('error', '...');
        }
    }
}
```

**Issue:**
- Three separate authorization checks do the same thing
- Policy already determines authorization
- Manual checks are redundant

**Quick Fix - Use only policy:**
```python
public function destroy(StudentLeave $studentLeave): RedirectResponse
{
    $this->authorize('delete', $studentLeave);  // ← This is enough
    
    if ($studentLeave->status !== 'pending') {
        return back()->with('error', 'Only pending leaves can be deleted.');
    }
    
    $studentLeave->delete();
    return redirect()->route('student-leaves.index')
        ->with('success', 'Leave request deleted!');
}
```

---

### 11. **TeacherAttendancePolicy Missing Method**
**Location:** [app/Policies/TeacherAttendancePolicy.php](app/Policies/TeacherAttendancePolicy.php)  
**Severity:** 🟡 MEDIUM  
**Status:** INCOMPLETE ⚠️

**What's Wrong:**
- Policy file exists but is incomplete
- Missing methods for `show()`, `edit()` operations
- Route references `show` view that may not be authorized

**Code Issue:**
```php
// TeacherAttendanceController.php line 47:
public function show(TeacherAttendance $teacherAttendance)
{
    return view('teacher-attendance.show', compact('teacherAttendance'));
    // ❌ No authorization check
}
```

**Impact:**
- Any authenticated user can view any teacher's attendance
- No authorization enforcement

**Quick Fix - Add to policy:**
```php
public function show(User $user, TeacherAttendance $teacherAttendance): bool
{
    return $user->role === 'admin' || $user->role === 'teacher';
}
```

And apply in controller:
```php
public function show(TeacherAttendance $teacherAttendance)
{
    $this->authorize('view', $teacherAttendance);
    return view('teacher-attendance.show', compact('teacherAttendance'));
}
```

---

### 12. **Syntax Issue - Duplicate Comment in StudentLeaveController**
**Location:** [app/Http/Controllers/StudentLeaveController.php](app/Http/Controllers/StudentLeaveController.php#L258)  
**Severity:** 🟡 MEDIUM  
**Status:** STYLE ERROR

**What's Wrong:**
- Line 258 has duplicate opening comment markers

**Code Issue:**
```php
    /**
    /**  ← ❌ DUPLICATE /** - malformed
     * Delete leave (only pending)
     */
```

**Impact:**
- Minor - doesn't affect functionality
- Code smell/style issue
- IDE may flag as error

**Quick Fix:**
```php
    /**
     * Delete leave (only pending)
     */
```

---

### 13. **Missing View File - TeacherAttendance.show**
**Location:** Routes/Views mismatch  
**Severity:** 🟡 MEDIUM  
**Status:** INCOMPLETE ⚠️

**What's Wrong:**
- TeacherAttendanceController has `show()` method
- No corresponding view file exists in resources/views/teacher-attendance/

**Code Issue:**
```php
// TeacherAttendanceController.php line 47
public function show(TeacherAttendance $teacherAttendance)
{
    return view('teacher-attendance.show', compact('teacherAttendance'));
    // ❌ View 'teacher-attendance.show' doesn't exist
}
```

**Impact:**
- Route exists but calling it returns 404/ViewNotFound
- Teachers can't see detailed attendance records

**Quick Fix:**
Create the missing view at `resources/views/teacher-attendance/show.blade.php`

---

## 🟢 LOWER PRIORITY ISSUES & RECOMMENDATIONS

### 14. **WhatsAppAlertController - Missing Middleware Authorization**
**Location:** [app/Http/Controllers/WhatsAppAlertController.php](app/Http/Controllers/WhatsAppAlertController.php)  
**Severity:** 🟢 LOW  
**Status:** INCOMPLETE ⚠️

**What's Wrong:**
- Some methods check `manage_alerts` gate
- Other methods don't (e.g., `index()`)
- Inconsistent authorization pattern

**Code Issue:**
```php
public function index(Request $request): View
{
    // ❌ NO authorization check
    $query = WhatsAppAlert::with('template')->orderBy('created_at', 'desc');
}

public function createTemplate(): View
{
    if (Gate::denies('manage_alerts')) {  // ← Only here
        abort(403, '...');
    }
}
```

**Recommendation:**
Apply `manage_alerts` gate consistently across all WhatsAppAlertController methods.

---

### 15. **ProfileController - Missing Authorization on Show Methods**
**Location:** [app/Http/Controllers/ProfileController.php](app/Http/Controllers/ProfileController.php)  
**Severity:** 🟢 LOW  
**Status:** INCOMPLETE ⚠️

**What's Wrong:**
- `showStudent()` and `showTeacher()` authorize before loading
- But individual URL routes could be manipulated

**Code Pattern:**
```php
public function showStudent($studentId)
{
    $student = Student::findOrFail($studentId);
    $this->authorize('view', $student);  // ← Good, but late
}
```

**Recommendation:**
Use Route Model Binding with implicit authorization:
```php
Route::get('student/{student}', ...)->can('view', 'student');
```

---

## Summary Table 

| # | Issue | Severity | Type | Affected Components | Status |
|---|-------|----------|------|---------------------|--------|
| 1 | User → Student/Teacher relationships | 🔴 CRITICAL | Architecture | 5+ controllers | BROKEN |
| 2 | PayrollPolicy non-existent roles | 🔴 CRITICAL | Authorization | PayrollController | BROKEN |
| 3 | PortalSelectorController logic error | 🔴 CRITICAL | Authentication | Auth flow | BROKEN |
| 4 | StudentLeavePolicy vs Controller mismatch | 🔴 CRITICAL | Authorization | StudentLeaveController | BROKEN |
| 5 | TeacherController.index() no auth | 🟠 HIGH | Authorization | TeacherController | VULNERABLE |
| 6 | StudentLeaveController.show() no auth | 🟠 HIGH | Authorization | StudentLeaveController | VULNERABLE |
| 7 | InvoicePolicy vs Controller mismatch | 🟠 HIGH | Authorization | InvoiceController | INCONSISTENT |
| 8 | BookController.index() no auth | 🟠 HIGH | Authorization | BookController | VULNERABLE |
| 9 | Empty middleware registration | 🟠 HIGH | Architecture | bootstrap/app.php | INCOMPLETE |
| 10 | Destroy method redundant auth | 🟡 MEDIUM | Code Quality | StudentLeaveController | REDUNDANT |
| 11 | TeacherAttendancePolicy incomplete | 🟡 MEDIUM | Authorization | Attendance system | INCOMPLETE |
| 12 | Syntax - duplicate comment | 🟡 MEDIUM | Style | StudentLeaveController | STYLE ERROR |
| 13 | Missing TeacherAttendance.show view | 🟡 MEDIUM | Views | TeacherAttendanceController | MISSING |
| 14 | WhatsAppAlertController inconsistent auth | 🟢 LOW | Authorization | WhatsAppAlertController | INCOMPLETE |
| 15 | ProfileController late authorization | 🟢 LOW | Authorization | ProfileController | PATTERN |

---

## Recommended Fix Priority

**IMMEDIATE (Do First):**
1. Add User → Student/Teacher relationships
2. Fix PayrollPolicy roles
3. Fix StudentLeavePolicy contradictions
4. Fix PortalSelectorController logic

**SHORT TERM (This Week):**
5. Add authorization checks to TeacherController.index()
6. Add authorization to StudentLeaveController.show()
7. Resolve InvoicePolicy contradictions
8. Create missing TeacherAttendance.show view
9. Register middleware in bootstrap/app.php

**ONGOING:**
10. Add consistent authorization checks across all controllers
11. Refactor redundant authorization code
12. Complete policy implementations
13. Add missing views

---

## Testing Checklist

- [ ] Student user can access own profile after User relationship fix
- [ ] Teacher user can access own profile  
- [ ] Payroll page loads for admin (after PayrollPolicy fix)
- [ ] StudentLeavePolicy allows students to view own leaves
- [ ] TeacherController.index requires authorization
- [ ] StudentLeaveController.show requires authorization
- [ ] Created TeacherAttendance.show view displays correctly
- [ ] All WhatsApp actions check manage_alerts consistently

