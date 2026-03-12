# 🚀 LAHOMES System Flow & Architecture Guide

## Complete User Journey Map

### 1️⃣ **INITIAL ACCESS (Unauthenticated User)**
```
User visits: http://localhost:8000/
    ↓
Redirects to: /portal-login (Route: portal.selector.view)
    ↓
Shows: Portal Selection Page (3 cards: Admin/Teacher/Student)
    ↓
User clicks one: Goes to /portal/{role}/login
```

### 2️⃣ **LOGIN PHASE**
```
Route: GET /portal/admin/login → AdminLoginController::show()
    ↓
Shows: Admin Login Form (email + password)
    ↓
User submits: POST /portal/admin/login → AdminLoginController::store()
    ↓
Validates:
  - Email/Password authentication (bcrypt)
  - User role must be 'admin' (else: 403 Unauthorized)
  - Session regeneration for security
    ↓
Success: Redirects to → /dashboards/analytics (dashboard)
Failure: Back to login form with error message
```

### 3️⃣ **DASHBOARD & NAVIGATION**
```
Route: GET /dashboards/analytics (protected with 'auth' middleware)
    ↓
Controller: RoutingController::secondLevel()
    ↓
Shows: Analytics Dashboard with Sidebar Navigation
    ↓
Sidebar Items (Role-based):
  - ADMIN: All features (Students, Teachers, Classes, Attendance, Grades, etc.)
  - TEACHER: Teaching features (Attendance, Grades, Classes) + profile
  - STUDENT: Self-service only (View grades, attendance, announcements)
```

### 4️⃣ **AUTHORIZATION SYSTEM**
```
Three-Layer Authorization:

Layer 1: MIDDLEWARE (Route Protection)
  - 'auth': User must be logged in
  - 'guest': User must NOT be logged in

Layer 2: GATES (Role-Based)
  - admin_only, teacher_only, student_only
  - manage_students, manage_teachers, etc.
  - Built in: AuthServiceProvider.php
  - Usage: if (Gate::denies('manage_students')) abort(403);

Layer 3: POLICIES (Model-Based)
  - StudentPolicy, TeacherPolicy, etc.
  - Methods: view(), create(), update(), delete(), approve(), etc.
  - Auto-authorization: $this->authorize('view', $student);

ADMIN SUPER-BYPASS:
  - Gate::before() grants 'admin' role full access to everything
  - Admins bypass all policies and gates
```

### 5️⃣ **PROPER ROLE SYSTEM**
```
System has 3 VALID roles ONLY:
  1. admin     - Full system access
  2. teacher   - Educational management + own profile
  3. student   - Self-service + view own data

Invalid Roles (REMOVED from system):
  ❌ principal   (use: admin)
  ❌ finance     (use: admin)
  ❌ staff       (use: admin)

Check User Role in DB:
  php artisan tinker
  User::find(1)->role  // Should return: admin, teacher, or student
```

---

## ✅ CRITICAL FIXES APPLIED

### Fix #1: User Model Relationships
**Issue**: User model had no relationships to Student/Teacher models
**Fixed**: Added:
```php
public function student() { return $this->hasOne(Student::class); }
public function teacher() { return $this->hasOne(Teacher::class); }
```
**Impact**: ProfileController, StudentLeaveController, InvoiceController now work

### Fix #2: PayrollPolicy Invalid Roles
**Issue**: Used non-existent roles: 'principal', 'finance'
**Fixed**: Changed all to use 'admin' role only
**Impact**: Payroll management now accessible to admins

### Fix #3: Admin Authorization Gate Bypass
**Issue**: Admins were getting 403 on some actions
**Fixed**: Added Gate::before() to AuthServiceProvider
```php
Gate::before(function ($user, $ability) {
    if ($user->role === 'admin') return true;
});
```
**Impact**: All admins now have full system access

---

## 📋 AUTHORIZATION REFERENCE

### Feature Access Matrix

| Feature | Admin | Teacher | Student |
|---------|-------|---------|---------|
| Manage Students | ✅ | ✅* | ❌ |
| Manage Teachers | ✅ | ❌ | ❌ |
| Manage Classes | ✅ | ❌ | ❌ |
| Attendance | ✅ | ✅ | ❌ |
| Grades | ✅ | ✅ | ✅** |
| Invoices | ✅ | ❌ | ❌ |
| Payroll | ✅ | ❌ | ❌ |
| Request Leaves | ✅ | ✅ | ✅ |
| Approve Leaves | ✅ | ✅ | ❌ |
| WhatsApp Alerts | ✅ | ❌ | ❌ |
| Messages | ✅ | ✅ | ✅ |
| View Profile | ✅ | ✅ | ✅ |
| Edit Profile | ✅ | ✅ | ✅ |

*Teachers can only manage attendance, not student creation
**Students can only view their own grades

---

## 🔧 TESTING THE SYSTEM

### Test Admin Access
```
1. Login with: admin@school.com / password
2. Should see: Full dashboard with all sidebar items
3. Try accessing: /students, /teachers, /payroll → All should work
```

### Test Teacher Access
```
1. Login: Get any teacher email from /students page
2. Email format: Usually "firstname.lastname@school.com"
3. Password: Always "password"
4. Should see: Only teaching-related features
5. Try: /payroll → Should get 403 Unauthorized
```

### Test Student Access
```
1. Login: Get any student email from /students page
2. Password: Always "password"
3. Should see: Only own data (grades, attendance)
4. Try: /students → Should show only own record
5. Try: /manage-students → Should get 403 Unauthorized
```

---

## 🐛 TROUBLESHOOTING

### Issue: 403 Unauthorized on any action
**Cause**: User role not set correctly in database
**Fix**:
```bash
php artisan tinker
User::find(1)->update(['role' => 'admin'])
```

### Issue: User Relationships Error
**Cause**: User model didn't have student()/teacher() methods
**Fix**: Already applied - Now working ✅

### Issue: Student can't view own leaves
**Cause**: StudentLeavePolicy might not allow it
**Fix**: Already verified - Policy is correct ✅

### Issue: "Active Role Manager" showing multiple users
**Cause**: Sidebar query selection logic needs fixing
**Fix**: Will be addressed in next phase

---

## 📚 Key Files to Know

```
Core Authentication:
  routes/auth.php                    - All auth routes
  app/Http/Controllers/Auth/         - Login controllers
  app/Providers/AuthServiceProvider  - Gates & Policies

Core Routing:
  routes/web.php                     - Main routes
  app/Http/Controllers/RoutingController.php - Dynamic routing

Authorization:
  app/Policies/                      - Model-based authorization
  app/Providers/AuthServiceProvider  - Gates (role-based)

Dashboard:
  resources/views/dashboards/        - Dashboard templates
  resources/views/layouts/           - Layout templates
```

---

## ✅ Status: System Ready for Testing

All critical issues have been fixed. The system is now ready for full testing with proper role-based access control.

**Next Steps**:
1. ✅ Test admin functionality
2. ✅ Test teacher functionality  
3. ✅ Test student functionality
4. ⏭️ Fix HIGH priority issues (TeacherController authorization, etc.)
5. ⏭️ Fix MEDIUM priority issues (missing views, etc.)
