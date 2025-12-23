<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class MemberAgeChartWidget extends ChartWidget
{
    protected static ?int $sort = 7;

    public function getHeading(): string
    {
        return 'Member Age Distribution';
    }

    protected function getData(): array
    {
        $ageGroups = [
            '0-17' => 0,
            '18-25' => 0,
            '26-35' => 0,
            '36-50' => 0,
            '51-65' => 0,
            '65+' => 0,
        ];

        Member::whereNotNull('date_of_birth')->chunk(100, function ($members) use (&$ageGroups) {
            foreach ($members as $member) {
                try {
                    // Handle both Carbon instances and string dates
                    $dob = $member->date_of_birth instanceof \Carbon\Carbon
                        ? $member->date_of_birth
                        : Carbon::parse($member->date_of_birth);

                    $age = $dob->diffInYears(now());

                    if ($age <= 17) $ageGroups['0-17']++;
                    elseif ($age <= 25) $ageGroups['18-25']++;
                    elseif ($age <= 35) $ageGroups['26-35']++;
                    elseif ($age <= 50) $ageGroups['36-50']++;
                    elseif ($age <= 65) $ageGroups['51-65']++;
                    else $ageGroups['65+']++;
                } catch (\Exception $e) {
                    // Skip invalid dates
                    continue;
                }
            }
        });

        return [
            'datasets' => [
                [
                    'label' => 'Members by Age Group',
                    'data' => array_values($ageGroups),
                    'backgroundColor' => ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899'],
                ],
            ],
            'labels' => array_keys($ageGroups),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
