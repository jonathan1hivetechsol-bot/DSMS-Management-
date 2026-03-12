<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get the admin user
$admin = \App\Models\User::where('email', 'admin@school.com')->first();

echo "=== ADMIN USER DEBUG ===\n";
echo "User ID: " . $admin->id . "\n";
echo "User Name: " . $admin->name . "\n";
echo "User Email: " . $admin->email . "\n";
echo "User Role: " . $admin->role . "\n";
echo "User Role Type: " . gettype($admin->role) . "\n";

// Authenticate the user
auth()->login($admin);

echo "\n=== AUTH STATUS ===\n";
echo "Is Authenticated: " . (auth()->check() ? 'YES' : 'NO') . "\n";
echo "Authenticated User: " . auth()->user()->email . "\n";
echo "Authenticated User Role: " . auth()->user()->role . "\n";

// Test the gate
echo "\n=== GATE CHECKS ===\n";
echo "Gate::allows('view_all_students'): " . (\Illuminate\Support\Facades\Gate::allows('view_all_students') ? 'YES' : 'NO') . "\n";
echo "Gate::allows('manage_students'): " . (\Illuminate\Support\Facades\Gate::allows('manage_students') ? 'YES' : 'NO') . "\n";
echo "Gate::allows('admin_only'): " . (\Illuminate\Support\Facades\Gate::allows('admin_only') ? 'YES' : 'NO') . "\n";

// Test the role check directly
echo "\n=== ROLE COMPARISON ===\n";
echo "auth()->user()->role === 'admin': " . (auth()->user()->role === 'admin' ? 'YES' : 'NO') . "\n";
echo "in_array(auth()->user()->role, ['admin', 'teacher']): " . (in_array(auth()->user()->role, ['admin', 'teacher']) ? 'YES' : 'NO') . "\n";
