<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DEBUG: Twilio Configuration ===\n\n";

$accountSid = config('whatsapp.twilio.account_sid');
$authToken = config('whatsapp.twilio.auth_token');
$fromNumber = config('whatsapp.twilio.from_number');

echo "Account SID: " . substr($accountSid, 0, 5) . "...\n";
echo "Auth Token: " . substr($authToken, 0, 5) . "...\n";
echo "From Number: $fromNumber\n\n";

// Test phone number
$phone = "+923001234567";
$toNumber = "whatsapp:$phone";

echo "To Number: $toNumber\n\n";

// Make the API call
$url = "https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/Messages.json";

echo "API Endpoint: $url\n\n";

$payload = [
    'From' => $fromNumber,
    'To' => $toNumber,
    'Body' => 'Test message - السلام عليكم',
];

echo "Payload:\n";
print_r($payload);
echo "\n";

// Make request
$response = \Illuminate\Support\Facades\Http::withBasicAuth($accountSid, $authToken)
    ->post($url, $payload);

echo "Response Status: " . $response->status() . "\n";
echo "Response Body:\n";
print_r($response->json());
