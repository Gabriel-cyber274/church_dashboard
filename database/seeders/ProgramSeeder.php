<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Program;
use Carbon\Carbon;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = [
            [
                'name' => 'Youth Empowerment Program',
                'description' => 'A program to empower young people with skills and knowledge.',
                'start_date' => Carbon::now()->addDays(1),
                'end_date' => Carbon::now()->addDays(30),
                'location' => 'Lagos',
                'is_budgeted' => true,
                'budget' => 500000.00,
            ],
            [
                'name' => 'Community Health Initiative',
                'description' => 'Promoting health awareness and access in local communities.',
                'start_date' => Carbon::now()->addDays(5),
                'end_date' => Carbon::now()->addDays(60),
                'location' => 'Abuja',
                'is_budgeted' => true,
                'budget' => 750000.00,
            ],
            [
                'name' => 'Environmental Awareness Campaign',
                'description' => 'Educating people about environmental protection and sustainability.',
                'start_date' => Carbon::now()->addDays(10),
                'end_date' => Carbon::now()->addDays(40),
                'location' => 'Port Harcourt',
                'is_budgeted' => false,
                'budget' => null,
            ],
        ];

        foreach ($programs as $program) {
            Program::create($program);
        }
    }
}
