<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pledge;
use Carbon\Carbon;

class PledgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pledges = [
            [
                'member_id' => 1,
                'program_id' => 1,
                'amount' => 10000.00,
                'pledge_date' => Carbon::now()->subDays(5),
                'status' => 'pending',
                'name' => 'John Doe',
                'phone_number' => '08012345678',
            ],
            [
                'member_id' => 2,
                'program_id' => 2,
                'amount' => 25000.00,
                'pledge_date' => Carbon::now()->subDays(3),
                'status' => 'approved',
                'name' => 'Jane Smith',
                'phone_number' => '08098765432',
            ],
            [
                'member_id' => 3,
                'program_id' => 1,
                'amount' => 5000.00,
                'pledge_date' => Carbon::now()->subDays(2),
                'status' => 'rejected',
                'name' => 'Michael Johnson',
                'phone_number' => '08123456789',
            ],
            [
                'member_id' => null,
                'program_id' => 3,
                'amount' => 15000.00,
                'pledge_date' => Carbon::now()->subDay(),
                'status' => 'pending',
                'name' => 'Anonymous Donor',
                'phone_number' => null,
            ],
        ];

        foreach ($pledges as $pledge) {
            Pledge::create($pledge);
        }
    }
}
