<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Create only the super_admin role and user.
     */
    public function run(): void
    {
        $superAdminRole = Role::updateOrCreate(
            ['name' => 'super_admin'],
            [
                'description' => 'System Super Administrator with full access',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );

        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@church.com'],
            [
                'name' => 'Super Administrator',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password123'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );

        $superAdmin->roles()->sync([$superAdminRole->id]);

        $this->command->info('Super Admin: superadmin@church.com / password123 (super_admin role)');
    }
}
