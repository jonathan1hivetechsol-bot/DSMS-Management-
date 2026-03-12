<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== WhatsApp Alert - LIVE TEST ===\n\n";

// Get a test recipient with Twilio sandbox number
$recipient = \App\Models\WhatsAppRecipient::where('name', 'Twilio Sandbox Test 1')->first();
$template = \App\Models\WhatsAppTemplate::where('name', 'Attendance Alert')->first();

if (!$recipient || !$template) {
    echo "❌ Test data missing. Run: php setup_twilio_sandbox.php && php seed_whatsapp_test.php\n";
    exit;
}

echo "📱 SENDING TEST MESSAGE\n";
echo "   To: {$recipient->name} ({$recipient->phone_number})\n";
echo "   Template: {$template->name}\n\n";

// Initialize service
$service = app(\App\Services\WhatsAppService::class);

// Send message
$alert = $service->sendMessage(
    $recipient->phone_number,
    "السلام عليكم Ahmad Ali! آپ کی حاضری: Present",
    $template,
    [
        'student_name' => 'Ahmad Ali',
        'attendance_status' => 'Present'
    ]
);

if ($alert) {
    echo "✅ ALERT CREATED:\n";
    echo "   ID: {$alert->id}\n";
    echo "   Status: {$alert->status}\n";
    echo "   Provider: {$alert->provider}\n";
    echo "   Message: {$alert->message}\n";
    echo "   Phone: {$alert->recipient_phone}\n";
    
    if ($alert->status === 'sent') {
        echo "\n✅ MESSAGE SENT SUCCESSFULLY!\n";
        echo "   Message ID: {$alert->provider_message_id}\n";
    } else if ($alert->status === 'failed') {
        echo "\n❌ MESSAGE FAILED:\n";
        echo "   Error: {$alert->error_message}\n";
    } else {
        echo "\n⏳ Status: {$alert->status}\n";
    }
} else {
    echo "❌ Failed to create alert\n";
}
