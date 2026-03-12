# WhatsApp Evolution API - Quick Reference Card

## 🚀 Installation Checklist

```bash
# 1. Pull latest code
git pull

# 2. Update environment variables
# Edit .env - Set Evolution API credentials
EVOLUTION_API_URL=http://localhost:8080
EVOLUTION_API_TOKEN=your_token
EVOLUTION_WEBHOOK_SECRET=your_secret
EVOLUTION_INSTANCE_NAME=lahomes_instance

# 3. Run database migration
php artisan migrate

# 4. Clear cache
php artisan cache:clear
php artisan config:clear

# 5. Test connection
php artisan tinker
>>> app(App\Services\EvolutionService::class)->testConnection()

# 6. Register webhook in Evolution API dashboard
Webhook URL: https://yourdomain.com/webhook/whatsapp
Secret: (same as EVOLUTION_WEBHOOK_SECRET)
```

## 📱 Send Message Examples

### Simple Text Message
```php
app(App\Services\WhatsAppService::class)->sendMessage('+923001234567', 'Hello!');
```

### With Template
```php
$service = app(App\Services\WhatsAppService::class);
$alert = $service->sendMessage(
    '+923001234567',
    'Student: {name}, Invoice: {id}, Amount: {amount}',
    null,
    ['name' => 'Ali', 'id' => 'INV001', 'amount' => 'Rs.5000']
);
```

### Send Media
```php
app(App\Services\EvolutionService::class)->sendMedia(
    '+923001234567',
    'https://example.com/image.jpg',
    'Your receipt image',
    'image'
);
```

## 📊 Message Status Reference

**Status Flow:**
```
pending → sent → delivered → read
          ↓
         failed (auto-retry up to 3 times)
```

**Check Status:**
```php
$alert = App\Models\WhatsAppAlert::find(1);
echo $alert->status;        // pending, sent, delivered, read, failed
echo $alert->sent_at;       // When sent
echo $alert->delivered_at;  // When delivered
echo $alert->read_at;       // When read
echo $alert->error_message; // If failed
echo $alert->retry_count;   // Number of retries
```

## 🔌 Webhook Events

**Events your app receives:**

| Event | Payload | Status Update |
|-------|---------|---|
| `MESSAGES_UPSERT` | New message sent | sent |
| `MESSAGES_UPDATE` | Status changed | delivered/read |
| `MESSAGE_FAILED` | Message failed | failed |
| `MESSAGES_DELETE` | Message deleted | deleted |

**Webhook URL:** `POST /webhook/whatsapp`

## 🔐 Security

- ✅ All webhooks verified with HMAC-SHA256
- ✅ Evolution token in `.env` (never commit)
- ✅ No authentication required for webhooks (but signature verified)
- ✅ All requests logged to `storage/logs/whatsapp.log`

## 📁 Key Files

| File | Purpose |
|------|---------|
| `app/Services/EvolutionService.php` | Evolution API client |
| `app/Services/WhatsAppService.php` | High-level WhatsApp service |
| `app/Http/Controllers/WhatsAppWebhookController.php` | Webhook receiver |
| `config/whatsapp.php` | Configuration |
| `.env` | Environment variables |
| `database/migrations/*evolution*` | DB schema updates |

## 🆘 Troubleshooting

**Webhook not working?**
- Check webhook URL is publicly accessible
- Verify webhook registered in Evolution dashboard
- Check signature verification in controller
- View logs: `tail -f storage/logs/whatsapp.log`

**Message not sending?**
- Verify `.env` configuration
- Check Evolution API is running
- Try test: `app(App\Services\EvolutionService::class)->testConnection()`
- Check phone number format (must have country code)

**Connection errors?**
- Verify `EVOLUTION_API_URL` is correct
- Check firewall/network allows connection
- Verify Evolution API token hasn't expired
- Test with: `curl -H "Authorization: Bearer TOKEN" EVOLUTION_API_URL`

## 🧪 Quick Test Commands

```bash
# Test webhook endpoint
curl http://localhost:8000/webhook/whatsapp/test

# Test Evolution connection (in tinker)
php artisan tinker
>>> app(App\Services\EvolutionService::class)->testConnection()

# Send test message (in tinker)
>>> $alert = app(App\Services\WhatsAppService::class)->sendMessage('+92...', 'test');
>>> $alert->status

# View message history
>>> App\Models\WhatsAppAlert::latest()->first()

# View specific alert
>>> App\Models\WhatsAppAlert::find(1)
```

## 🎯 Common Use Cases

### Attendance Alert
```php
$service = app(App\Services\WhatsAppService::class);
$service->sendMessage(
    $student->guardian_phone,
    "Alert: {$student->name} was absent today"
);
```

### Invoice Reminder
```php
$service->sendMessage(
    $student->guardian_phone,
    "Invoice #{$invoice->id} of Rs.{$invoice->amount} is due on {$invoice->due_date}"
);
```

### Grade Notification
```php
$service->sendMessage(
    $student->guardian_phone,
    "Marks Released: {$student->name} scored {$grade->marks}/{$grade->total}"
);
```

### Payroll Alert (Teacher)
```php
$service->sendMessage(
    $teacher->phone,
    "Your salary of Rs.{$payroll->net_salary} has been approved"
);
```

## 📞 Provider Comparison

| Feature | Evolution | Twilio | Meta |
|---------|-----------|--------|------|
| Real-time status | ✅ | ✅ | ✅ |
| Media support | ✅ | ✅ | ✅ |
| Templates | ✅ | ❌ | ✅ |
| Incoming msgs | ✅ | ✅ | ✅ |
| Webhook support | ✅ | ✅ | ✅ |
| Cost | Varies | Free(sandbox) | Free(sandbox) |

## 🚨 Important Notes

1. **Phone Format**: Always use country code (e.g., +923001234567)
2. **Rate Limits**: Check Evolution API rate limiting
3. **Opt-in**: Verify recipients have opted in for WhatsApp
4. **Logs**: Monitor `storage/logs/whatsapp.log` always
5. **Backups**: Keep Twilio/Meta configured as fallback
6. **Testing**: Use sandbox before production

## 📖 Documentation Files

- `WHATSAPP_EVOLUTION_API_SETUP.md` - Full setup guide
- `test_evolution_api.php` - Testing guide
- This file - Quick reference
- `WHATSAPP_IMPLEMENTATION.md` - Original implementation
- `WHATSAPP_QUICK_REFERENCE.md` - General WhatsApp reference

---

**Need Help?** Check logs: `tail -f storage/logs/whatsapp.log`
