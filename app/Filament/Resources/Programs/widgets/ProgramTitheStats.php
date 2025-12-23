<?php

namespace App\Filament\Resources\Programs\Widgets;

use App\Models\Program;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;

class ProgramTitheStats extends BaseWidget
{
    public Program $record;

    protected ?string $pollingInterval = '10s';



    protected function getStats(): array
    {
        // Get tithe totals
        $totalTithes = $this->record->tithes()->sum('amount');
        $titheCount = $this->record->tithes()->count();
        $avgTithe = $titheCount > 0 ? $totalTithes / $titheCount : 0;

        // Get monthly and weekly comparisons
        $thisMonthTithes = $this->record->tithes()
            ->whereMonth('tithe_date', Carbon::now()->month)
            ->whereYear('tithe_date', Carbon::now()->year)
            ->sum('amount');

        $lastMonthTithes = $this->record->tithes()
            ->whereMonth('tithe_date', Carbon::now()->subMonth()->month)
            ->whereYear('tithe_date', Carbon::now()->subMonth()->year)
            ->sum('amount');

        $thisQuarterTithes = $this->record->tithes()
            ->whereBetween('tithe_date', [
                Carbon::now()->startOfQuarter(),
                Carbon::now()->endOfQuarter()
            ])
            ->sum('amount');

        $lastQuarterTithes = $this->record->tithes()
            ->whereBetween('tithe_date', [
                Carbon::now()->subQuarter()->startOfQuarter(),
                Carbon::now()->subQuarter()->endOfQuarter()
            ])
            ->sum('amount');

        // Calculate growth rates
        $monthlyGrowth = $lastMonthTithes > 0 ?
            (($thisMonthTithes - $lastMonthTithes) / $lastMonthTithes) * 100 : 0;

        $quarterlyGrowth = $lastQuarterTithes > 0 ?
            (($thisQuarterTithes - $lastQuarterTithes) / $lastQuarterTithes) * 100 : 0;

        // Calculate consistency (percentage of months with tithes)
        $firstTithe = $this->record->tithes()->orderBy('tithe_date')->first();
        $monthsActive = $firstTithe ?
            Carbon::parse($firstTithe->tithe_date)->diffInMonths(now()) + 1 : 0;

        $monthsWithTithes = $this->record->tithes()
            ->selectRaw('DISTINCT YEAR(tithe_date) as year, MONTH(tithe_date) as month')
            ->count();

        $consistencyRate = $monthsActive > 0 ? ($monthsWithTithes / $monthsActive) * 100 : 0;

        // Format numbers
        $formattedTotal = Number::format($totalTithes, 2);
        $formattedAvg = Number::format($avgTithe, 2);
        $formattedMonthly = Number::format($thisMonthTithes, 2);
        $formattedConsistency = Number::format($consistencyRate, 1);

        // Get chart data
        $monthlyChart = $this->getMonthlyChartData();
        $quarterlyChart = $this->getQuarterlyChartData();

        return [
            Stat::make('Total Tithes', "{$formattedTotal}")
                ->description("{$titheCount} tithe" . ($titheCount !== 1 ? 's' : ''))
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color($this->getTotalColor($totalTithes))
                ->chart($monthlyChart)
                ->chartColor($this->getChartColor($totalTithes)),

            Stat::make('Average Tithe', "{$formattedAvg}")
                ->description($this->getAverageDescription($avgTithe))
                ->descriptionIcon('heroicon-o-scale')
                ->color($this->getAverageColor($avgTithe))
                ->extraAttributes([
                    'title' => "Based on {$titheCount} tithes",
                ]),

            Stat::make('This Month', "{$formattedMonthly}")
                ->description($this->getMonthlyChangeDescription($monthlyGrowth))
                ->descriptionIcon($this->getGrowthIcon($monthlyGrowth))
                ->color($this->getGrowthColor($monthlyGrowth))
                ->chart($quarterlyChart)
                ->chartColor($this->getGrowthChartColor($monthlyGrowth)),

            Stat::make('Consistency', "{$formattedConsistency}%")
                ->description($this->getConsistencyDescription($consistencyRate))
                ->descriptionIcon($this->getConsistencyIcon($consistencyRate))
                ->color($this->getConsistencyColor($consistencyRate))
                ->extraAttributes([
                    'class' => 'cursor-help',
                    'title' => "Tithes in {$monthsWithTithes} of {$monthsActive} months",
                ]),
        ];
    }

    private function getMonthlyChartData(): array
    {
        $chartData = [];

        // Last 6 months of tithe data
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthlyTotal = $this->record->tithes()
                ->whereMonth('tithe_date', $date->month)
                ->whereYear('tithe_date', $date->year)
                ->sum('amount');
            $chartData[] = $monthlyTotal;
        }

        return $chartData;
    }

    private function getQuarterlyChartData(): array
    {
        $chartData = [];

        // Last 4 quarters
        for ($i = 3; $i >= 0; $i--) {
            $quarterStart = Carbon::now()->subQuarters($i)->startOfQuarter();
            $quarterEnd = $quarterStart->copy()->endOfQuarter();

            $quarterlyTotal = $this->record->tithes()
                ->whereBetween('tithe_date', [$quarterStart, $quarterEnd])
                ->sum('amount');

            $chartData[] = $quarterlyTotal;
        }

        return $chartData;
    }

    private function getTotalColor(float $total): string
    {
        return match (true) {
            $total >= 50000 => 'success',  // Very high
            $total >= 25000 => 'info',     // High
            $total >= 10000 => 'primary',  // Good
            $total >= 5000 => 'warning',   // Moderate
            $total > 0 => 'gray',          // Low
            default => 'gray',             // None
        };
    }

    private function getChartColor(float $total): string
    {
        return match (true) {
            $total >= 50000 => 'green',
            $total >= 25000 => 'blue',
            $total >= 10000 => 'purple',
            $total >= 5000 => 'yellow',
            $total > 0 => 'gray',
            default => 'gray',
        };
    }

    private function getAverageDescription(float $average): string
    {
        if ($average == 0) return 'No tithes yet';

        if ($average >= 1000) return 'Large tithes';
        if ($average >= 500) return 'Above average';
        if ($average >= 250) return 'Standard tithes';
        if ($average >= 100) return 'Moderate tithes';
        return 'Small tithes';
    }

    private function getAverageColor(float $average): string
    {
        return match (true) {
            $average >= 1000 => 'success',
            $average >= 500 => 'info',
            $average >= 250 => 'primary',
            $average >= 100 => 'warning',
            $average > 0 => 'gray',
            default => 'gray',
        };
    }

    private function getMonthlyChangeDescription(float $growth): string
    {
        if ($growth > 0) {
            return "+" . Number::format($growth, 1) . "% from last month";
        } elseif ($growth < 0) {
            return Number::format($growth, 1) . "% from last month";
        } else {
            return "Same as last month";
        }
    }

    private function getGrowthIcon(float $growth): string
    {
        return match (true) {
            $growth > 30 => 'heroicon-o-rocket-launch',
            $growth > 15 => 'heroicon-o-arrow-trending-up',
            $growth > 0 => 'heroicon-o-arrow-up',
            $growth < -30 => 'heroicon-o-arrow-trending-down',
            $growth < 0 => 'heroicon-o-arrow-down',
            default => 'heroicon-o-minus',
        };
    }

    private function getGrowthColor(float $growth): string
    {
        return match (true) {
            $growth > 50 => 'success',     // Excellent growth
            $growth > 20 => 'info',        // Strong growth
            $growth > 0 => 'primary',      // Positive growth
            $growth > -10 => 'warning',    // Slight decline
            $growth > -30 => 'danger',     // Moderate decline
            default => 'danger',           // Significant decline
        };
    }

    private function getGrowthChartColor(float $growth): string
    {
        return $growth >= 0 ? 'green' : 'red';
    }

    private function getConsistencyDescription(float $rate): string
    {
        return match (true) {
            $rate >= 90 => 'Highly consistent',
            $rate >= 75 => 'Very consistent',
            $rate >= 50 => 'Moderately consistent',
            $rate >= 25 => 'Somewhat consistent',
            $rate > 0 => 'Inconsistent',
            default => 'No pattern',
        };
    }

    private function getConsistencyIcon(float $rate): string
    {
        return match (true) {
            $rate >= 90 => 'heroicon-o-check-badge',
            $rate >= 75 => 'heroicon-o-check-circle',
            $rate >= 50 => 'heroicon-o-clock',
            $rate >= 25 => 'heroicon-o-calendar',
            $rate > 0 => 'heroicon-o-question-mark-circle',
            default => 'heroicon-o-x-circle',
        };
    }

    private function getConsistencyColor(float $rate): string
    {
        return match (true) {
            $rate >= 90 => 'success',      // Excellent consistency
            $rate >= 75 => 'info',         // Good consistency
            $rate >= 50 => 'primary',      // Fair consistency
            $rate >= 25 => 'warning',      // Low consistency
            $rate > 0 => 'danger',         // Poor consistency
            default => 'gray',             // No consistency data
        };
    }
}
