<?php

/**
 * ============================================================================
 * WhatsApp Evolution API - Quick Testing Guide
 * ============================================================================
 * 
 * Test file to quickly verify your Evolution API integration
 * Place this in your project root and run: php test_evolution_api.php
 */

// Test 1: Check configuration
echo "\n=== TEST 1: Configuration Check ===\n";
$evolutionUrl = getenv('EVOLUTION_API_URL');
$evolutionToken = getenv('EVOLUTION_API_TOKEN');
$webhookSecret = getenv('EVOLUTION_WEBHOOK_SECRET');
$instanceName = getenv('EVOLUTION_INSTANCE_NAME');

echo "✓ EVOLUTION_API_URL: " . ($evolutionUrl ? "✅ Set" : "❌ Missing") . "\n";
echo "✓ EVOLUTION_API_TOKEN: " . ($evolutionToken ? "✅ Set" : "❌ Missing") . "\n";
echo "✓ EVOLUTION_WEBHOOK_SECRET: " . ($webhookSecret ? "✅ Set" : "❌ Missing") . "\n";
echo "✓ EVOLUTION_INSTANCE_NAME: " . ($instanceName ? "✅ Set" : "❌ Missing") . "\n";

// Test 2: Check files exist
echo "\n=== TEST 2: Files Check ===\n";
$files = [
    'app/Services/EvolutionService.php',
    'app/Http/Controllers/WhatsAppWebhookController.php',
    'config/whatsapp.php',
];

foreach ($files as $file) {
    $exists = file_exists($file);
    echo "✓ $file: " . ($exists ? "✅ Exists" : "❌ Missing") . "\n";
}

// Test 3: Database check
echo "\n=== TEST 3: Database Check ===\n";
echo "Run these commands:\n";
echo "  php artisan migrate\n";
echo "  php artisan tinker\n";
echo "  >>> App\\Models\\WhatsAppAlert::count()\n";

// Test 4: Test webhook endpoint
echo "\n=== TEST 4: Webhook Endpoint Test ===\n";
echo "Run this command:\n";
echo "  curl http://localhost:8000/webhook/whatsapp/test\n";
echo "\nExpected response:\n";
echo "  {\n";
echo "    \"success\": true,\n";
echo "    \"webhook_url\": \"...\",\n";
echo "    \"instance\": \"...\",\n";
echo "    \"time\": \"...\"\n";
echo "  }\n";

// Test 5: Test Evolution Service connection
echo "\n=== TEST 5: Evolution Service Connection ===\n";
echo "Run this in tinker:\n";
echo "  >>> \$service = app(App\\Services\\EvolutionService::class)\n";
echo "  >>> \$service->testConnection()\n";
echo "\nExpected: true (if Evolution API is accessible)\n";

// Test 6: Send test message
echo "\n=== TEST 6: Send Test Message ===\n";
echo "Run this in tinker:\n";
echo "  >>> \$service = app(App\\Services\\WhatsAppService::class)\n";
echo "  >>> \$alert = \$service->sendMessage('+923001234567', 'Test message')\n";
echo "  >>> \$alert->status\n";
echo "\nExpected: 'sent' or 'pending'\n";

// Test 7: Check Evolution API instance
echo "\n=== TEST 7: Evolution Instance Info ===\n";
echo "Run this in tinker:\n";
echo "  >>> \$service = app(App\\Services\\EvolutionService::class)\n";
echo "  >>> \$info = \$service->getInstanceInfo()\n";
echo "  >>> print_r(\$info)\n";

// Test 8: Webhook signature verification
echo "\n=== TEST 8: Webhook Security ===\n";
echo "Webhook signature is verified using:\n";
echo "  HMAC-SHA256 hash of payload with webhook secret\n";
echo "  Header: X-Webhook-Signature\n";
echo "  Secret: EVOLUTION_WEBHOOK_SECRET from .env\n";

// Test 9: Message status tracking
echo "\n=== TEST 9: Message Status Tracking ===\n";
echo "Check status updates:\n";
echo "  >>> App\\Models\\WhatsAppAlert::latest()->first()\n";
echo "  >>> \$alert->status\n";
echo "  >>> \$alert->sent_at\n";
echo "  >>> \$alert->delivered_at\n";
echo "  >>> \$alert->read_at\n";

// Test 10: View logs
echo "\n=== TEST 10: Check Logs ===\n";
echo "View WhatsApp logs:\n";
echo "  tail -f storage/logs/whatsapp.log\n";

echo "\n=== TESTING COMPLETE ===\n";
echo "For detailed guide, see: WHATSAPP_EVOLUTION_API_SETUP.md\n\n";
