<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use Filament\Widgets\ChartWidget;

class MemberGenderChartWidget extends ChartWidget
{
    protected static ?int $sort = 9;

    public function getHeading(): string
    {
        return 'Gender Distribution';
    }

    protected function getData(): array
    {
        $maleCount = Member::where('gender', 'male')->count();
        $femaleCount = Member::where('gender', 'female')->count();
        $otherCount = Member::where('gender', 'other')->orWhereNull('gender')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Gender Distribution',
                    'data' => [$maleCount, $femaleCount, $otherCount],
                    'backgroundColor' => ['#3B82F6', '#EC4899', '#10B981'],
                    'borderWidth' => 0,
                ],
            ],
            'labels' => ['Male', 'Female', 'Other/Not Specified'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
