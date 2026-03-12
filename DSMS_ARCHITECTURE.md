# DSMS System Architecture Rules

## 1. Authentication Flow

Every request must follow this authentication sequence:

```
User Access
    ↓
Portal Selection (Admin/Teacher/Student)
    ↓
Role-Based Login (/portal/admin/login, /portal/teacher/login, /portal/student/login)
    ↓
Credential Verification (MySQL - Bcrypt Hashing)
    ↓
Role-Specific Dashboard
    ↓
Authorization Gates (Per Feature)
```

### Key Requirements:
- **No hardcoded default passwords** - Every user must have unique, secure credentials
- **All passwords must be hashed** using bcrypt (`Hash::make()`)
- **Credentials must be managed via Admin backend** only
- **Environment variables** for sensitive data (.env)
- **Default test credentials** are for development only and must be changed before production

## 2. Routing Structure

### Route Pattern: `/portal/:role/login`

```
GET  /portal                    → PortalSelectorController@show       (Portal Selection)
GET  /portal/admin              → AdminLoginController@show           (Admin Login Form)
GET  /portal/teacher            → TeacherLoginController@show         (Teacher Login Form)
GET  /portal/student            → StudentLoginController@show         (Student Login Form)
POST /portal/admin/login        → AdminLoginController@store          (Process Admin Login)
POST /portal/teacher/login      → TeacherLoginController@store        (Process Teacher Login)
POST /portal/student/login      → StudentLoginController@store        (Process Student Login)
```

### Middleware Protection:
- **Before Portal Selection**: No auth required
- **Role-Specific Login**: Route-specific middleware validates role against user
- **Dashboard/Resources**: `auth` middleware + role-based gates

## 3. Coding Standards

### Security Principles:
```php
// ✅ CORRECT: Hash passwords
$user->password = Hash::make($request->password);

// ❌ WRONG: Plain text passwords
$user->password = $request->password;

// ✅ CORRECT: Use authorization gates
if (auth()->user()->can('manage_students'))
```

### Code Organization:
- **Controllers**: Thin, focused on request handling
- **Services**: Business logic and data processing
- **Policies**: Authorization checks
- **Requests**: Form validation and sanitization
- **Models**: Database interactions

### UI/Navigation Standards:
```blade
<!-- Every authenticated page includes back navigation -->
<a href="{{ route('portal.show') }}" class="btn btn-secondary">
    ← Back to Portal Selection
</a>
```

## 4. Credential Management

### Development Environment:
- **Admin**: admin@school.com / password
- **Teachers**: Random emails / password (5 users)
- **Students**: Random emails / password (50 users)

### Production Environment:
- ❌ NEVER use default passwords
- ✅ Generate unique strong passwords (min 12 characters)
- ✅ Store in secure password manager
- ✅ Require password change on first login
- ✅ Implement multi-factor authentication

### Credential Documentation:
- Keep `QUICK\_CREDENTIALS.md` for **development reference only**
- Add warning: "⚠️ For development use only"
- Create separate admin panel for credential management
- Log all credential changes for audit trail

## 5. Database Security

### User Table Schema:
```sql
id, name, email, password (bcrypt), role, 
email_verified_at, created_at, updated_at,
last_login_at, password_changed_at
```

### Authorization Framework:
```php
// In AuthServiceProvider
Gate::define('manage_students', fn($user) => $user->role === 'admin' || $user->role === 'teacher');
Gate::define('manage_teachers', fn($user) => $user->role === 'admin');
Gate::define('manage_grades', fn($user) => $user->role === 'admin' || $user->role === 'teacher');
```

## 6. Error Handling & Audit Logging

### Login Attempts:
- Log all login attempts (success & failure)
- Lock account after 5 failed attempts
- Send email notification on suspicious activity

### Audit Trail:
```php
// Log important actions
Log::info('User login', ['user_id' => $user->id, 'role' => $user->role]);
Log::warning('Failed login attempt', ['email' => $request->email]);
```

## 7. Compliance Checklist

- [x] Portal selection before login
- [x] Role-based routing structure
- [x] Middleware verification
- [x] Password hashing (bcrypt)
- [x] Authorization gates
- [ ] "Back to Portal" navigation on all pages
- [ ] Audit logging for login attempts
- [ ] Environment-based credential handling
- [ ] Production security checklist

## 8. Testing Credentials

Use these ONLY in development:

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@school.com | password |
| Teacher | Check database | password |
| Student | Check database | password |

**Before deploying to production:**
1. [ ] Change all test passwords
2. [ ] Enable multi-factor authentication
3. [ ] Configure email notifications
4. [ ] Set up activity logging
5. [ ] Document security procedures

---

**Last Updated**: March 9, 2026  
**Status**: Implementation Phase
