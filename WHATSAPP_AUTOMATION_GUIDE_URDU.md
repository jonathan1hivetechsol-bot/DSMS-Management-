# ✅ WhatsApp Automation System - کمپلیٹ گائیڈ

## 📋 تیاری (Setup)

### مرحلہ 1: ڈیٹا بیس کو اپڈیٹ کریں
```bash
php artisan migrate
```

### مرحلہ 2: کیش کو صاف کریں
```bash
php artisan cache:clear
php artisan config:clear
```

---

## 🎯 Features (خصوصیات)

### 1️⃣ **Automation Dashboard**
- **URL**: `/whatsapp/automation/` 
- آج کے بھیجے گئے پیغامات دیکھیں
- موجودہ گروپس دیکھیں
- فوری تعدادیں دیکھیں (sent, delivered, failed)

### 2️⃣ **Groups Management** (گروپس کا نظام)
- **URL**: `/whatsapp/automation/groups`
- 5 قسمیں:
  - **Students** - تمام طلباء
  - **Teachers** - تمام اساتذہ  
  - **Parents** - تمام والدین
  - **Guardians** - تمام سرپرستیں
  - **Custom** - اپنی فہرست

### 3️⃣ **Broadcast** (بیک وقت بھیجنا)
- **URL**: `/whatsapp/automation/broadcast`
- پورے گروپ کو ایک ساتھ پیغام بھیجیں
- Template أو Custom message
- Confirmation سے پہلے تصدیق

### 4️⃣ **Quick Send** (فوری بھیجنا)
- Dashboard سے کسی بھی نمبر کو فوری پیغام
- کوئی Group requirement نہیں
- AJAX سے سیدھے بھیج دیں

### 5️⃣ **Automated Alerts** (خودکار alerts)
- Attendance notifications
- Grade notifications  
- Fee reminders
- Custom triggers

---

## 🔧 استعمال (Usage)

### گروپ بنانا

**مثال 1: تمام Class 5 طلباء**
```
نام: سال پنجم تمام طلباء
قسم: Students
کلاس: 5
```
→ تمام Class 5 کے طلباء کو شامل

**مثال 2: والدین**
```
نام: تمام والدین
قسم: Parents
```
→ تمام طلباء کے guardian phone نمبروں کو شامل

**مثال 3: Custom List**
```
نام: VIP Parents
قسم: Custom
نمبرز: 
+923001234567
+923009876543
```
→ صرف داخل کیے گئے نمبروں کو شامل

---

## 📱 پیغام بھیجنے کے طریقے

### طریقہ 1: Broadcast (پورا گروپ)
```
1. WhatsApp → Automation → Broadcast
2. گروپ منتخب کریں
3. پیغام لکھیں یا Template منتخب کریں
4. تصدیق کریں
5. "پیغام بھیجیں" پر کلک کریں
```

### طریقہ 2: Quick Send (کوئی نمبر)
```
1. Automation Dashboard
2. "کسی کو بھیجو" بٹن پر کلک
3. نمبر اور پیغام داخل کریں
4. بھیجیں
```

### طریقہ 3: Code میں (Developer)
```php
// کوئی بھی نمبر کو
app(App\Services\WhatsAppService::class)->sendMessage(
    '+923001234567',
    'السلام علیکم! یہ ٹیسٹ پیغام ہے'
);

// گروپ کو
$group = \App\Models\WhatsAppGroup::find(1);
$group->broadcastMessage('حالیہ اپڈیٹ');
```

---

## 🎨 صفحات اور Routes

| URL | صفحہ | مقصد |
|-----|------|------|
| `/whatsapp/automation/` | Dashboard | رپورٹ اور stats |
| `/whatsapp/automation/groups` | Groups List | تمام گروپس دیکھیں |
| `/whatsapp/automation/groups/create` | Create Group | نیا گروپ بنائیں |
| `/whatsapp/automation/broadcast` | Broadcast Form | پورے گروپ کو بھیجیں |

---

## 📊 ڈیٹا بیس ٹیبلز

### whatsapp_groups
```
id - گروپ کی ID
name - نام
description - تفصیل
type - قسم (students, teachers, parents, etc)
filters - JSON (مثلاً class_id)
member_count - اراکین کی تعداد
is_active - فعال؟
created_at / updated_at
```

### whatsapp_alerts (updated)
```
... پرانے فیلڈز ...
group_id - کون سے گروپ سے بھیجا گیا
```

---

## 🧠 کیسے کام کرتا ہے (Flow)

```
Admin منصوبہ بناتا ہے
        ↓
گروپ بناتا ہے (مثلاً: سال اول والدین)
        ↓
پیغام لکھتا ہے/Template منتخب کرتا ہے
        ↓
Broadcast میں گروپ منتخب کرتا ہے
        ↓
سسٹم خودکار طور پر:
  - تمام members تلاش کرتا ہے
  - ہر ایک کو پیغام بھیجتا ہے
  - Status track کرتا ہے
        ↓
Real-time webhooks سے status update ہوتا ہے
        ↓
Admin دیکھتا ہے: ✓ Delivered
```

---

## ⚙️ Automation Events (خودکار triggers)

### دستیاب APIs (Backend میں)

```php
// Attendance alert بھیجیں
POST /whatsapp/automation/attendance-alert
{
    "class_id": 1,
    "date": "2024-03-12"
}

// Grades بھیجیں
POST /whatsapp/automation/grade-notification
{
    "exam_id": 1
}

// Fee reminders
POST /whatsapp/automation/fee-reminder
{
    "days_overdue": 7  // 7 دن سے referral ہے
}
```

---

## 📝 Template Variables

Templates میں استعمال کریں:
- `{name}` - نام
- `{student_name}` - طالب کا نام
- `{date}` - تاریخ
- `{amount}` - رقم
- `{marks}` - نمبرات

**مثال:**
```
السلام علیکم {name}!

{student_name} کے نمبرات: {marks}

شکریہ
```

---

## 🚀 Performance Tips

1. **بڑے گروپس** - 1000+ members والے
   - رات کو بھیجیں
   - Queued jobs استعمال کریں (بعد میں)

2. **Templates** - بار بار استعمال کریں
   - کم typing
   - consistency

3. **Groups** - پہلے سے بنائیں
   - فوری broadcast کے لیے
   - کوئی delay نہیں

---

## ⚠️ اہم نکات

✅ **ہمیشہ یقینی بنائیں:**
- Phone numbers صحیح ہوں
- Contact نے opt-in کیا ہو
- Message دیر رات نہ بھیجیں
- Frequency زیادہ نہ ہو

❌ **نہ کریں:**
- Spam messages
- بیک وقت بہت سارے
- غلط نمبروں پر
- بغیر تصدیق کے

---

## 🔄 Scheduled Automation (آنے والا)

بعد میں یہ features آئیں گے:
- Cron-based scheduling
- Conditional triggers (اگر X ہو تو Y بھیجو)
- Multi-language support
- Template builder UI

---

## 📞 مثالیں

### موجودہ استعمال

#### 1. حاضری بھیجنا (روزانہ)
```
Groups → Students (سال اول)
Message: "حاضری کا ریکارڈ درج ہو گیا"
Broadcast → बھیجیں
```

#### 2. نتائج بھیجنا (Exams کے بعد)
```
Groups → Parents (سال اول)
Message: "نتائج جاری ہو گئے۔ ہمارے ساتھ چیک کریں"
Broadcast → بھیجیں
```

#### 3. فیس کی یادہانی (ماہانہ)
```
Groups → Guardians (سال دوم)
Message: "فیس بقایا ہے - براہ کرم جماع کریں"
Broadcast → بھیجیں
```

#### 4. اہم اعلان (جب ضرورت ہو)
```
Groups → All Parents
Message: "اسکول کل بند ہے"
Quick Send / Broadcast → بھیجیں
```

---

## 🐛 عام مسائل

**مسئلہ**: Messages بھن نہیں ہو رہے
- **حل**: Evolution API token check کریں
- **حل**: Phone numbers valid ہیں؟

**مسئہلہ**: Group empty دکھ رہا ہے
- **حل**: students/teachers کے پاس phone numbers ہیں؟
- **حل**: Database users active ہیں؟

**مسئہلہ**: Status update نہیں ہو رہی
- **حل**: Webhook registered ہے؟
- **حل**: Logs دیکھیں: `tail -f storage/logs/whatsapp.log`

---

## 📚 مزید معلومات

دیکھیں:
- `WHATSAPP_EVOLUTION_API_SETUP.md` - مکمل setup
- `WHATSAPP_EVOLUTION_QUICK_REFERENCE.md` - Quick reference
- `WHATSAPP_QUICK_REFERENCE.md` - General WhatsApp

---

**تیاری مکمل! اب گروپ بنائیں اور broadcasts بھیجنا شروع کریں۔** 🚀
