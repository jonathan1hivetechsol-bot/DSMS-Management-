<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Log;
use App\Models\WhatsAppAlert;

echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║   Local Evolution API Simulator - Quick Send Test          ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

// Simulate sending without Evolution API
$phone = '+923701624461';
$message = 'یہ test message ہے';

echo "📱 Testing Quick Send (Local Simulator):\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "Phone: {$phone}\n";
echo "Message: {$message}\n\n";

// Create alert like it's being sent
try {
    $alert = WhatsAppAlert::create([
        'recipient_phone' => $phone,
        'status' => 'sent', // Marked as sent for local testing
        'message' => $message,
        'data' => [],
        'provider' => 'evolution_mock',
        'provider_message_id' => 'mock_' . time() . '_' . uniqid(),
        'retry_count' => 0,
        'sent_at' => now(),
    ]);
    
    Log::channel('whatsapp')->info('Mock Evolution API: Message queued', [
        'alert_id' => $alert->id,
        'phone' => $phone,
        'mode' => 'local_simulator',
        'note' => 'This is a local development simulation. No actual WhatsApp message sent.',
    ]);
    
    echo "✅ Alert Created Successfully!\n";
    echo "   Alert ID: {$alert->id}\n";
    echo "   Message ID: {$alert->provider_message_id}\n";
    echo "   Status: {$alert->status}\n\n";
    
    echo "📊 Database میں یہ message شامل ہو گیا:\n";
    echo "   - Dashboard میں دیکھا جا سکتا ہے\n";
    echo "   - لیکن حقیقت میں WhatsApp نہیں گیا (local test mode)\n\n";
    
    echo "🔄 اب کیا کریں:\n";
    echo "━━━━━━━━━━━━━━━━\n";
    echo "1. Evolution API Server setup کریں:\n";
    echo "   docker run -p 8080:8080 -e INSTANCE_NAME=MeraWhatsApp evolution-api\n\n";
    echo "2. صحیح token اور instance name .env میں ڈالیں\n\n";
    echo "3. یا Twilio استعمال کریں:\n";
    echo "   WHATSAPP_PROVIDER=twilio\n";
    echo "   php artisan cache:clear\n\n";
    
} catch (\Exception $e) {
    echo "❌ Error: {$e->getMessage()}\n";
}

echo "\n";
