<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Program;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProgrammeAttendeeSeeder extends Seeder
{
    public function run(): void
    {
        $programs = Program::pluck('id');
        $members  = Member::pluck('id');

        // Seed members attending programs
        foreach ($programs as $programId) {
            $usedMembers = [];

            foreach ($members->random(min(5, $members->count())) as $memberId) {
                // Prevent duplicate (program_id + member_id)
                if (in_array($memberId, $usedMembers)) {
                    continue;
                }

                DB::table('programme_attendees')->insert([
                    'program_id'      => $programId,
                    'member_id'       => $memberId,
                    'attendance_time' => Carbon::now()->subMinutes(rand(1, 180))->format('H:i:s'),
                    'name'            => null,
                    'phone_number'    => null,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);

                $usedMembers[] = $memberId;
            }

            // Seed guest attendees (no member_id)
            for ($i = 0; $i < 2; $i++) {
                DB::table('programme_attendees')->insert([
                    'program_id'      => $programId,
                    'member_id'       => null,
                    'attendance_time' => Carbon::now()->subMinutes(rand(1, 180))->format('H:i:s'),
                    'name'            => fake()->name(),
                    'phone_number'    => fake()->phoneNumber(),
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            }
        }
    }
}
