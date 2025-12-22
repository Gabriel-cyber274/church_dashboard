<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use Carbon\Carbon;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'name'        => 'Community Outreach Program',
                'description' => 'A project focused on community development and outreach.',
                'deadline'    => Carbon::now()->addMonths(2),
                'budget'      => 1500000.00,
            ],
            [
                'name'        => 'Youth Empowerment Initiative',
                'description' => 'Empowering youths through skill acquisition and mentorship.',
                'deadline'    => Carbon::now()->addMonths(4),
                'budget'      => 2750000.00,
            ],
            [
                'name'        => 'Church Renovation Project',
                'description' => 'Renovation and upgrading of church facilities.',
                'deadline'    => Carbon::now()->addMonths(6),
                'budget'      => 5200000.00,
            ],
            [
                'name'        => 'Annual Charity Event',
                'description' => 'Planning and execution of the annual charity fundraising event.',
                'deadline'    => Carbon::now()->addMonths(1),
                'budget'      => 800000.00,
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}
