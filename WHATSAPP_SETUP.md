# WhatsApp Alert System - Setup Guide

## Overview
The WhatsApp Alert System is a **free and secure** messaging solution integrated with your school management system. It uses official WhatsApp APIs from **Twilio** (free sandbox) or **Meta** (WhatsApp Business Cloud API).

## Quick Start

### Option 1: Twilio WhatsApp Sandbox (Recommended for Testing)

**Best for:** Quick testing and demos. Completely free with no billing.

#### Steps:

1. **Create Twilio Account** (Free):
   - Visit: https://www.twilio.com/try-twilio
   - Sign up and verify your phone number
   - Get your free credits ($15 USD)

2. **Get WhatsApp Sandbox Credentials**:
   - Go to Twilio Console > Messaging > WhatsApp Sandbox
   - Find your Sandbox Number (usually `whatsapp:+15551234567`)
   - Copy your Account SID and Auth Token

3. **Configure in .env**:
   ```
   WHATSAPP_PROVIDER=twilio
   TWILIO_ACCOUNT_SID=your_account_sid_here
   TWILIO_AUTH_TOKEN=your_auth_token_here
   TWILIO_WHATSAPP_NUMBER=whatsapp:+15551234567
   ```

4. **Verify Your Phone** (Important!):
   - On Twilio WhatsApp Sandbox page, click "Join this workspace"
   - Send the code to the sandbox number
   - This verifies your phone for receiving messages

---

### Option 2: Meta WhatsApp Business Cloud API (Production)

**Best for:** Production use. Official Meta API for unlimited messages.

#### Steps:

1. **Create Meta Business Account**:
   - Visit: https://business.facebook.com/
   - Create a business account

2. **Set Up WhatsApp Business App**:
   - Go to https://developers.facebook.com/
   - Create an app
   - Add WhatsApp Business Platform product

3. **Get Credentials**:
   - Business Phone Number ID
   - Access Token
   - Business Account ID

4. **Configure in .env**:
   ```
   WHATSAPP_PROVIDER=meta
   META_ACCESS_TOKEN=your_access_token_here
   META_PHONE_NUMBER_ID=your_phone_number_id_here
   META_BUSINESS_ACCOUNT_ID=your_business_account_id_here
   ```

---

## Features

### 1. Alert Templates
- Pre-defined message templates for different events
- Supports variable substitution (e.g., `{student_name}`, `{date}`)
- Categories: Attendance, Payroll, Grades, Fees, Announcements
- Easy to create and manage

### 2. Recipients Management
- Store WhatsApp numbers for students, teachers, parents
- Manage consent (opt-in/opt-out)
- Track verification status
- Multiple recipient types

### 3. Alert Dashboard
- Real-time statistics
- Success/failure tracking
- Message monitoring
- Retry mechanism for failed alerts

### 4. Message Tracking
- Status: Pending, Sent, Delivered, Failed
- Provider message IDs
- Error logging
- Automatic retry (up to 3 times)

---

## Usage

### Creating a Template

1. Go to **WhatsApp Alerts > Templates**
2. Click **Create Template**
3. Fill in:
   - **Name**: e.g., "Daily Attendance Alert"
   - **Category**: Select type
   - **Template**: Type your message (use `{variable}` for dynamic content)
   - **Variables**: List variables (e.g., `student_name, date, status`)

**Example Template**:
```
Hello {student_name},

Your attendance for {date} has been marked as {status}.

Best regards,
School Management System
```

### Adding Recipients

1. Go to **WhatsApp Alerts > Recipients**
2. Click **Add Recipient**
3. Enter:
   - Name
   - Phone number (with country code, e.g., +12025551234)
   - Recipient type
   - Check "opt-in" checkbox

### Sending Alerts

1. Go to **WhatsApp Alerts > Send Alert**
2. Choose:
   - A saved recipient OR enter custom phone number
   - A template (optional) OR write custom message
3. Review message content
4. Click **Send Alert**

---

## Integration with Existing Features

You can trigger WhatsApp alerts from other parts of the system:

```php
// In your controller or service
use App\Services\WhatsAppService;
use App\Models\WhatsAppTemplate;

$whatsapp = app(WhatsAppService::class);

// Send simple message
$whatsapp->sendMessage('+12025551234', 'Your attendance has been marked');

// Send templated message
$template = WhatsAppTemplate::where('name', 'Attendance Alert')->first();
$whatsapp->sendTemplateMessage(
    $recipient,
    $template,
    [
        'student_name' => 'John Doe',
        'date' => '2026-03-06',
        'status' => 'Present'
    ]
);
```

---

## Predefined Templates

To help you get started, use these templates:

### 1. Attendance Alert
**Template**: `Dear {student_name}, your attendance for {date} has been marked as {status}.`

### 2. Payroll Notification
**Template**: `Hi {teacher_name}, your payroll for {month} has been {status}. Amount: {amount}`

### 3. Grade Alert
**Template**: `Hi {student_name}, your grades for {subject} have been updated. Score: {marks}%`

### 4. Fee Reminder
**Template**: `Reminder: Fee for {student_name} (Class {class}) is due on {due_date}. Amount: {amount}`

### 5. Announcement
**Template**: `Announcement: {announcement_text}`

---

## Security & Privacy

✅ **Secure by Default**:
- Encrypted API communication (HTTPS)
- Database encryption support
- API key protection (stored in .env)
- Audit logging of all messages

✅ **Compliance**:
- GDPR compliant (with opt-in tracking)
- WhatsApp's official APIs only
- Message retention policies
- User consent management

---

## Troubleshooting

### "Failed to send alert"
1. Check .env configuration
2. Verify phone number format (with country code)
3. Check Twilio/Meta console for errors
4. Review logs: `storage/logs/whatsapp.log`

### Messages not sending (Twilio Sandbox)
1. Make sure you verified your phone number
2. Your phone number must message the sandbox first
3. Message expires after 24 hours without contact

### Invalid phone number
- Use international format: `+1` + area code + number
- Remove spaces or dashes
- Ensure valid country code

---

## Performance & Limits

- **Twilio Sandbox**: ~1 message/second, 24-hour message limit
- **Meta API**: Unlimited (based on your tier)
- **Retry Logic**: Automatic retry up to 3 times for failed messages
- **Database**: All messages logged and searchable

---

## Next Steps

1. ✅ Configure provider credentials in .env
2. ✅ Create alert templates for your school
3. ✅ Add parent/teacher WhatsApp numbers
4. ✅ Test sending a message
5. ✅ Set up automated alerts (if needed)

---

## Support

For issues or questions:
- Check application logs: `tail -f storage/logs/laravel.log`
- Review WhatsApp provider console
- Check database for failed alerts
- Review error messages in alert details page

---

**Free & Secure WhatsApp Alerts Ready to Use! 🎉**
