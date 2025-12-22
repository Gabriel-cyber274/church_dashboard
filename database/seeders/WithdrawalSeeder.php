<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Withdrawal;
use Carbon\Carbon;

class WithdrawalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $withdrawals = [
            [
                'program_id' => 1,
                'amount' => 40000.00,
                'withdrawal_date' => Carbon::now()->subDays(6),
                'description' => 'Program logistics expenses',
            ],
            [
                'program_id' => 2,
                'amount' => 60000.00,
                'withdrawal_date' => Carbon::now()->subDays(4),
                'description' => 'Equipment purchase',
            ],
            [
                'program_id' => 1,
                'amount' => 25000.00,
                'withdrawal_date' => Carbon::now()->subDays(2),
                'description' => 'Transportation and feeding',
            ],
            [
                'program_id' => null,
                'amount' => 15000.00,
                'withdrawal_date' => Carbon::now()->subDay(),
                'description' => 'General church expenses',
            ],
        ];

        foreach ($withdrawals as $withdrawal) {
            Withdrawal::create($withdrawal);
        }
    }
}
