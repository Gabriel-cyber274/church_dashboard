<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tithe;
use Carbon\Carbon;

class TitheSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tithes = [
            [
                'program_id' => 1,
                'amount' => 30000.00,
                'tithe_date' => Carbon::now()->subDays(10),
                'description' => 'Monthly tithe',
            ],
            [
                'program_id' => 2,
                'amount' => 45000.00,
                'tithe_date' => Carbon::now()->subDays(6),
                'description' => 'Special program tithe',
            ],
            [
                'program_id' => 1,
                'amount' => 25000.00,
                'tithe_date' => Carbon::now()->subDays(3),
                'description' => 'Mid-month tithe',
            ],
            [
                'program_id' => null,
                'amount' => 20000.00,
                'tithe_date' => Carbon::now()->subDay(),
                'description' => 'General tithe (no program)',
            ],
        ];

        foreach ($tithes as $tithe) {
            Tithe::create($tithe);
        }
    }
}
