<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Offering;
use Carbon\Carbon;

class OfferingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $offerings = [
            [
                'program_id' => 1,
                'amount' => 20000.00,
                'offering_date' => Carbon::now()->subDays(7),
                'description' => 'Sunday service offering',
            ],
            [
                'program_id' => 2,
                'amount' => 50000.00,
                'offering_date' => Carbon::now()->subDays(4),
                'description' => 'Special fundraising service',
            ],
            [
                'program_id' => 1,
                'amount' => 15000.00,
                'offering_date' => Carbon::now()->subDays(2),
                'description' => 'Midweek service offering',
            ],
            [
                'program_id' => null,
                'amount' => 10000.00,
                'offering_date' => Carbon::now()->subDay(),
                'description' => 'General offering (no program)',
            ],
        ];

        foreach ($offerings as $offering) {
            Offering::create($offering);
        }
    }
}
