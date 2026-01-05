<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::firstOrCreate(
            ['email' => 'admin@kayaboys.com'],
            [
                'name' => 'Admin Owner',
                'password' => Hash::make('admin123456'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'phone' => '08123456789',
            ]
        );

        $this->command->info('✅ Admin account created successfully!');
        $this->command->info('📧 Email: admin@kayaboys.com');
        $this->command->info('🔐 Password: admin123456');
        $this->command->info('🔗 Access at: /admin/dashboard');
    }
}
