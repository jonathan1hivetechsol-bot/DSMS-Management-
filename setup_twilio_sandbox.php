<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Setting up Twilio WhatsApp Sandbox Test ===\n\n";

// Delete old recipients that conflict
\App\Models\WhatsAppRecipient::whereIn('name', ['Ahmad Ali', 'Hassan Khan', 'Fatima Parent'])->delete();

echo "✅ Old test recipients removed\n\n";

// Create new sandbox-compatible recipients
$newRecipients = [
    [
        'name' => 'Twilio Sandbox Test 1',
        'phone_number' => '+15551234567',  // Different from FROM number
        'recipient_type' => 'student',
        'opted_in' => true,
    ],
    [
        'name' => 'Test Your Own Number',
        'phone_number' => '+1YOUR_ACTUAL_PHONE',  // CHANGE THIS to your real phone
        'recipient_type' => 'teacher',
        'opted_in' => true,
    ],
];

foreach ($newRecipients as $recipient) {
    \App\Models\WhatsAppRecipient::create($recipient);
    echo "✅ Created: {$recipient['name']} ({$recipient['phone_number']})\n";
}

echo "\n📱 TWILIO SANDBOX SETUP INSTRUCTIONS:\n\n";
echo "1. Your Twilio Number: +15017462909\n";
echo "2. Go to: https://console.twilio.com/\n";
echo "3. Navigate to: Messaging → WhatsApp → Try It Out\n";
echo "4. Follow the sandbox setup wizard\n";
echo "5. You'll get a message: 'To get started, send this message to this number'\n";
echo "6. Send that message from your WhatsApp to join the sandbox\n";
echo "7. Then return here and test sending alerts\n\n";

echo "After setup complete, run: php send_test_whatsapp.php\n";
