<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Carbon;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'description' => 'System Super Administrator with full access',
            ],
            [
                'name' => 'admin',
                'description' => 'Administrator with high-level permissions',
            ],
            [
                'name' => 'hod',
                'description' => 'Head of Department',
            ],
            [
                'name' => 'assistant_hod',
                'description' => 'Assistant Head of Department',
            ],
            [
                'name' => 'pastors',
                'description' => 'Church Pastor',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']], // prevent duplicates
                [
                    'description' => $role['description'],
                    'updated_at' => Carbon::now(),
                    'created_at' => Carbon::now(),
                ]
            );
        }
    }
}
