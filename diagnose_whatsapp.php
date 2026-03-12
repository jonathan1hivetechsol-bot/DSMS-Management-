<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

echo "=== WhatsApp Automation - Diagnostics & Quick Fix ===\n\n";

echo "1️⃣  CURRENT CONFIGURATION:\n";
$provider = config('whatsapp.provider');
$apiUrl = config('whatsapp.evolution.api_url');
$apiToken = config('whatsapp.evolution.api_token');
$instanceName = config('whatsapp.evolution.instance_name');

echo "   Provider: {$provider}\n";
echo "   API URL: {$apiUrl}\n";
echo "   Instance Name: {$instanceName}\n";
echo "   Token: " . (substr($apiToken, 0, 8) . '...' . substr($apiToken, -4)) . "\n";

echo "\n2️⃣  TESTING EVOLUTION API CONNECTION:\n";
try {
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $apiToken,
        'Content-Type' => 'application/json',
    ])->timeout(10)->get("{$apiUrl}/instance/info/{$instanceName}");

    echo "   Status: {$response->status()}\n";
    
    if ($response->status() === 401) {
        echo "   ❌ ERROR: Authentication failed (401 Unauthorized)\n";
        echo "      Possible causes:\n";
        echo "      - Token is incorrect or expired\n";
        echo "      - Instance name doesn't exist\n";
        echo "      - Evolution API server not running\n";
    } elseif ($response->successful()) {
        echo "   ✅ Connected successfully!\n";
        echo "   Response: " . json_encode($response->json(), JSON_PRETTY_PRINT) . "\n";
    } else {
        echo "   ❌ ERROR: {$response->status()}\n";
        echo "   Response: " . json_encode($response->json(), JSON_PRETTY_PRINT) . "\n";
    }
} catch (\Exception $e) {
    echo "   ❌ Connection failed: {$e->getMessage()}\n";
}

echo "\n3️⃣  RECOMMENDED SOLUTIONS:\n";
echo "\n   Option A: Use Twilio Sandbox (Quick Test - No Setup)\n";
echo "   =========================================\n";
echo "   1. Get free Twilio credits: https://www.twilio.com/try-twilio\n";
echo "   2. Update .env:\n";
echo "      WHATSAPP_PROVIDER=twilio\n";
echo "      TWILIO_ACCOUNT_SID=your_sid_here\n";
echo "      TWILIO_AUTH_TOKEN=your_token_here\n";
echo "      TWILIO_WHATSAPP_NUMBER=whatsapp:+15551234567\n";
echo "\n   Option B: Fix Evolution API\n";
echo "   ===========================\n";
echo "   1. Verify Evolution API is running at {$apiUrl}\n";
echo "   2. Check your token and instance name are correct\n";
echo "   3. Test with: curl -H 'Authorization: Bearer {$apiToken}' {$apiUrl}/instance/info/{$instanceName}\n";

echo "\n4️⃣  QUICK ACTION:\n";
echo "   To use Twilio instead, update .env and run:\n";
echo "   php artisan cache:clear && php artisan route:clear\n";
echo "\n";
