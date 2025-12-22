<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Deposit;
use Carbon\Carbon;

class DepositSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $deposits = [
            [
                'program_id' => 1,
                'member_id' => 1,
                'amount' => 100000.00,
                'deposit_date' => Carbon::now()->subDays(8),
                'description' => 'Program sponsorship deposit',
            ],
            [
                'program_id' => 2,
                'member_id' => 2,
                'amount' => 75000.00,
                'deposit_date' => Carbon::now()->subDays(5),
                'description' => 'Member special contribution',
            ],
            [
                'program_id' => 1,
                'member_id' => 3,
                'amount' => 50000.00,
                'deposit_date' => Carbon::now()->subDays(3),
                'description' => 'General program deposit',
            ],
            [
                'program_id' => null,
                'member_id' => null,
                'amount' => 30000.00,
                'deposit_date' => Carbon::now()->subDay(),
                'description' => 'Anonymous deposit',
            ],
        ];

        foreach ($deposits as $deposit) {
            Deposit::create($deposit);
        }
    }
}
