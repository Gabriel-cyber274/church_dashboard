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
        // Create or get roles first
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $hodRole = Role::where('name', 'hod')->first();
        $financeRole = Role::where('name', 'Finance')->first(); // Changed from assistant_hod to Finance
        $pastorRole = Role::where('name', 'pastors')->first();

        // 1. Create Super Admin - ONE ROLE ONLY
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

        // Attach ONLY super_admin role
        $superAdmin->roles()->sync([$superAdminRole->id]);

        // 2. Create Regular Admin - ONE ROLE ONLY
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

        // Attach ONLY admin role
        $admin->roles()->sync([$adminRole->id]);

        // 3. Create Head of Department - ONE ROLE ONLY
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

        // Attach ONLY HOD role
        $hod->roles()->sync([$hodRole->id]);

        // 4. Create Finance User - ONE ROLE ONLY (replacing assistant_hod)
        $financeUser = User::updateOrCreate(
            ['email' => 'finance@church.com'],
            [
                'name' => 'Finance Officer',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password123'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );

        // Attach ONLY Finance role
        $financeUser->roles()->sync([$financeRole->id]);

        // 5. Create Pastor - ONE ROLE ONLY
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

        // Attach ONLY pastor role
        $pastor->roles()->sync([$pastorRole->id]);

        $this->command->info('Users seeded successfully!');
        $this->command->info('Super Admin: superadmin@church.com / password123 (super_admin role)');
        $this->command->info('Admin: admin@church.com / password123 (admin role)');
        $this->command->info('Head of Department: hod@church.com / password123 (hod role)');
        $this->command->info('Finance Officer: finance@church.com / password123 (Finance role)');
        $this->command->info('Pastor: pastor@church.com / password123 (pastors role)');
    }
}
