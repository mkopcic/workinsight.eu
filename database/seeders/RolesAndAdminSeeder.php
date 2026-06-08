<?php

namespace Database\Seeders;

use App\Domain\Access\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RolesAndAdminSeeder extends Seeder
{
    /**
     * Seed the 5 application roles and one verified dev user per role.
     * Dev lozinka za sve: Admin1234!
     */
    public function run(): void
    {
        $roles = ['admin', 'customer', 'company', 'driver', 'kitchen'];

        foreach ($roles as $role) {
            Role::findOrCreate($role, 'web');
        }

        $users = [
            ['admin@workinsight.eu', 'WorkInsight Admin', 'admin'],
            ['customer@workinsight.eu', 'Test Customer', 'customer'],
            ['company@workinsight.eu', 'Test Company', 'company'],
            ['driver@workinsight.eu', 'Test Driver', 'driver'],
            ['kitchen@workinsight.eu', 'Test Kitchen', 'kitchen'],
        ];

        foreach ($users as [$email, $name, $role]) {
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make('Admin1234!'),
                    'status' => 'active',
                    'email_verified_at' => now(),
                ],
            );

            $user->syncRoles([$role]);
        }
    }
}
