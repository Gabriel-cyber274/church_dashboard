<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Member;
use App\Models\Department;

class MemberDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all members and departments
        $members = Member::all();
        $departments = Department::all();

        // Attach each member to random departments
        foreach ($members as $member) {
            // Pick 1-2 random departments for each member
            $randomDepartments = $departments->random(rand(1, 2))->pluck('id')->toArray();

            $member->departments()->sync($randomDepartments);
        }
    }
}
