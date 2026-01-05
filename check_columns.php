<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;

$columns = Schema::getColumnListing('users');
echo "Users table columns:\n";
foreach($columns as $column) {
    echo "  - " . $column . "\n";
}

// Check if role column exists
if(in_array('role', $columns)) {
    echo "\n✅ 'role' column EXISTS\n";
} else {
    echo "\n❌ 'role' column MISSING - adding it now...\n";
    Schema::table('users', function($table) {
        $table->enum('role', ['admin', 'customer', 'courier', 'guest'])->default('customer')->after('password');
    });
    echo "✅ 'role' column added successfully\n";
}
