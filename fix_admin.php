<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = \App\Models\User::where('email', 'admin@kayaboys.com')->first();
if($user) {
    $user->role = 'admin';
    $user->save();
    echo "Admin role updated successfully!\n";
    echo "Email: " . $user->email . "\n";
    echo "Role: " . $user->role . "\n";
} else {
    echo "Admin user not found!\n";
}
