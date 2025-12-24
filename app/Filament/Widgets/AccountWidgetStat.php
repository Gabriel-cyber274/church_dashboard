<?php

namespace App\Filament\Widgets;

use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Offering;
use App\Models\Tithe;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class AccountWidgetStat extends BaseWidget
{
    // protected static ?string $pollingInterval = '30s';
    protected static ?int $sort = 1;

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
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfYear = $now->copy()->startOfYear();

        // Get current month totals
        $currentMonthDeposits = Deposit::whereBetween('deposit_date', [$startOfMonth, $now])->where('status', 'completed')->sum('amount');
        $currentMonthWithdrawals = Withdrawal::whereBetween('withdrawal_date', [$startOfMonth, $now])->where('status', 'completed')->sum('amount');
        $currentMonthOfferings = Offering::whereBetween('offering_date', [$startOfMonth, $now])->where('status', 'completed')->sum('amount');
        $currentMonthTithes = Tithe::whereBetween('tithe_date', [$startOfMonth, $now])->where('status', 'completed')->sum('amount');

        // Get year-to-date totals
        $ytdDeposits = Deposit::whereBetween('deposit_date', [$startOfYear, $now])->where('status', 'completed')->sum('amount');
        $ytdWithdrawals = Withdrawal::whereBetween('withdrawal_date', [$startOfYear, $now])->where('status', 'completed')->sum('amount');
        $ytdOfferings = Offering::whereBetween('offering_date', [$startOfYear, $now])->where('status', 'completed')->sum('amount');
        $ytdTithes = Tithe::whereBetween('tithe_date', [$startOfYear, $now])->where('status', 'completed')->sum('amount');

        // Calculate total balance
        $totalIncome = $ytdDeposits + $ytdOfferings + $ytdTithes;
        $totalBalance = $totalIncome - $ytdWithdrawals;

        return [
            Stat::make('Total Account Balance', '₦' . number_format($totalBalance, 2))
                ->description('Net balance in church account')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('success')
                ->chart($this->getBalanceTrend()),

            Stat::make('Month Income', '₦' . number_format($currentMonthDeposits + $currentMonthOfferings + $currentMonthTithes, 2))
                ->description('This month\'s total income')
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color('info'),

            Stat::make('Month Expenses', '₦' . number_format($currentMonthWithdrawals, 2))
                ->description('This month\'s withdrawals')
                ->descriptionIcon('heroicon-o-arrow-trending-down')
                ->color('danger'),
        ];
    }

    protected function getBalanceTrend(): array
    {
        // Generate trend data for the last 6 months
        $trend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();

            $deposits = Deposit::whereBetween('deposit_date', [$start, $end])->where('status', 'completed')->sum('amount');
            $offerings = Offering::whereBetween('offering_date', [$start, $end])->where('status', 'completed')->sum('amount');
            $tithes = Tithe::whereBetween('tithe_date', [$start, $end])->where('status', 'completed')->sum('amount');
            $withdrawals = Withdrawal::whereBetween('withdrawal_date', [$start, $end])->where('status', 'completed')->sum('amount');

            $balance = ($deposits + $offerings + $tithes) - $withdrawals;
            $trend[] = $balance;
        }

        return $trend;
    }
}
