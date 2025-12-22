<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramCoordinatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Example associations of programs and members
        $coordinators = [
            ['program_id' => 1, 'member_id' => 1],
            ['program_id' => 1, 'member_id' => 2],
            ['program_id' => 2, 'member_id' => 3],
            ['program_id' => 3, 'member_id' => 4],
            ['program_id' => 2, 'member_id' => 1],
        ];

        foreach ($coordinators as $coordinator) {
            DB::table('program_coordinators')->insert([
                'program_id' => $coordinator['program_id'],
                'member_id' => $coordinator['member_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
