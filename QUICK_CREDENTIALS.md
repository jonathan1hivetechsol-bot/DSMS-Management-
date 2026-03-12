# 🎯 QUICK CREDENTIALS REFERENCE

⚠️ **⚠️ ⚠️ DEVELOPMENT ONLY - DO NOT USE IN PRODUCTION ⚠️ ⚠️ ⚠️**

These are **test credentials** for development and testing purposes ONLY. 
Before deploying to production:
1. ✅ Change all passwords to unique, secure credentials (min 12 characters)
2. ✅ Use admin panel for credential management
3. ✅ Enable multi-factor authentication (2FA)
4. ✅ Document all credentials in secure password manager
5. ✅ Audit all user accounts regularly

---

## ADMIN
```
📧 Email:    admin@school.com
🔑 Password: password
✅ Role:     ADMIN (Full Access)
⚠️  Status:  CHANGE THIS BEFORE PRODUCTION
```

---

## TEACHERS (5 Total)
```
🔑 Password: password (Same for ALL)
📧 Email:    [RANDOM - Check Database]
✅ Role:     TEACHER
⚠️  Status:  CHANGE THESE BEFORE PRODUCTION

To find teacher emails:
→ Login as admin
→ Go to Users/Teachers table
→ Filter role = "teacher"
→ See all teacher emails

Example Teachers in Database:
  Muhammad Ali Khan (random-email-1@example.com)
  Hassan Ahmed (random-email-2@example.com)
  Omar Khan (random-email-3@example.com)
  Faisal Ahmed (random-email-4@example.com)
  Imran Khan (random-email-5@example.com)
```

---

## STUDENTS (50 Total)
```
🔑 Password: password (Same for ALL)
📧 Email:    [RANDOM - Check Database]
✅ Role:     STUDENT
⚠️  Status:  CHANGE THESE BEFORE PRODUCTION

To find student emails:
→ Login as admin
→ Database → Users Table
→ Filter role = "student"
→ See all 50 student emails

Example Students in Database:
  Aisha Khan (random-email-51@example.com)
  Zainab Ahmed (random-email-52@example.com)
  Fatima Hassan (random-email-53@example.com)
  ... (47 more students)
```

---

## 🔐 Production Credential Management

### Create Secure Credentials

Use a password generator for production:
```bash
# Linux/Mac
openssl rand -base64 12

# Or use Laravel
php artisan tinker
> Str::random(16)
```

### Update User Passwords (Admin Panel)

```
Profile Settings → User Management → Select User → Change Password
```

### Require Password Change on First Login

Add to `AuthenticatedSessionController@store`:
```php
if (!$user->password_changed_at) {
    return redirect()->route('password.change.force');
}
```

### Enable Multi-Factor Authentication

Implement in `config/auth.php`:
```php
'mfa' => [
    'enabled' => env('MFA_ENABLED', true),
    'methods' => ['email', 'sms', 'authenticator'],
],
```

---

## 🚫 What NOT to Do

❌ Don't commit passwords to version control
❌ Don't use the same password for multiple accounts
❌ Don't share credentials via email or chat
❌ Don't store passwords in plain text files
❌ Don't use weak passwords (password, 123456, admin)

---

## ✅ What TO Do

✅ Use strong, unique passwords (16+ characters recommended)
✅ Store credentials in secure password manager (LastPass, 1Password, Vault)
✅ Rotate passwords every 90 days
✅ Enable audit logging for all logins
✅ Monitor failed login attempts
✅ Use HTTPS/SSL for all connections
✅ Enable rate limiting on login endpoints
✅ Keep logs for compliance/auditing

---

## 📋 Migration Checklist for Production

Before going live with this system:

- [ ] Changed admin@school.com password
- [ ] Generated unique passwords for all teachers
- [ ] Generated unique passwords for all students
- [ ] Configured email notifications
- [ ] Set up activity logging
- [ ] Enabled HTTPS/SSL
- [ ] Configured firewall rules
- [ ] Set up backup procedures
- [ ] Documented security procedures
- [ ] Trained admin staff
- [ ] Set up monitoring/alerts
- [ ] Created disaster recovery plan

---

**Last Updated**: March 9, 2026
**Status**: Development Credentials (⚠️ Change before production)
**Related Documentation**: DSMS_ARCHITECTURE.md, DSMS_AUTH_IMPLEMENTATION.md
```

---

## ⚡ FASTEST WAY TO GET ALL EMAILS

### Option 1: Using Artisan Tinker (Easiest)
```bash
cd Lahomes
php artisan tinker
User::select('id', 'name', 'email', 'role')->get();
```

### Option 2: SQL Query
```sql
SELECT id, name, email, role FROM users;
```

### Option 3: Login & Click
- Login as: admin@school.com / password
- Go to Admin → Users or Teachers
- View all emails listed

---

## 📊 USER BREAKDOWN

| Role    | Count | Email Pattern | Password |
|---------|-------|---------------|----------|
| Admin   | 1     | admin@school.com | password |
| Teachers| 5     | Random        | password |
| Students| 50    | Random        | password |
| **Total** | **56** | - | password |

---

## 🎓 CLASS DISTRIBUTION

```
Class 1  → Teacher 1 (4 subjects) → ~10 Students
Class 2  → Teacher 2 (4 subjects) → ~10 Students
Class 3  → Teacher 3 (4 subjects) → ~10 Students
Class 4  → Teacher 4 (4 subjects) → ~10 Students
Class 5  → Teacher 5 (4 subjects) → ~10 Students
```

---

## 🔒 ADMIN-ONLY ACTIONS

Only admin@school.com / password can:
- ✅ Create/edit/delete users
- ✅ Change user roles
- ✅ View all data
- ✅ Access system settings
- ✅ Manage all records

---

## 📝 NOTES

- ✓ **All passwords** = `password`
- ✓ **Emails are RANDOM** (use database to find them)
- ✓ **50 students** divided into **5 classes** (10 each)
- ✓ **Test data fully populated** (grades, attendance, invoices, etc.)
- ✓ **Pakistani names** used for authenticity

---

**Status: ✅ System Ready to Use!**
