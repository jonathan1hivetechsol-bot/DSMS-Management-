<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;
use App\Services\EvolutionService;

echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║   WhatsApp Evolution API - تفصیلی ڈایاگنوسٹک REPORt       ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

// ===== CONFIG CHECK =====
echo "📋 CONFIGURATION:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━\n";
$provider = config('whatsapp.provider');
$apiUrl = config('whatsapp.evolution.api_url');
$apiToken = config('whatsapp.evolution.api_token');
$instance = config('whatsapp.evolution.instance_name');

echo "Provider: {$provider}\n";
echo "API URL: {$apiUrl}\n";
echo "Instance: {$instance}\n";
echo "Token: " . (strlen($apiToken) > 0 ? "✅ موجود (" . strlen($apiToken) . " chars)" : "❌ خالی") . "\n";

// ===== SERVER CONNECTIVITY =====
echo "\n🌐 SERVER CONNECTIVITY:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━\n";

try {
    $response = Http::timeout(5)->get("{$apiUrl}/");
    echo "✅ Server is accessible (Status: {$response->status()})\n";
} catch (\Exception $e) {
    echo "❌ Cannot reach {$apiUrl}\n";
    echo "   Error: {$e->getMessage()}\n";
    echo "\n   حل: Evolution API server شروع کریں:\n";
    echo "   docker run -p 8080:8080 evolution-api\n";
    echo "   یا: npm start (اگر locally installed ہو)\n";
}

// ===== AUTHENTICATION TEST =====
echo "\n🔐 AUTHENTICATION TEST:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━\n";

$testEndpoint = "{$apiUrl}/message/sendText/{$instance}";
echo "Endpoint: {$testEndpoint}\n\n";

echo "Testing authentication methods:\n\n";

// Method 1: Bearer Token
echo "1️⃣  Bearer Token (Authorization: Bearer {token}):\n";
try {
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $apiToken,
        'Content-Type' => 'application/json',
    ])->timeout(5)->post($testEndpoint, [
        'number' => '923701624461',
        'text' => 'Test',
    ]);
    
    echo "   Status: {$response->status()}\n";
    if ($response->status() === 200 || $response->status() === 201) {
        echo "   ✅ WORKING!\n";
    } elseif ($response->status() === 401) {
        echo "   ❌ Unauthorized - Token may be invalid\n";
    }
} catch (\Exception $e) {
    echo "   ❌ Error: {$e->getMessage()}\n";
}

// Method 2: API-KEY header
echo "\n2️⃣  API-KEY Header (X-API-Key: {token}):\n";
try {
    $response = Http::withHeaders([
        'X-API-Key' => $apiToken,
        'Content-Type' => 'application/json',
    ])->timeout(5)->post($testEndpoint, [
        'number' => '923701624461',
        'text' => 'Test',
    ]);
    
    echo "   Status: {$response->status()}\n";
    if ($response->status() === 200 || $response->status() === 201) {
        echo "   ✅ WORKING!\n";
    }
} catch (\Exception $e) {
    echo "   ❌ Error\n";
}

// ===== SOLUTIONS =====
echo "\n\n✨ 3 SOLUTIONS:\n";
echo "━━━━━━━━━━━━━━━━\n";

echo "\n🔧 حل #1: Evolution API خود setup کریں\n";
echo "   ──────────────────────────────────\n";
echo "   1. Download/Install:\n";
echo "      docker run -e INSTANCE_NAME=MeraWhatsApp \\\n";
echo "                 -p 8080:8080 \\\n";
echo "                 ghcr.io/evolution-api/evolution-api:latest\n";
echo "\n   2. یا: npm install -g @evolution-api/api && evolution-api start\n";
echo "\n   3. Instance بنائیں اور proper token حاصل کریں\n";

echo "\n💡 حل #2: Twilio استعمال کریں (فوری کام)\n";
echo "   ────────────────────────────────\n";
echo "   Update .env:\n";
echo "      WHATSAPP_PROVIDER=twilio\n";
echo "   Twilio پہلے سے configured ہے۔\n";
echo "   php artisan cache:clear\n";

echo "\n🧪 حل #3: میں Evolution API simulator بناتا ہوں\n";
echo "   ────────────────────────────────────────\n";
echo "   Local testing کے لیے mock server\n";

echo "\n\n📞 QUICK TEST:\n";
echo "━━━━━━━━━━━━━\n";
echo "اگر نیچے دیے گئے میں سے کوئی بھی message نہیں آ رہا تو:\n";
echo "   1. Evolution API server شروع کریں\n";
echo "   2. یا Twilio پر switch کریں\n";
echo "   3. یا debug logs دیکھیں: tail -f storage/logs/whatsapp.log\n";

echo "\n";
