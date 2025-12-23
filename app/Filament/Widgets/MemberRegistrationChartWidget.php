<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use Filament\Widgets\ChartWidget;

class MemberRegistrationChartWidget extends ChartWidget
{
    protected static ?int $sort = 8;

    public function getHeading(): string
    {
        return 'Member Registration Trend';
    }

    protected function getData(): array
    {
        $labels = [];
        $data = [];

        // Last 12 months
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = $month->format('M Y');

            $count = Member::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $data[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'New Members',
                    'data' => $data,
                    'borderColor' => '#3B82F6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
