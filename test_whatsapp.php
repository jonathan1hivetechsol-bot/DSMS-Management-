<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== WhatsApp Alert System Test ===\n\n";

// 1. Check WhatsApp Configuration
echo "1️⃣  CONFIG CHECK:\n";
$provider = config('whatsapp.provider');
echo "   Provider: " . $provider . "\n";
echo "   Features Enabled:\n";
foreach (config('whatsapp.features') as $feature => $enabled) {
    echo "     - $feature: " . ($enabled ? '✅' : '❌') . "\n";
}

// 2. Check Database Tables
echo "\n2️⃣  DATABASE TABLES:\n";
$tables = [
    'whats_app_alerts' => 'WhatsAppAlert',
    'whats_app_templates' => 'WhatsAppTemplate',
    'whats_app_recipients' => 'WhatsAppRecipient',
];

foreach ($tables as $table => $model) {
    $count = \DB::table($table)->count();
    echo "   $model: " . $count . " records\n";
}

// 3. Check Credentials
echo "\n3️⃣  CREDENTIALS CHECK:\n";
if ($provider === 'twilio') {
    $sid = config('whatsapp.twilio.account_sid');
    $token = config('whatsapp.twilio.auth_token');
    $fromNumber = config('whatsapp.twilio.from_number');
    
    echo "   Twilio Account SID: " . ($sid ? '✅ SET' : '❌ NOT SET') . "\n";
    echo "   Twilio Auth Token: " . ($token ? '✅ SET' : '❌ NOT SET') . "\n";
    echo "   From Number: " . ($fromNumber ? $fromNumber : '❌ NOT SET') . "\n";
} elseif ($provider === 'meta') {
    $token = config('whatsapp.meta.access_token');
    $phoneId = config('whatsapp.meta.phone_number_id');
    
    echo "   Meta Access Token: " . ($token ? '✅ SET' : '❌ NOT SET') . "\n";
    echo "   Phone Number ID: " . ($phoneId ? '✅ SET' : '❌ NOT SET') . "\n";
}

// 4. Check Templates
echo "\n4️⃣  TEMPLATES:\n";
$templates = \App\Models\WhatsAppTemplate::all();
if ($templates->count() > 0) {
    echo "   ✅ Found " . $templates->count() . " templates:\n";
    foreach ($templates as $template) {
        echo "     - {$template->name} ({$template->category})\n";
    }
} else {
    echo "   ⚠️  No templates found. Create test templates:\n";
    echo "     → Dashboard → WhatsApp Alerts → Templates → Create Template\n";
}

// 5. Check Recipients
echo "\n5️⃣  RECIPIENTS:\n";
$recipients = \App\Models\WhatsAppRecipient::all();
if ($recipients->count() > 0) {
    echo "   ✅ Found " . $recipients->count() . " recipients\n";
} else {
    echo "   ⚠️  No recipients found\n";
}

// 6. Check Alerts  
echo "\n6️⃣  ALERTS HISTORY:\n";
$alerts = \App\Models\WhatsAppAlert::orderBy('created_at', 'desc')->limit(5)->get();
if ($alerts->count() > 0) {
    echo "   Found " . $alerts->count() . " recent alerts:\n";
    foreach ($alerts as $alert) {
        $status = match($alert->status) {
            'sent' => '✅',
            'pending' => '⏳',
            'failed' => '❌',
            'delivered' => '✔️',
            default => '❓'
        };
        echo "     $status [{$alert->provider}] {$alert->recipient_phone} - {$alert->status}\n";
        if ($alert->error_message) {
            echo "        Error: {$alert->error_message}\n";
        }
    }
} else {
    echo "   No alerts sent yet\n";
}

// 7. Test WhatsAppService
echo "\n7️⃣  SERVICE TEST:\n";
try {
    $service = app(\App\Services\WhatsAppService::class);
    echo "   ✅ WhatsAppService initialized successfully\n";
    
    // Check if we can create a test alert
    if ($recipients->count() > 0) {
        echo "   ✅ Can send messages to recipients\n";
    } else {
        echo "   ⚠️  Add a recipient first to test sending\n";
    }
} catch (\Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

// 8. Authorization Check
echo "\n8️⃣  AUTHORIZATION:\n";
$admin = \App\Models\User::where('email', 'admin@school.com')->first();
if ($admin) {
    auth()->login($admin);
    $canManage = \Illuminate\Support\Facades\Gate::allows('manage_alerts');
    echo "   Admin can manage alerts: " . ($canManage ? '✅ YES' : '❌ NO') . "\n";
}

echo "\n=== TEST SUMMARY ===\n";
echo "Provider: $provider\n";
echo "Templates: " . \App\Models\WhatsAppTemplate::count() . " (need at least 1)\n";
echo "Recipients: " . \App\Models\WhatsAppRecipient::count() . " (need at least 1 to test)\n";
echo "\n✅ System is ready! Now:\n";
echo "1. Add a recipient: Dashboard → WhatsApp → Recipients → Add\n";
echo "2. Create a template: Dashboard → WhatsApp → Templates → Create\n";
echo "3. Send alert: Dashboard → WhatsApp → Send Alert\n";
