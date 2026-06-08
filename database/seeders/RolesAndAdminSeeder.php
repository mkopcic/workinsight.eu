<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RolesAndAdminSeeder extends Seeder
{
    /**
     * Seed the 5 application roles and a default admin user.
     */
    public function run(): void
    {
        $roles = ['admin', 'customer', 'company', 'driver', 'kitchen'];

        foreach ($roles as $role) {
            Role::findOrCreate($role, 'web');
        }

        $admin = User::firstOrCreate(
            ['email' => 'admin@workinsight.eu'],
            [
                'name' => 'WorkInsight Admin',
                'password' => Hash::make('Admin1234!'),
                'status' => 'active',
                'email_verified_at' => now(),
            ],
        );

        $admin->syncRoles(['admin']);
    }
}
