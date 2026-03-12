<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

echo "=== Evolution API - Authentication Fix ===\n\n";

$apiUrl = config('whatsapp.evolution.api_url');
$token = config('whatsapp.evolution.api_token');
$instance = config('whatsapp.evolution.instance_name');

echo "Testing POST with different Auth Methods:\n\n";

$testPhone = '923701624461';
$testMessage = 'Test';
$endpoint = "{$apiUrl}/message/sendText/{$instance}";

// Method 1: Bearer Token in Authorization header
echo "1️⃣  Bearer Token (Authorization: Bearer {token}):\n";
try {
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $token,
        'Content-Type' => 'application/json',
    ])->timeout(5)->post($endpoint, [
        'number' => $testPhone,
        'text' => $testMessage,
    ]);
    
    echo "   Status: {$response->status()}\n";
    if ($response->status() !== 401) {
        echo "   Response: " . json_encode($response->json()) . "\n";
    }
} catch (\Exception $e) {
    echo "   Error: {$e->getMessage()}\n";
}

// Method 2: Simple API Key header
echo "\n2️⃣  API Key Header (X-API-Key: {token}):\n";
try {
    $response = Http::withHeaders([
        'X-API-Key' => $token,
        'Content-Type' => 'application/json',
    ])->timeout(5)->post($endpoint, [
        'number' => $testPhone,
        'text' => $testMessage,
    ]);
    
    echo "   Status: {$response->status()}\n";
    if ($response->status() !== 401) {
        echo "   Response: " . json_encode($response->json()) . "\n";
    }
} catch (\Exception $e) {
    echo "   Error: {$e->getMessage()}\n";
}

// Method 3: Token as query parameter
echo "\n3️⃣  Token as Query Param (?token={token}):\n";
try {
    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
    ])->timeout(5)->post($endpoint . '?token=' . $token, [
        'number' => $testPhone,
        'text' => $testMessage,
    ]);
    
    echo "   Status: {$response->status()}\n";
    if ($response->status() !== 401) {
        echo "   Response: " . json_encode($response->json()) . "\n";
    }
} catch (\Exception $e) {
    echo "   Error: {$e->getMessage()}\n";
}

// Method 4: No authentication
echo "\n4️⃣  No Authentication:\n";
try {
    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
    ])->timeout(5)->post($endpoint, [
        'number' => $testPhone,
        'text' => $testMessage,
    ]);
    
    echo "   Status: {$response->status()}\n";
    if ($response->status() !== 401) {
        echo "   Response: " . json_encode($response->json()) . "\n";
    }
} catch (\Exception $e) {
    echo "   Error: {$e->getMessage()}\n";
}

echo "\n=== NEXT STEP ===\n";
echo "Jis method se 2xx status aye, us ko EvolutionService mein implement karo.\n";
