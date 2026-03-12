# ✅ WhatsApp Evolution API - Real-Time Integration Guide

## 📋 Overview
This guide explains how to set up your Lahomes system with **Evolution API** for real-time WhatsApp messaging, delivery status tracking, and webhook-based message updates.

---

## 🔧 Part A: Configuration Setup

### 1️⃣ Update Your `.env` File

Your `.env` file has been updated with Evolution API configuration. You need to replace these values:

```env
# Evolution API Configuration
EVOLUTION_API_URL=http://localhost:8080              # Your Evolution API instance URL
EVOLUTION_API_TOKEN=your_api_token_here              # Your API token from Evolution
EVOLUTION_WEBHOOK_SECRET=your_webhook_secret_here    # Random secret key for webhook
EVOLUTION_INSTANCE_NAME=lahomes_instance             # Your WhatsApp instance name
```

**Where to get these credentials:**
- **EVOLUTION_API_URL**: Your Evolution API server URL (e.g., `https://api.evolution.com` or self-hosted)
- **EVOLUTION_API_TOKEN**: Generate from Evolution API dashboard
- **EVOLUTION_WEBHOOK_SECRET**: Create any random string (min 32 characters recommended)
- **EVOLUTION_INSTANCE_NAME**: The instance name in your Evolution setup

### 2️⃣ Update `config/whatsapp.php`

Configuration added automatically. Default provider changed to:
```php
'provider' => env('WHATSAPP_PROVIDER', 'evolution')
```

---

## 🚀 Part B: Files Created/Updated

### New Files:
1. **`app/Services/EvolutionService.php`** - Handles all Evolution API calls
2. **`app/Http/Controllers/WhatsAppWebhookController.php`** - Receives real-time webhooks
3. **`database/migrations/2024_03_12_000001_add_evolution_api_support_to_whatsapp_alerts.php`** - Database updates

### Updated Files:
1. **`.env`** - Added Evolution configuration
2. **`config/whatsapp.php`** - Added Evolution section
3. **`app/Services/WhatsAppService.php`** - Added Evolution provider support
4. **`app/Models/WhatsAppAlert.php`** - Added `read_at` field
5. **`routes/web.php`** - Added webhook routes

---

## 🔌 Part C: Webhook Setup

### What is a Webhook?
Webhooks allow Evolution API to send real-time updates to your application about:
- ✅ Message delivery status (sent, delivered, read)
- ❌ Failed messages
- 📨 Incoming messages
- 🔄 Message updates

### Webhook URLs
Your application has these webhook endpoints (no authentication required):

| Endpoint | Method | Purpose |
|----------|--------|---------|
| `/webhook/whatsapp` | POST | Receive real-time events |
| `/webhook/whatsapp` | GET | Verify webhook (token query) |
| `/webhook/whatsapp/test` | GET | Test webhook connectivity |

### How to Register Webhook in Evolution API:

**Using the Evolution API Dashboard:**
1. Go to your Evolution API dashboard
2. Navigate to Webhooks/Settings
3. Add webhook URL: `https://yourdomain.com/webhook/whatsapp`
4. Set webhook secret: (same as `EVOLUTION_WEBHOOK_SECRET` in `.env`)
5. Subscribe to events:
   - `MESSAGES_UPSERT` (new messages)
   - `MESSAGES_UPDATE` (status updates)
   - `MESSAGE_FAILED` (failed messages)

**Or using cURL:**
```bash
curl -X POST https://evolution-api.com/webhook/set/{instance-name} \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "url": "https://yourdomain.com/webhook/whatsapp",
    "events": [
      "MESSAGES_UPSERT",
      "MESSAGES_UPDATE",
      "MESSAGE_FAILED"
    ]
  }'
```

---

## 📤 Part D: Sending Messages

### Via Dashboard (UI)
1. Go to **WhatsApp Alerts** → **Send Alert**
2. Select recipient or enter phone number
3. Choose template or write custom message
4. Click **Send**
5. Status updates automatically via webhooks

### Via Code/API

```php
use App\Services\WhatsAppService;

$service = app(WhatsAppService::class);

// Send simple text message
$alert = $service->sendMessage(
    '+923001234567',  // Phone number with country code
    'Hello! Your invoice #123 is ready.'
);

if ($alert) {
    echo "Message sent! ID: " . $alert->id;
    echo "Status: " . $alert->status;
    echo "Provider ID: " . $alert->provider_message_id;
}
```

### Advanced: Send with Template

```php
use App\Models\WhatsAppTemplate;
use App\Models\WhatsAppRecipient;

$template = WhatsAppTemplate::where('name', 'invoice_alert')->first();
$recipient = WhatsAppRecipient::find(1);

$variables = [
    'student_name' => 'Ali Khan',
    'invoice_id' => '1001',
    'amount' => 'Rs. 50,000',
];

$alert = $service->sendTemplateMessage($recipient, $template, $variables);
```

---

## 📊 Part E: Message Status Tracking

### Message Statuses:
| Status | Meaning | When Triggered |
|--------|---------|-----------------|
| `pending` | Waiting to be sent | After creation |
| `sent` | Successfully sent to WhatsApp | Immediately after send |
| `delivered` | Received by WhatsApp server | MESSAGES_UPDATE (status=3) |
| `read` | Message read by recipient | MESSAGES_UPDATE (status=4) |
| `failed` | Delivery failed | MESSAGE_FAILED event |

### Check Message Status:

```php
use App\Models\WhatsAppAlert;

$alert = WhatsAppAlert::find(1);

echo "Status: " . $alert->status;
echo "Sent at: " . $alert->sent_at;
echo "Delivered at: " . $alert->delivered_at;
echo "Read at: " . $alert->read_at;
```

### View All Alerts Dashboard:
Go to **Admin → WhatsApp Alerts** to see all messages, statuses, and retry history.

---

## 🔄 Part F: Automatic Retry Mechanism

If a message fails:
1. Status automatically changes to `failed`
2. `retry_count` increments
3. If `retry_count < 3`, status resets to `pending` for automatic retry
4. Manual retry available via dashboard button

---

## 🔐 Part G: Security

### Webhook Signature Verification
All webhook calls are verified using HMAC-SHA256:
```php
// In WhatsAppWebhookController
$signature = $request->header('X-Webhook-Signature');
$verified = $this->verifySignature($payload, $signature, $webhook_secret);
```

### Security Checklist:
- ✅ webhook endpoints require valid signature OR token
- ✅ Evolution API token stored in `.env` (never in code)
- ✅ HTTPS recommended for production
- ✅ Rate limiting can be added if needed

---

## 🧪 Part H: Testing

### Test Connection:

**Option 1: Via Code**
```php
use App\Services\EvolutionService;

$service = app(EvolutionService::class);
if ($service->testConnection()) {
    echo "✅ Connected to Evolution API";
} else {
    echo "❌ Connection failed";
}
```

**Option 2: Via Browser/cURL**
```bash
# Test webhook endpoint
curl https://yourdomain.com/webhook/whatsapp/test
```

Expected response:
```json
{
  "success": true,
  "webhook_url": "https://yourdomain.com/webhook/whatsapp",
  "instance": "lahomes_instance",
  "time": "2024-03-12T10:30:00Z"
}
```

### Test Send Message:

```php
// From tinker or controller
use App\Services\WhatsAppService;

$service = app(WhatsAppService::class);
$alert = $service->sendMessage('+923001234567', 'Test message');

dump($alert); // See the result
```

---

## 📦 Part I: Available Methods

### EvolutionService Methods:

```php
use App\Services\EvolutionService;

$service = app(EvolutionService::class);

// Send text message
$response = $service->sendMessage($phone, $text);

// Send with media (image/pdf/video)
$response = $service->sendMedia($phone, $mediaUrl, $caption, $mediaType);

// Send template message
$response = $service->sendTemplate($phone, $templateName, $variables);

// Get message status
$response = $service->getMessageStatus($messageId);

// Get instance info
$response = $service->getInstanceInfo();

// Setup webhook
$response = $service->setupWebhook($webhookUrl);

// Test connection
$connected = $service->testConnection(); // returns true/false
```

---

## 🚨 Part J: Troubleshooting

### Problem: Messages not sending
**Solution:**
1. Check `.env` configuration:
   ```bash
   echo $EVOLUTION_API_URL
   echo $EVOLUTION_API_TOKEN
   ```
2. Verify Evolution instance is running
3. Check logs: `storage/logs/whatsapp.log`

### Problem: Webhooks not received
**Solution:**
1. Verify webhook URL is publicly accessible
2. Check webhook is registered in Evolution dashboard
3. Check signature matches in webhook controller
4. Monitor Network tab for POST requests

### Problem: Phone number format errors
**Solution:**
The system auto-formats phone numbers to E.164 format:
- Pakistan: `+92` country code required
- System converts `03001234567` → `923001234567`

### Problem: Repeated "failed" status
**Solution:**
1. Check phone number validity
2. Verify Evolution API token hasn't expired
3. Check message content (special characters, length)
4. Check recipient opt-in status (if required by platform)

---

## 📝 Part K: Database Schema

### whatsapp_alerts table columns:
```
id                      - Unique ID
template_id             - (Optional) Template used
recipient_phone         - Phone number (formatted)
status                  - pending/sent/delivered/read/failed/deleted
message                 - Message content
data                    - JSON variables/template data
provider                - evolution/twilio/meta/custom
provider_message_id     - ID from Evolution API (for status tracking)
error_message           - Error details if failed
retry_count             - Number of retry attempts
sent_at                 - When message was sent
delivered_at            - When message was delivered
read_at                 - When message was read
created_at / updated_at - Timestamps
```

---

## 🔄 Part L: Real-Time Flow Example

Here's what happens when you send a message:

```
1. You click "Send" button
   ↓
2. WhatsAppAlertController::sendAlertAction() called
   ↓
3. WhatsAppService::sendMessage() creates alert (status: pending)
   ↓
4. EvolutionService::sendMessage() calls Evolution API
   ↓
5. Evolution API returns message_id (status: sent)
   ↓
6. Alert updated with provider_message_id and sent_at
   ↓
7. [REAL-TIME] Evolution API sends webhook to /webhook/whatsapp
   ↓
8. WhatsAppWebhookController::handle() receives UPDATE event
   ↓
9. Alert status updated to "delivered" and delivered_at set
   ↓
10. [REAL-TIME] When user reads: Another webhook → status: "read"
   ↓
11. Dashboard shows "✅ Delivered" / "👁️ Read"
```

---

## 🎯 Quick Checklist

- [ ] Updated `.env` with Evolution credentials
- [ ] Verified `config/whatsapp.php` looks correct
- [ ] Run migration: `php artisan migrate`
- [ ] Registered webhook in Evolution API dashboard
- [ ] Tested webhook connectivity
- [ ] Sent test message from dashboard
- [ ] Verified status updates in real-time
- [ ] Checked logs for any errors

---

## 💡 Pro Tips

1. **Batch Sending**: Create admin command to send alerts to multiple recipients
2. **Scheduled Messages**: Use Laravel jobs to schedule messages for later
3. **Template Management**: Pre-create templates for common alerts
4. **Analytics**: Track delivery rates, read rates per template
5. **Fallback**: Keep Twilio configured as backup provider

---

## 📞 Support Features

Current system supports:
- ✅ Text messages
- ✅ Media (images, PDFs, videos)
- ✅ Template messages with variables
- ✅ Delivery tracking
- ✅ Read receipts
- ✅ Automatic retry
- ✅ Real-time webhooks
- ✅ Error logging
- ✅ Dashboard UI

---

## 🔗 Useful Links

- Evolution API Docs: https://docs.evolution-api.com/
- WhatsApp Message Formats: https://www.whatsapp.com/business/downloads/
- ISO Phone Codes: https://countrycode.org/
- Laravel HTTP Client: https://laravel.com/docs/http-client

---

**Last Updated**: March 12, 2024  
**Provider**: Evolution API  
**Status**: ✅ Production Ready
