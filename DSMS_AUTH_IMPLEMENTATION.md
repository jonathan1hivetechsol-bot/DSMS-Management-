# DSMS Authentication Implementation Guide

## Overview

This document provides a complete guide to the Distributed School Management System (DSMS) enterprise-grade authentication and authorization framework implemented in the Lahomes project.

## Authentication Architecture

### Flow Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                    USER ACCESS                              │
└─────────────────────┬───────────────────────────────────────┘
                      ↓
┌─────────────────────────────────────────────────────────────┐
│            /portal-login (Portal Selection)                  │
│  Choose: Admin Portal | Teacher Portal | Student Portal     │
└─────────────────────┬───────────────────────────────────────┘
                      ↓
┌─────────────────────────────────────────────────────────────┐
│         Role-Specific Login                                  │
│  /portal/admin/login                                        │
│  /portal/teacher/login                                      │
│  /portal/student/login                                      │
└─────────────────────┬───────────────────────────────────────┘
                      ↓
┌─────────────────────────────────────────────────────────────┐
│         Database Authentication (Bcrypt Hash)               │
│  Verify credentials against users table                     │
│  Log login attempt for audit trail                          │
└─────────────────────┬───────────────────────────────────────┘
                      ↓ Success
┌─────────────────────────────────────────────────────────────┐
│         Portal Selection (/portal)                           │
│  After login, user selects which portal to access           │
│  Based on their role (admin/teacher/student)                │
└─────────────────────┬───────────────────────────────────────┘
                      ↓
┌─────────────────────────────────────────────────────────────┐
│         Role-Based Dashboard                                │
│  Access resources based on authorization gates              │
└─────────────────────────────────────────────────────────────┘
```

## Key Components

### 1. Controllers

#### AdminLoginController
- **Path**: `app/Http/Controllers/Auth/AdminLoginController.php`
- **Methods**: 
  - `show()` - Display admin login form
  - `store()` - Process admin authentication
  - `logout()` - Handle admin logout with logging
- **Features**:
  - Role verification (only admins allowed)
  - Audit logging on success/failure
  - Session regeneration for security

#### TeacherLoginController
- **Path**: `app/Http/Controllers/Auth/TeacherLoginController.php`
- **Similar structure to AdminLoginController**

#### StudentLoginController
- **Path**: `app/Http/Controllers/Auth/StudentLoginController.php`
- **Similar structure to AdminLoginController**

### 2. Routes

Located in `routes/auth.php`:

```php
// Portal role selection (pre-login)
GET  /portal-login → portal.selector.view

// Role-specific login routes
GET  /portal/admin/login → AdminLoginController@show (portal.admin.login)
POST /portal/admin/login → AdminLoginController@store (portal.admin.login.store)

GET  /portal/teacher/login → TeacherLoginController@show (portal.teacher.login)
POST /portal/teacher/login → TeacherLoginController@store (portal.teacher.login.store)

GET  /portal/student/login → StudentLoginController@show (portal.student.login)
POST /portal/student/login → StudentLoginController@store (portal.student.login.store)

// Post-authentication portal selection
GET  /portal → PortalSelectorController@show (portal.show)
GET  /portal/{portal} → PortalSelectorController@select (portal.select)
```

### 3. Views

#### `resources/views/auth/portal-selector-login.blade.php`
- **Purpose**: Pre-login portal selection (choose role before login)
- **Shows**: 3 cards for Admin, Teacher, Student
- **Links to**: Role-specific login pages

#### `resources/views/auth/admin-login.blade.php`
- **Purpose**: Admin-specific login form
- **Features**:
  - Back to Portal Navigation button
  - Role verification in form submission
  - Error display with dismissible alerts
  - Red border (danger) styling

#### `resources/views/auth/teacher-login.blade.php`
- **Purpose**: Teacher-specific login form
- **Features**: Same as admin, but with blue styling

#### `resources/views/auth/student-login.blade.php`
- **Purpose**: Student-specific login form
- **Features**: Same as admin, but with green styling

## Security Features

### 1. Password Security

✅ **Implemented**:
- All passwords stored as bcrypt hashes
- Never stored in plain text
- `Hash::make()` used for creation
- `Hash::check()` used for verification

❌ **Not Implemented** (in development):
- Default password is "password" for all test users
- **⚠️ MUST BE CHANGED BEFORE PRODUCTION**

### 2. Authentication

✅ **Features**:
- Session-based authentication
- Token regeneration after login
- Session invalidation on logout
- Role verification before dashboard access

### 3. Authorization

✅ **Features**:
- Role-based access control (Admin/Teacher/Student)
- Authorization gates for features
- Policy-based authorization for resources
- Permission checking in controllers

### 4. Audit Logging

✅ **Implemented**:
```php
Log::info('Admin login successful', ['user_id' => $user->id, 'email' => $user->email]);
Log::info('Teacher login successful', ['user_id' => $user->id, 'email' => $user->email]);
Log::info('Admin logout', ['user_id' => $user->id]);
```

### 5. Navigation

✅ **Back to Portal Button** on all role-specific login pages:
```blade
<a href="{{ route('portal.show') }}" class="btn btn-sm btn-outline-secondary">
    <i class="ri-arrow-left-line"></i> Back to Portal Selection
</a>
```

## Testing Authentication

### Test Credentials

```
Development Only - Change before production!

Admin:
  Email: admin@school.com
  Password: password
  
Teachers (5 total):
  Check database or QUICK_CREDENTIALS.md
  Password: password (same for all)
  
Students (50 total):
  Check database or QUICK_CREDENTIALS.md
  Password: password (same for all)
```

### Testing Steps

1. **Navigate to Portal Selection**
   ```
   GET /portal-login
   ```

2. **Select Admin Portal**
   ```
   Click "Admin Portal" card
   Redirects to: GET /portal/admin/login
   ```

3. **Enter Credentials**
   ```
   Email: admin@school.com
   Password: password
   Click "Sign In to Admin Panel"
   ```

4. **Verify Authentication**
   - If success: Redirect to `/portal` (portal.show)
   - If fail: Show error message
   - Check logs: `storage/logs/laravel.log`

5. **Access Dashboard**
   ```
   From /portal, select Admin Portal
   Redirects to: /dashboards/analytics
   ```

## Production Checklist

### Before Deploying to Production

- [ ] Change all default passwords
- [ ] Enable HTTPS/SSL
- [ ] Configure secure cookie settings
- [ ] Set up email notifications
- [ ] Enable rate limiting on login attempts
- [ ] Configure firewall rules
- [ ] Set up audit logging
- [ ] Enable 2FA/MFA
- [ ] Configure password reset flow
- [ ] Test all authentication flows
- [ ] Review security logs
- [ ] Document security procedures

### Configuration Files

Update in `config/auth.php`:
```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
        'session_timeout' => 60, // 60 minutes
    ],
],

'passwords' => [
    'users' => [
        'provider' => 'users',
        'table' => 'password_resets',
        'expire' => 60, // 60 minutes
        'throttle' => 60, // Throttle in seconds
    ],
],
```

### Environment Variables

Update in `.env`:
```bash
# Production
APP_ENV=production
APP_DEBUG=false
SESSION_LIFETIME=60
SESSION_SECURE_COOKIES=true
SESSION_HTTP_ONLY=true

# Database
DB_CONNECTION=mysql
DB_HOST=your-host
DB_DATABASE=dsms_production
DB_USERNAME=secure-user
DB_PASSWORD=very-secure-password-here
```

## Troubleshooting

### Login Issues

#### Issue: "Unauthorized access. Admin credentials required"
- **Cause**: User is trying to login with wrong role credentials
- **Solution**: Verify user role in database
```sql
SELECT id, name, email, role FROM users WHERE email = 'user@example.com';
```

#### Issue: Session expires too quickly
- **Cause**: SESSION_LIFETIME too short
- **Solution**: Update in `.env`: `SESSION_LIFETIME=120`

#### Issue: Cannot go back to portal selection
- **Cause**: Missing back button or incorrect route
- **Solution**: Verify `portal.show` route is reachable

### Password Issues

#### Issue: Password reset not working
- **Cause**: Email configuration not set up
- **Solution**: Configure mail settings in `.env` and `config/mail.php`

#### Issue: Bcrypt hash validation fails
- **Cause**: BCRYPT_ROUNDS mismatch or corruption
- **Solution**: Check `BCRYPT_ROUNDS=12` in `.env`

## API References

### AuthenticatedSessionController (Legacy)
- Still available at `/login` for backward compatibility
- Redirects to `/portal-login` if unauthenticated
- Existing code compatibility maintained

### PortalSelectorController
- **Routes**: `/portal` GET and `/portal/{portal}` GET
- **Purpose**: Post-login portal selection
- **Usage**: User selects which portal to enter after authentication

## Code Examples

### Login in Custom Code

```php
use Illuminate\Support\Facades\Auth;

// Check if user is authenticated
if (Auth::check()) {
    $user = Auth::user();
    echo "Logged in as: " . $user->email;
}

// Get user role
$role = Auth::user()->role; // 'admin', 'teacher', or 'student'

// Check authorization
if (Auth::user()->can('manage_students')) {
    // User has permission
}
```

### Redirect to Login

```php
// In controller
if (!Auth::check()) {
    return redirect()->route('portal.selector.view');
}

// In middleware
Route::middleware('auth')->group(function () {
    // Protected routes
});
```

### Logout

```php
// In Blade template
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Logout</button>
</form>

// Or redirect to specific logout
<a href="{{ route('portal.admin.logout') }}">Admin Logout</a>
```

## Additional Resources

- **DSMS_ARCHITECTURE.md** - Main architecture rules
- **ROLES_AND_PERMISSIONS.md** - Role-based access control
- **QUICK_CREDENTIALS.md** - Test credentials (dev only)
- **Laravel Auth Documentation** - https://laravel.com/docs/authentication

---

**Last Updated**: March 9, 2026
**Status**: Implementation Complete for DSMS Phase 1
