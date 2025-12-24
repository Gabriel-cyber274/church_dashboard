<?php

namespace App\Filament\Resources\Pledges\Widgets;

use App\Models\Pledge;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;

class PledgeDepositStatsWidget extends BaseWidget
{
    public Pledge $record;

    protected ?string $pollingInterval = '10s';




    protected function getStats(): array
    {
        if (!$this->record) {
            return [
                Stat::make('Pledge Amount', 'NGN 0.00')
                    ->description('Total pledge amount')
                    ->color('gray'),

                Stat::make('Total Deposits', 'NGN 0.00')
                    ->description('0 deposits made')
                    ->color('gray'),

                Stat::make('Balance', 'NGN 0.00')
                    ->description('0% paid')
                    ->color('gray'),
            ];
        }

        $pledgeAmount = $this->record->amount ?? 0;
        $totalDeposits = $this->record->deposits->where('status', 'completed')->sum('amount');
        $balance = $pledgeAmount - $totalDeposits;
        $percentagePaid = $pledgeAmount > 0 ? round(($totalDeposits / $pledgeAmount) * 100, 2) : 0;
        $depositCount = $this->record->deposits->where('status', 'completed')->count();

        // Determine colors based on status
        $balanceColor = match (true) {
            $balance <= 0 => 'success',
            $balance > 0 && $percentagePaid >= 50 => 'warning',
            default => 'danger',
        };

        $percentageColor = match (true) {
            $percentagePaid >= 100 => 'success',
            $percentagePaid >= 50 => 'warning',
            default => 'danger',
        };

        return [
            Stat::make('Pledge Amount', 'NGN ' . number_format($pledgeAmount, 2))
                ->description('Total pledge amount')
                ->color('primary')
                ->icon('heroicon-o-currency-dollar'),

            Stat::make('Total Deposits', 'NGN ' . number_format($totalDeposits, 2))
                ->description($depositCount . ' completed ' . str('deposit')->plural($depositCount))
                ->color('success')
                ->icon('heroicon-o-banknotes')
                ->chart($this->getDepositChartData())
                ->descriptionIcon($percentagePaid >= 100 ? 'heroicon-o-check-circle' : 'heroicon-o-arrow-trending-up'),

            Stat::make('Balance', 'NGN ' . number_format($balance, 2))
                ->description($percentagePaid . '% paid')
                ->color($balanceColor)
                ->icon($balance <= 0 ? 'heroicon-o-check-circle' : 'heroicon-o-clock')
                ->descriptionColor($percentageColor),
        ];
    }

    protected function getDepositChartData(): array
    {
        if (!$this->record) {
            return [0, 0, 0, 0, 0, 0, 0];
        }

        // Get deposits by month for the last 7 months
        $deposits = $this->record->deposits()
            ->where('status', 'completed')
            ->where('deposit_date', '>=', now()->subMonths(6)->startOfMonth())
            ->get()
            ->groupBy(function ($deposit) {
                return $deposit->deposit_date->format('Y-m');
            });

        $chartData = [];
        $currentMonth = now()->startOfMonth();

        for ($i = 6; $i >= 0; $i--) {
            $month = $currentMonth->copy()->subMonths($i);
            $monthKey = $month->format('Y-m');

            $monthlyTotal = isset($deposits[$monthKey])
                ? $deposits[$monthKey]->sum('amount')
                : 0;

            $chartData[] = $monthlyTotal;
        }

        return $chartData;
    }

    public static function canView(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole([
            'super_admin',
            'admin',
            'finance'
        ]);
    }
}
