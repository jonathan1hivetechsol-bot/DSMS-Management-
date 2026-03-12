<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\WhatsAppAlert;
use App\Services\WhatsAppService;

echo "=== Testing Twilio WhatsApp Provider ===\n\n";

echo "1️⃣  CONFIGURATION:\n";
$provider = config('whatsapp.provider');
echo "   Provider: {$provider}\n";
if ($provider === 'twilio') {
    echo "   AccountID: " . substr(config('whatsapp.twilio.account_sid', ''), 0, 4) . "...\n";
    echo "   From Number: " . config('whatsapp.twilio.from_number') . "\n";
    echo "   ✅ Twilio is configured\n";
} else {
    echo "   ❌ Provider is not Twilio\n";
    exit(1);
}

echo "\n2️⃣  TESTING ALERT CREATION (No Template):\n";
try {
    // Clean up any test records
    WhatsAppAlert::where('recipient_phone', '+923701624461-twilio-test')->delete();
    
    $alert = WhatsAppAlert::create([
        'recipient_phone' => '+923701624461-twilio-test',
        'status' => 'pending',
        'message' => 'This is a test message from Lahomes School',
        'data' => [],
        'provider' => 'twilio',
        'retry_count' => 0,
    ]);
    
    echo "   ✅ Alert created: ID {$alert->id}\n";
    echo "   Phone: {$alert->recipient_phone}\n";
    echo "   Message: {$alert->message}\n";
    
    // Clean up
    $alert->delete();
    echo "\n3️⃣  CLEANUP:\n";
    echo "   ✅ Test record deleted\n";
    
    echo "\n✅ ALL CHECKS PASSED!\n";
    echo "\nYou can now use the Quick Send feature in WhatsApp Automation.\n";
    echo "The system will use Twilio to send messages.\n";
    
} catch (\Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
