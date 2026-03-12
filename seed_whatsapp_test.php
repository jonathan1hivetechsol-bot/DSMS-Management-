<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Creating Test Data for WhatsApp ===\n\n";

// 1. Create Test Templates
echo "1️⃣  Creating Templates...\n";

$templates = [
    [
        'name' => 'Attendance Alert',
        'category' => 'attendance',
        'template' => 'السلام عليكم {student_name}! آپ کی حاضری: {attendance_status}',
        'variables' => ['student_name', 'attendance_status'],
        'description' => 'Student attendance notification',
        'is_active' => true,
    ],
    [
        'name' => 'Payroll Processed',
        'category' => 'payroll',
        'template' => 'تنخواہ کی سلپ تیار ہے: {teacher_name} - Rs. {net_salary}',
        'variables' => ['teacher_name', 'net_salary'],
        'description' => 'Teacher payroll notification',
        'is_active' => true,
    ],
    [
        'name' => 'Grade Released',
        'category' => 'grades',
        'template' => 'درجات جاری! {student_name} - {subject}: {marks}/100',
        'variables' => ['student_name', 'subject', 'marks'],
        'description' => 'Grade notification',
        'is_active' => true,
    ],
    [
        'name' => 'Fee Due',
        'category' => 'fees',
        'template' => 'فیس نوٹس: {student_name} کی فیس Rs. {amount} واجب الادا ہے',
        'variables' => ['student_name', 'amount'],
        'description' => 'Fee payment reminder',
        'is_active' => true,
    ],
    [
        'name' => 'School Announcement',
        'category' => 'announcement',
        'template' => 'اہم اعلان: {announcement_title} - {announcement_text}',
        'variables' => ['announcement_title', 'announcement_text'],
        'description' => 'General announcement',
        'is_active' => true,
    ],
];

foreach ($templates as $template) {
    $existing = \App\Models\WhatsAppTemplate::where('name', $template['name'])->first();
    if (!$existing) {
        \App\Models\WhatsAppTemplate::create($template);
        echo "   ✅ {$template['name']}\n";
    } else {
        echo "   ⏭️  {$template['name']} (already exists)\n";
    }
}

// 2. Create Test Recipients
echo "\n2️⃣  Creating Test Recipients...\n";

$recipients = [
    [
        'name' => 'Ahmad Ali',
        'phone_number' => '+923001234567',
        'recipient_type' => 'student',
        'opted_in' => true,
    ],
    [
        'name' => 'Hassan Khan',
        'phone_number' => '+923009876543',
        'recipient_type' => 'teacher',
        'opted_in' => true,
    ],
    [
        'name' => 'Fatima Parent',
        'phone_number' => '+923002345678',
        'recipient_type' => 'parent',
        'opted_in' => true,
    ],
];

foreach ($recipients as $recipient) {
    $existing = \App\Models\WhatsAppRecipient::where('phone_number', $recipient['phone_number'])->first();
    if (!$existing) {
        \App\Models\WhatsAppRecipient::create($recipient);
        echo "   ✅ {$recipient['name']} ({$recipient['recipient_type']})\n";
    } else {
        echo "   ⏭️  {$recipient['name']} (already exists)\n";
    }
}

echo "\n✅ TEST DATA CREATED!\n\n";
echo "=== NEXT STEPS ===\n";
echo "1. Dashboard → WhatsApp Alerts → Send Alert\n";
echo "2. Choose a recipient: Ahmad Ali\n";
echo "3. Choose template: Attendance Alert\n";
echo "4. Fill variables:\n";
echo "   - student_name: Ahmad Ali\n";
echo "   - attendance_status: Present\n";
echo "5. Click Send\n\n";

echo "⚠️  NOTE: To actually send messages, set Twilio credentials in .env:\n";
echo "   TWILIO_ACCOUNT_SID=ACxxxxxxxxx\n";
echo "   TWILIO_AUTH_TOKEN=your_token\n";
echo "   Get free account: https://www.twilio.com/console/sms/try-it\n";
