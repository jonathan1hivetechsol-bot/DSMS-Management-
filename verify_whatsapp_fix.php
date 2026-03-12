<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\WhatsAppAlert;
use Illuminate\Support\Facades\Schema;

echo "=== WhatsApp Quick Send - Fix Verification ===\n\n";

// 1. Check database schema
echo "1️⃣  DATABASE SCHEMA CHECK:\n";
$columns = Schema::getColumns('whats_app_alerts');
$template_id_col = collect($columns)->firstWhere('name', 'template_id');

if ($template_id_col) {
    $nullable = $template_id_col['nullable'] ?? false;
    echo "   template_id nullable: " . ($nullable ? "✅ YES" : "❌ NO") . "\n";
} else {
    echo "   ❌ template_id column not found!\n";
    exit(1);
}

// 2. Test creating an alert without template_id
echo "\n2️⃣  TEST CREATING ALERT WITHOUT TEMPLATE:\n";
try {
    // Delete any test record first
    WhatsAppAlert::where('recipient_phone', '+923701624461-test')->delete();
    
    $alert = WhatsAppAlert::create([
        'recipient_phone' => '+923701624461-test',
        'status' => 'pending',
        'message' => 'Test message without template',
        'data' => [],
        'provider' => 'evolution',
        'retry_count' => 0,
    ]);
    
    echo "   ✅ Alert created successfully!\n";
    echo "   ID: {$alert->id}\n";
    echo "   Phone: {$alert->recipient_phone}\n";
    echo "   template_id: " . ($alert->template_id ?? 'NULL') . "\n";
    
    // Clean up
    $alert->delete();
    echo "\n3️⃣  CLEANUP:\n";
    echo "   ✅ Test record deleted\n";
    
} catch (\Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n✅ All checks passed! Quick send should now work.\n";
