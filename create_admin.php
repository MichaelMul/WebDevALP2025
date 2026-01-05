<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Delete existing admin if any
User::where('email', 'admin@kayaboys.com')->delete();

// Create fresh admin user
$admin = User::create([
    'name' => 'Admin Owner',
    'email' => 'admin@kayaboys.com',
    'password' => Hash::make('admin123456'),
    'role' => 'admin',
    'email_verified_at' => now(),
    'phone' => '08123456789',
]);

echo "✅ Admin user created successfully!\n";
echo "📧 Email: " . $admin->email . "\n";
echo "🔐 Password: admin123456\n";
echo "🎯 Role: " . $admin->role . "\n";
echo "🔗 Login at: /login\n";
echo "📊 Admin panel at: /admin/dashboard\n";
