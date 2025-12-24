<?php

namespace App\Filament\Widgets;

use App\Models\Deposit;
use App\Models\Offering;
use App\Models\Tithe;
use Filament\Widgets\ChartWidget;

class IncomeChartWidget extends ChartWidget
{
    protected static ?int $sort = 2;
    // protected static ?string $pollingInterval = '60s';

    public static function canView(): bool
    {
        return auth()->user()?->hasAnyRole([
            'super_admin',
            'admin',
            'finance',
        ]) ?? false;
    }

    public function getHeading(): string
    {
        return 'Income Breakdown';
    }

    protected function getData(): array
    {
        // Get date range based on filter
        $dateRange = $this->getDateRange();
        $startDate = $dateRange['start'];
        $endDate = $dateRange['end'];

        $deposits = Deposit::whereBetween('deposit_date', [$startDate, $endDate])->where('status', 'completed')->sum('amount');
        $offerings = Offering::whereBetween('offering_date', [$startDate, $endDate])->where('status', 'completed')->sum('amount');
        $tithes = Tithe::whereBetween('tithe_date', [$startDate, $endDate])->where('status', 'completed')->sum('amount');

        return [
            'datasets' => [
                [
                    'label' => 'Income Breakdown',
                    'data' => [$deposits, $offerings, $tithes],
                    'backgroundColor' => ['#3B82F6', '#10B981', '#F59E0B'],
                ],
            ],
            'labels' => ['Deposits', 'Offerings', 'Gratitudes'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'Last week',
            'month' => 'This month',
            'year' => 'This year',
        ];
    }

    protected function getDateRange(): array
    {
        $filter = $this->filter;

        return match ($filter) {
            'today' => [
                'start' => now()->startOfDay(),
                'end' => now()->endOfDay(),
            ],
            'week' => [
                'start' => now()->subWeek()->startOfDay(),
                'end' => now()->endOfDay(),
            ],
            'month' => [
                'start' => now()->startOfMonth(),
                'end' => now()->endOfMonth(),
            ],
            'year' => [
                'start' => now()->startOfYear(),
                'end' => now()->endOfYear(),
            ],
            default => [
                'start' => now()->startOfMonth(),
                'end' => now()->endOfMonth(),
            ],
        };
    }
}
