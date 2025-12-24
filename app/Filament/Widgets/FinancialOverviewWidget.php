<?php

namespace App\Filament\Widgets;

use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Offering;
use App\Models\Tithe;
use Filament\Widgets\ChartWidget;

class FinancialOverviewWidget extends ChartWidget
{
    protected static ?int $sort = 3;

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
        return 'Financial Overview (Last 6 Months)';
    }

    protected function getData(): array
    {
        $labels = [];
        $depositsData = [];
        $withdrawalsData = [];
        $offeringsData = [];
        $tithesData = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();

            $labels[] = $month->format('M Y');
            $depositsData[] = Deposit::whereBetween('deposit_date', [$start, $end])->where('status', 'completed')->sum('amount');
            $withdrawalsData[] = Withdrawal::whereBetween('withdrawal_date', [$start, $end])->where('status', 'completed')->sum('amount');
            $offeringsData[] = Offering::whereBetween('offering_date', [$start, $end])->where('status', 'completed')->sum('amount');
            $tithesData[] = Tithe::whereBetween('tithe_date', [$start, $end])->where('status', 'completed')->sum('amount');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Deposits',
                    'data' => $depositsData,
                    'backgroundColor' => '#3B82F6',
                ],
                [
                    'label' => 'Withdrawals',
                    'data' => $withdrawalsData,
                    'backgroundColor' => '#EF4444',
                ],
                [
                    'label' => 'Offerings',
                    'data' => $offeringsData,
                    'backgroundColor' => '#10B981',
                ],
                [
                    'label' => 'Gratitudes',
                    'data' => $tithesData,
                    'backgroundColor' => '#F59E0B',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
