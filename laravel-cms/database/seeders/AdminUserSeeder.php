<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        if (!$adminRole) {
            return;
        }

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin DECOM',
                'password' => 'Admin@123456',
                'role_id' => $adminRole->id,
                'email_verified_at' => now(),
            ]
        );
    }
}
