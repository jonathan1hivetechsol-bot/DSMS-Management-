<?php

// Bootstrap Laravel
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Check admin user
$admin = \App\Models\User::where('email', 'admin@school.com')->first();

if ($admin) {
    echo "Admin User Found:\n";
    echo "ID: " . $admin->id . "\n";
    echo "Name: " . $admin->name . "\n";
    echo "Email: " . $admin->email . "\n";
    echo "Role: " . $admin->role . "\n";
} else {
    echo "Admin user NOT found!\n";
}
