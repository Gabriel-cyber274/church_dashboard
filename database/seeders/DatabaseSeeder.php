<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            // MemberSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            DepartmentSeeder::class,
            // MemberDepartmentSeeder::class,
            // BankSeeder::class,
            // ProgramSeeder::class,
            // ProgramCoordinatorSeeder::class,
            // PledgeSeeder::class,
            // TitheSeeder::class,
            // OfferingSeeder::class,
            // DepositSeeder::class,
            // WithdrawalSeeder::class,
            // ProgrammeAttendeeSeeder::class,
            // ProjectSeeder::class,
        ]);
    }
}
