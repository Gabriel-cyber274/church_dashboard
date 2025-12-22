<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing users (optional)
        // User::truncate();

        // Create or get roles first
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $hodRole = Role::where('name', 'hod')->first();
        $assistantHodRole = Role::where('name', 'assistant_hod')->first();
        $pastorRole = Role::where('name', 'pastors')->first();

        // 1. Create Super Admin
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@church.com'],
            [
                'name' => 'Super Administrator',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password123'), // Change this in production!
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );

        // Attach all roles to super admin (optional)
        $superAdmin->roles()->sync([
            $superAdminRole->id,
            $adminRole->id,
            $hodRole->id,
            $assistantHodRole->id,
            $pastorRole->id
        ]);

        // 2. Create Regular Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@church.com'],
            [
                'name' => 'Administrator',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password123'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );

        // Attach admin role
        $admin->roles()->sync([$adminRole->id]);

        // 3. Create Head of Department
        $hod = User::updateOrCreate(
            ['email' => 'hod@church.com'],
            [
                'name' => 'Head of Department',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password123'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );

        // Attach HOD and Assistant HOD roles (can have multiple)
        $hod->roles()->sync([$hodRole->id, $assistantHodRole->id]);

        // 4. Create Assistant HOD
        $assistantHod = User::updateOrCreate(
            ['email' => 'assistanthod@church.com'],
            [
                'name' => 'Assistant Head of Department',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password123'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );

        // Attach Assistant HOD role only
        $assistantHod->roles()->sync([$assistantHodRole->id]);

        // 5. Create Pastor
        $pastor = User::updateOrCreate(
            ['email' => 'pastor@church.com'],
            [
                'name' => 'Church Pastor',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password123'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );

        // Attach pastor role
        $pastor->roles()->sync([$pastorRole->id]);

        // 6. Create a user with multiple roles
        $multiRoleUser = User::updateOrCreate(
            ['email' => 'multi@church.com'],
            [
                'name' => 'Multi Role User',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password123'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );

        // Attach multiple roles
        $multiRoleUser->roles()->sync([$pastorRole->id, $hodRole->id]);

        $this->command->info('Users seeded successfully!');
        $this->command->info('Super Admin: superadmin@church.com / password123');
        $this->command->info('Admin: admin@church.com / password123');
        $this->command->info('Pastor: pastor@church.com / password123');
    }
}
