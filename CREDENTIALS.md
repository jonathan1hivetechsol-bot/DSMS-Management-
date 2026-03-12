# Lahomes - User Credentials & Access Guide

## 🔐 Master Admin Account

### Admin Login Credentials:
```
Email:    admin@school.com
Password: password
Role:     Admin (Full System Access)
```

**What Admin Can Do:**
- ✓ Manage all students, teachers, classes
- ✓ View all records and reports
- ✓ Create/edit/delete any data
- ✓ Access to attendance, grades, payroll, invoices
- ✓ WhatsApp alerts management
- ✓ Settings and configuration

---

## 👨‍🏫 Teacher Accounts (5 Teachers)

### How to Find Teacher Credentials:

Since teacher emails are randomly generated, you have **3 options**:

#### **Option 1: View in Database (Easiest)**
1. Open your database management tool (phpMyAdmin, DBeaver, etc.)
2. Go to `users` table
3. Filter by `role = 'teacher'`
4. You'll see all teacher emails

**Example teachers you might see:**
```
Email: muhammad.ali@example.com     | Password: password | Role: teacher
Email: aisha.khan@example.com       | Password: password | Role: teacher
Email: hassan.khan@example.com      | Password: password | Role: teacher
Email: fatima.ahmed@example.com     | Password: password | Role: teacher
Email: imran.khan@example.com       | Password: password | Role: teacher
```

#### **Option 2: Create New Teacher Accounts**
As Admin, go to Teachers section and create new teachers with your own emails:
```
Name:     [Your name]
Email:    yourteacheremail@school.com
Password: password
Role:     Teacher
```

#### **Option 3: Reset Teacher Passwords**
As Admin, you can reset any teacher's password to match their email prefix:
```
Email:    teacher@school.com
Password: teacherpassword or password
```

---

## 👨‍🎓 Student Accounts (50 Students)

### How to Find Student Credentials:

Similarly, 50 random students are created with randomly generated emails and the password `password`.

#### **Option 1: View in Database**
1. Go to `users` table
2. Filter by `role = 'student'`
3. View all 50 student emails assigned to classes

**Example students you might see:**
```
Email: sara.malik@example.com      | Password: password | Role: student
Email: ali.reza@example.com        | Password: password | Role: student
Email: zainab.khan@example.com     | Password: password | Role: student
Email: bilal.ahmed@example.com     | Password: password | Role: student
Email: hina.hassan@example.com     | Password: password | Role: student
```

#### **Option 2: Create Test Student Accounts**
As Admin, create specific test accounts:
```
Name:     Rana Ahmed
Email:    rana.ahmed@school.com
Password: password
Role:     Student
Class:    Class-1-A
```

#### **Option 3: Query Database Directly**
Run this SQL query to see all users:
```sql
-- All users
SELECT id, name, email, role FROM users;

-- Only teachers
SELECT id, name, email, role FROM users WHERE role = 'teacher';

-- Only students
SELECT id, name, email, role FROM users WHERE role = 'student';

-- Only admin
SELECT id, name, email, role FROM users WHERE role = 'admin';
```

---

## 📋 Default Test Data Summary

```
Total Users Created: 56
├── 1 Admin User
├── 5 Teachers
│   ├── Teacher 1 (Class 1)
│   ├── Teacher 2 (Class 2)
│   ├── Teacher 3 (Class 3)
│   ├── Teacher 4 (Class 4)
│   └── Teacher 5 (Class 5)
│
└── 50 Students (Distributed across 5 classes)
    ├── 10 students in Class 1
    ├── 10 students in Class 2
    ├── 10 students in Class 3
    ├── 10 students in Class 4
    └── 10 students in Class 5

Additional Test Data:
- 8 Subjects across all classes
- 20 Books in library
- 150+ Loan records
- 1000+ Attendance records
- 500+ Grades
- 100+ Invoices
- 100+ Messages
- 100+ Teacher Attendance records
- 5 Payroll records
```

---

## 🔧 How to Get Actual User Emails & Info

### Method 1: Using Laravel Tinker (Easiest)
```bash
cd Lahomes
php artisan tinker

# Get admin user
User::where('role', 'admin')->first();

# Get all teachers
User::where('role', 'teacher')->get();

# Get all students
User::where('role', 'student')->get();

# Get specific user
User::find(1);

# Exit tinker
exit
```

### Method 2: Using Artisan Command
```bash
php artisan tinker

# List all emails with roles
User::select('id', 'name', 'email', 'role')->get();

# Get teacher with most students
Teacher::withCount('students')->orderBy('students_count', 'desc')->first();

# Get student info
Student::with('user', 'schoolClass')->first();
```

### Method 3: Check Database Directly
```bash
# If using SQLite (default)
cd Lahomes/database
sqlite3 database.sqlite

SELECT name, email, role FROM users;
.quit
```

---

## 🎯 Quick Access Guide

### For Admin:
```
Login: admin@school.com
Pass:  password
Access: Everything
```

### For Teachers:
```
Login: [Check database for actual email]
Pass:  password
Access: Attendance, Grades, Classes, Students, Messages
```

### For Students:
```
Login: [Check database for actual email]
Pass:  password
Access: Own profile, grades, attendance, announcements, library
```

---

## 🔐 Security Notes

⚠️ **IMPORTANT (For Development Only)**
- All test accounts use password: `password`
- **NEVER** use these in production!
- Change all passwords before deploying
- Set strong, unique passwords for real users
- Use `.env` file for sensitive credentials

---

## 👤 Add New Users Manually

### As Admin, Create a New Teacher:
1. Login as admin@school.com / password
2. Go to Teachers → Create
3. Fill in details:
   ```
   Name:      Khalid Ahmed
   Email:     khalid@school.com
   CNIC:      1234567890123
   Subject:   Physics
   Hire Date: 2026-03-07
   Salary:    Rs. 50,000
   ```

### Create a New Student:
1. Go to Students → Create
2. Fill in details:
   ```
   Name:             Sara Khan
   Email:            sara@school.com
   Class:            Class-1-A
   Parent Name:      Ahmed Khan
   Parent Phone:     +92-300-1234567
   Date of Birth:    2010-05-15
   ```

---

## 📞 Popular Pakistani Test Names in System

### Teachers:
- Muhammad Ali Khan
- Hassan Ahmed
- Omar Khan
- Faisal Ahmed
- Imran Khan

### Students:
- Aisha Khan
- Zainab Ahmed
- Fatima Hassan
- Hina Khan
- Saira Ahmed
- (50 total students with Pakistani Muslim names)

---

## 🚀 First Time Setup

1. **See all users:**
   ```bash
   php artisan tinker
   User::all();
   ```

2. **Login with Admin:**
   - Email: `admin@school.com`
   - Password: `password`

3. **View test data in dashboard**

4. **Find teacher/student details** in Users table via database

5. **Change passwords** immediately in production!

---

## Troubleshooting

**Q: I see error "Too many login attempts"**
- Wait 15 minutes or clear the rate limit cache

**Q: Can't find student email?**
- Go to Admin → Users → Filter by role "student"
- Or use: `User::where('role', 'student')->pluck('email');` in tinker

**Q: Forgot password?**
- Use "Forgot Password" link on login page
- Or reset in database: `User::find(1)->update(['password' => Hash::make('newpassword')]);`

**Q: Want to reset all passwords?**
```bash
php artisan tinker
User::all()->each(fn($u) => $u->update(['password' => Hash::make('password')]));
exit
```

---

✅ **System fully seeded and ready to use!**
