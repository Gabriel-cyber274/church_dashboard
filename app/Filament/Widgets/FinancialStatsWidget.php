<?php

namespace App\Filament\Widgets;

use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Offering;
use App\Models\Tithe;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class FinancialStatsWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    public ?string $filter = 'month';

    public static function canView(): bool
    {
        return auth()->user()?->hasAnyRole([
            'super_admin',
            'admin',
            'finance',
        ]) ?? false;
    }

    protected function getStats(): array
    {
        $dateRange = $this->getDateRange();

        $deposits = Deposit::whereBetween('deposit_date', [$dateRange['start'], $dateRange['end']])->where('status', 'completed')->sum('amount');
        $withdrawals = Withdrawal::whereBetween('withdrawal_date', [$dateRange['start'], $dateRange['end']])->where('status', 'completed')->sum('amount');
        $offerings = Offering::whereBetween('offering_date', [$dateRange['start'], $dateRange['end']])->where('status', 'completed')->sum('amount');
        $tithes = Tithe::whereBetween('tithe_date', [$dateRange['start'], $dateRange['end']])->where('status', 'completed')->sum('amount');

        $totalIncome = $deposits + $offerings + $tithes;
        $netBalance = $totalIncome - $withdrawals;

        return [
            Stat::make('Total Deposits', '₦' . number_format($deposits, 2))
                ->description("For {$this->filter}")
                ->descriptionIcon('heroicon-o-credit-card')
                ->color('primary'),

            Stat::make('Total Withdrawals', '₦' . number_format($withdrawals, 2))
                ->description("For {$this->filter}")
                ->descriptionIcon('heroicon-o-arrow-up-tray')
                ->color('danger'),

            Stat::make('Total Offerings', '₦' . number_format($offerings, 2))
                ->description("For {$this->filter}")
                ->descriptionIcon('heroicon-o-heart')
                ->color('success'),

            Stat::make('Total Gratitudes', '₦' . number_format($tithes, 2))
                ->description("For {$this->filter}")
                ->descriptionIcon('heroicon-o-scale')
                ->color('warning'),

            Stat::make('Total Income', '₦' . number_format($totalIncome, 2))
                ->description("For {$this->filter}")
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color('info'),

            Stat::make('Net Balance', '₦' . number_format($netBalance, 2))
                ->description("For {$this->filter}")
                ->descriptionIcon('heroicon-o-banknotes')
                ->color($netBalance >= 0 ? 'success' : 'danger'),
        ];
    }

    protected function getDateRange(): array
    {
        return match ($this->filter) {
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

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'Last Week',
            'month' => 'This Month',
            'year' => 'This Year',
        ];
    }
}
