<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

// Clear all sessions
\Illuminate\Support\Facades\DB::table('sessions')->truncate();

echo "All sessions cleared successfully!\n";
echo "User must log in again.\n";
