<?php

namespace App\Filament\Resources\Programs\Widgets;

use App\Models\Program;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class ProgramWithdrawalProgress extends BaseWidget
{
    public Program $record;

    protected ?string $pollingInterval = '10s';


    protected function getStats(): array
    {
        // Get withdrawal totals
        $totalWithdrawals = $this->record->withdrawals()->where('status', 'completed')->sum('amount');
        $withdrawalCount = $this->record->withdrawals()->where('status', 'completed')->count();
        $avgWithdrawal = $withdrawalCount > 0 ? $totalWithdrawals / $withdrawalCount : 0;

        // Get deposit and budget data
        $totalDeposits = $this->record->deposits()->where('status', 'completed')->sum('amount');
        $budget = $this->record->budget ?? 0;

        // Calculate available funds and withdrawal impact
        $availableFunds = $totalDeposits - $totalWithdrawals;
        $availablePercent = $totalDeposits > 0 ?
            ($availableFunds / $totalDeposits) * 100 : 0;

        // Calculate what percentage of budget remains after withdrawals
        $remainingBudgetPercent = $budget > 0 ?
            (($budget - $totalWithdrawals) / $budget) * 100 : 0;

        // Format numbers
        $formattedWithdrawals = Number::format($totalWithdrawals, 2);
        $formattedAvailable = Number::format($availableFunds, 2);
        $formattedAvailablePercent = Number::format($availablePercent, 1);
        $formattedRemainingBudget = Number::format($remainingBudgetPercent, 1);

        // Withdrawal frequency stats
        $thisMonthWithdrawals = $this->record->withdrawals()->where('status', 'completed')
            ->whereMonth('withdrawal_date', now()->month)
            ->sum('amount');

        $lastMonthWithdrawals = $this->record->withdrawals()->where('status', 'completed')
            ->whereMonth('withdrawal_date', now()->subMonth()->month)
            ->sum('amount');

        $monthOverMonthChange = $lastMonthWithdrawals > 0 ?
            (($thisMonthWithdrawals - $lastMonthWithdrawals) / $lastMonthWithdrawals) * 100 : 0;

        return [
            Stat::make('Total Withdrawn', "{$formattedWithdrawals}")
                ->description(
                    $withdrawalCount > 0 ?
                        "Avg: " . Number::format($avgWithdrawal, 2) . " per withdrawal" :
                        "No withdrawals"
                )
                ->descriptionIcon('heroicon-o-banknotes')
                ->color($this->getWithdrawalLevelColor($totalWithdrawals, $budget))
                ->chart($this->getWithdrawalChartData())
                ->chartColor($this->getWithdrawalChartColor($totalWithdrawals, $budget))
                ->extraAttributes([
                    'title' => $budget > 0 ?
                        "{$formattedWithdrawals} of " . Number::format($budget, 2) . " budget" :
                        "Withdrawn from program funds",
                ]),

            Stat::make('Available Funds', "{$formattedAvailable}")
                ->description("{$formattedAvailablePercent}% of deposits remaining")
                ->descriptionIcon($availableFunds > 0 ? 'heroicon-o-wallet' : 'heroicon-m-exclamation-triangle')
                ->color($this->getAvailableFundsColor($availableFunds, $totalDeposits))
                ->extraAttributes([
                    'title' => "Deposits: " . Number::format($totalDeposits, 2) .
                        " - Withdrawals: {$formattedWithdrawals}",
                ]),

            Stat::make('Monthly Activity', Number::format($thisMonthWithdrawals, 2))
                ->description($this->getMonthlyChangeDescription($monthOverMonthChange))
                ->descriptionIcon($this->getMonthlyChangeIcon($monthOverMonthChange))
                ->color($this->getMonthlyChangeColor($monthOverMonthChange))
                ->extraAttributes([
                    'title' => $lastMonthWithdrawals > 0 ?
                        "Last month: " . Number::format($lastMonthWithdrawals, 2) :
                        "First month with withdrawals",
                ]),

            Stat::make('Budget Impact', "{$formattedRemainingBudget}%")
                ->description($budget > 0 ? "budget remaining" : "no budget set")
                ->descriptionIcon($remainingBudgetPercent >= 50 ? 'heroicon-o-shield-check' : 'heroicon-o-shield-exclamation')
                ->color($this->getBudgetImpactColor($remainingBudgetPercent))
                ->extraAttributes([
                    'class' => 'cursor-help',
                    'title' => $budget > 0 ?
                        $remainingBudgetPercent >= 0 ?
                        Number::format(($budget - $totalWithdrawals), 2) . " of budget remaining" :
                        "Budget exceeded by " . Number::format(abs($budget - $totalWithdrawals), 2) :
                        "Set a budget to track withdrawals against it",
                ]),
        ];
    }

    private function getWithdrawalLevelColor(float $withdrawals, float $budget): string
    {
        if ($budget == 0) return 'primary';

        $percentage = $budget > 0 ? ($withdrawals / $budget) * 100 : 0;

        return match (true) {
            $percentage >= 100 => 'danger',
            $percentage >= 75 => 'warning',
            $percentage >= 50 => 'info',
            $percentage >= 25 => 'success',
            $percentage > 0 => 'primary',
            default => 'gray',
        };
    }

    private function getWithdrawalChartData(): array
    {
        // Get withdrawals by month for the last 5 months
        $chartData = [];
        for ($i = 4; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthTotal = $this->record->withdrawals()->where('status', 'completed')
                ->whereYear('withdrawal_date', $date->year)
                ->whereMonth('withdrawal_date', $date->month)
                ->sum('amount');
            $chartData[] = $monthTotal;
        }

        return $chartData;
    }

    private function getWithdrawalChartColor(float $withdrawals, float $budget): string
    {
        if ($budget == 0) return 'blue';

        $percentage = $budget > 0 ? ($withdrawals / $budget) * 100 : 0;

        return match (true) {
            $percentage >= 100 => 'red',
            $percentage >= 75 => 'yellow',
            $percentage >= 50 => 'blue',
            $percentage >= 25 => 'green',
            default => 'gray',
        };
    }

    private function getAvailableFundsColor(float $available, float $deposits): string
    {
        if ($deposits == 0) return 'gray';

        $percentage = ($available / $deposits) * 100;

        return match (true) {
            $percentage >= 75 => 'success',
            $percentage >= 50 => 'info',
            $percentage >= 25 => 'warning',
            $percentage >= 0 => 'danger',
            default => 'danger',
        };
    }

    private function getMonthlyChangeDescription(float $change): string
    {
        if ($change > 0) {
            return "+" . Number::format($change, 1) . "% from last month";
        } elseif ($change < 0) {
            return Number::format($change, 1) . "% from last month";
        } else {
            return "No change from last month";
        }
    }

    private function getMonthlyChangeIcon(float $change): string
    {
        return match (true) {
            $change > 10 => 'heroicon-o-arrow-trending-up',
            $change > 0 => 'heroicon-o-arrow-up',
            $change < -10 => 'heroicon-o-arrow-trending-down',
            $change < 0 => 'heroicon-o-arrow-down',
            default => 'heroicon-o-minus',
        };
    }

    private function getMonthlyChangeColor(float $change): string
    {
        return match (true) {
            $change > 20 => 'danger',     // Large increase in withdrawals
            $change > 0 => 'warning',     // Moderate increase
            $change > -10 => 'info',      // Small change
            $change > -20 => 'success',   // Decrease in withdrawals
            default => 'primary',
        };
    }

    private function getBudgetImpactColor(float $remainingPercent): string
    {
        return match (true) {
            $remainingPercent >= 75 => 'success',
            $remainingPercent >= 50 => 'info',
            $remainingPercent >= 25 => 'warning',
            $remainingPercent >= 0 => 'danger',
            default => 'danger',
        };
    }
}
