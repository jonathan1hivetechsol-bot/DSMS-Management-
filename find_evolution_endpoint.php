<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

echo "=== Evolution API - Endpoint Finder ===\n\n";

$apiUrl = config('whatsapp.evolution.api_url');
$token = config('whatsapp.evolution.api_token');
$instance = config('whatsapp.evolution.instance_name');

echo "Configuration:\n";
echo "  URL: {$apiUrl}\n";
echo "  Instance: {$instance}\n";
echo "  Token: " . substr($token, 0, 8) . "...\n";

echo "\n🔍 Testing Different Endpoints:\n\n";

$endpoints = [
    "GET {$apiUrl}/message/sendText/{$instance}",
    "GET {$apiUrl}/instance/info/{$instance}",
    "GET {$apiUrl}/api/instance/info/{$instance}",
    "GET {$apiUrl}/instances/{$instance}",
    "POST {$apiUrl}/message/sendText/{$instance}",
    "POST {$apiUrl}/api/send/{$instance}",
    "POST {$apiUrl}/send",
];

$testPhone = '923701624461';
$testMessage = 'Test message';

foreach ($endpoints as $endpoint) {
    list($method, $url) = explode(' ', $endpoint, 2);
    
    try {
        if ($method === 'GET') {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->timeout(5)->get($url);
        } else {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ])->timeout(5)->post($url, [
                'number' => $testPhone,
                'text' => $testMessage,
            ]);
        }
        
        $status = $response->status();
        $statusText = match($status) {
            200, 201 => '✅ SUCCESS',
            400 => '⚠️ Bad Request',
            401 => '❌ Unauthorized',
            404 => '❌ Not Found',
            500 => '❌ Server Error',
            default => "Status: {$status}"
        };
        
        echo "{$method} {$url}\n";
        echo "  Status: {$statusText}\n";
        
        if ($status !== 404 && $status !== 401) {
            echo "  Response: " . json_encode($response->json(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
        }
        
    } catch (\Exception $e) {
        echo "{$method} {$url}\n";
        echo "  ❌ Error: {$e->getMessage()}\n";
    }
    
    echo "\n";
}

echo "=== RECOMMENDATIONS ===\n";
echo "Sahi endpoint dhondo aur .env mein update karo.\n";
echo "Agar koi bhi kaam nahi kare to:\n";
echo "1. Evolution API service chal raha hai check karo\n";
echo "2. Token valid hai verify karo\n";
echo "3. Instance name sahi hai confirm karo\n";
