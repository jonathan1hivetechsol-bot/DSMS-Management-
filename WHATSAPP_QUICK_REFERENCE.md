# 📱 WhatsApp Alerts - Quick Reference

## Access Point
**Sidebar** → **WhatsApp Alerts** (located in School Management section)

## Main Operations

### 1. 📊 Dashboard
- View real-time statistics
- Recent alerts list
- Quick actions

### 2. 📤 Send Alert
- Select existing recipient OR enter phone number
- Choose template OR write custom message
- Track character count
- Auto-retry on failure

### 3. 📋 Manage Templates
- Create alert templates
- Edit templates
- Delete unused templates
- Support variables like `{student_name}`

### 4. 👥 Manage Recipients
- Add/view WhatsApp contacts
- Track opt-in status
- Categorize by type (student, teacher, parent, admin)

### 5. 📬 View All Alerts
- Filter by status, date range
- Retry failed alerts
- View detailed alert information
- Track delivery

---

## Getting Started (5 Minutes)

### Setup Credentials

Edit `.env` file:
```
WHATSAPP_PROVIDER=twilio
TWILIO_ACCOUNT_SID=ACxxx...
TWILIO_AUTH_TOKEN=xxx...
TWILIO_WHATSAPP_NUMBER=whatsapp:+15551234567
```

**Get Twilio Credentials**:
1. Visit https://www.twilio.com/try-twilio
2. Sign up (free)
3. Go to Console → Messaging → WhatsApp
4. Copy Account SID & Auth Token

### Create First Template

1. Go to **WhatsApp Alerts** → **Templates**
2. Click **Create Template**
3. Name: `Test Template`
4. Message: `Hello {name}, testing WhatsApp alerts!`
5. Click **Create**

### Add Recipient

1. Go to **WhatsApp Alerts** → **Recipients**
2. Click **Add Recipient**
3. Name: `Test Contact`
4. Phone: Your number with country code (e.g., `+12025551234`)
5. Type: `Student`
6. Check "opt-in"
7. Click **Add Recipient**

### Send First Alert

1. Go to **WhatsApp Alerts** → **Send Alert**
2. Select your test contact
3. Select your test template
4. Click **Send Alert**
5. Check your phone! 🎉

---

## Template Variables Reference

Use these placeholders in templates:

```
{student_name}        - Student's full name
{teacher_name}        - Teacher's full name
{parent_name}         - Parent's name
{date}               - Date (e.g., 2026-03-06)
{month}              - Month (e.g., March)
{year}               - Year (e.g., 2026)
{class}              - Class/Grade name
{subject}            - Subject name
{marks}              - Marks/Score
{status}             - Status (Present/Absent/Late)
{amount}             - Amount (salary, fee, etc.)
{due_date}           - Due date for payments
{message}            - Custom message text
{announcement_text}  - Announcement content
```

---

## Ready-to-Use Templates

### Attendance Template
```
Dear {student_name},

Your attendance for {date} has been marked as {status}.

Best regards,
School Management System
```

### Payroll Template
```
Hi {teacher_name},

Your payroll for {month} {year} has been processed.
Amount: {amount}

Status: {status}
```

### Grade Alert
```
Hi {student_name},

Your grade for {subject} has been updated:
Score: {marks}%

View full report in the student portal.
```

### Fee Reminder
```
Dear {parent_name},

Friendly reminder: Fee for {student_name} is due on {due_date}.
Class: {class}
Amount: {amount}

Please arrange the payment at your earliest convenience.
```

---

## Status Meanings

| Status | Meaning |
|--------|---------|
| **pending** | Queued, waiting to send |
| **sent** | Sent to WhatsApp, awaiting delivery |
| **delivered** | Successfully delivered to recipient |
| **failed** | Failed to send (check error message) |

---

## Common Issues & Solutions

### ❌ "Invalid phone number"
✅ **Solution**: Use international format like `+12025551234`

### ❌ "Failed to send alert"
✅ **Solution**: 
- Check `.env` credentials
- Verify phone number format
- Check Twilio account has credits

### ❌ "Message not received (Twilio)"
✅ **Solution**:
- Make sure your phone verified with sandbox
- Message expires after 24 hours
- Send message from your phone first

### ❌ "Template not found"
✅ **Solution**: Create template first in Templates section

---

## Admin Role Required

⚠️ Only authorized admin users can:
- Create/edit templates
- Add/manage recipients
- Send alerts
- View all alerts

---

## Database Tables

```
whats_app_templates
├── id
├── name (unique)
├── category
├── template
├── variables (json)
└── is_active

whats_app_alerts
├── id
├── template_id
├── recipient_phone
├── status
├── message
├── data (json)
├── provider
├── error_message
└── retry_count

whats_app_recipients
├── id
├── phone_number (unique)
├── recipient_type
├── name
├── opt_in (boolean)
└── verified_at
```

---

## Logging

View WhatsApp message logs:
```bash
tail -f storage/logs/whatsapp.log
```

Or check Laravel logs:
```bash
tail -f storage/logs/laravel.log
```

---

## API Integration (Optional)

To send WhatsApp messages from other parts of your app:

```php
use App\Services\WhatsAppService;
use App\Models\WhatsAppTemplate;

// Get the service
$whatsapp = app(WhatsAppService::class);

// Send simple message
$alert = $whatsapp->sendMessage(
    '+12025551234',
    'Hello! This is a test message'
);

// Check status
if ($alert && $alert->isSent()) {
    // Message sent successfully
}
```

---

## Security Checklist

✅ Store API keys in `.env` (never git commit)
✅ Use HTTPS for all API calls
✅ Track user opt-in/consent
✅ Review logs regularly
✅ Limit message content character count
✅ Verify phone numbers before sending

---

## Support & Troubleshooting

**For Twilio Issues**: https://www.twilio.com/console/support
**For Meta Issues**: https://developers.facebook.com/support

Check logs: `storage/logs/whatsapp.log`

---

**Ready to send WhatsApp alerts? Start with the Dashboard! 📱✨**
