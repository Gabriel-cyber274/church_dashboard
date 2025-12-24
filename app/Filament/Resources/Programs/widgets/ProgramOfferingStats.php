<?php

namespace App\Filament\Resources\Programs\Widgets;

use App\Models\Program;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;

class ProgramOfferingStats extends BaseWidget
{
    public Program $record;

    protected ?string $pollingInterval = '10s';



    protected function getStats(): array
    {
        // Get offering totals
        $totalOfferings = $this->record->offerings()->where('status', 'completed')->sum('amount');
        $offeringCount = $this->record->offerings()->where('status', 'completed')->count();
        $avgOffering = $offeringCount > 0 ? $totalOfferings / $offeringCount : 0;

        // Get recent offering data
        $thisWeekOfferings = $this->record->offerings()->where('status', 'completed')
            ->whereBetween('offering_date', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])
            ->sum('amount');

        $lastWeekOfferings = $this->record->offerings()->where('status', 'completed')
            ->whereBetween('offering_date', [
                Carbon::now()->subWeek()->startOfWeek(),
                Carbon::now()->subWeek()->endOfWeek()
            ])
            ->sum('amount');

        $thisMonthOfferings = $this->record->offerings()->where('status', 'completed')
            ->whereMonth('offering_date', Carbon::now()->month)
            ->whereYear('offering_date', Carbon::now()->year)
            ->sum('amount');

        $lastMonthOfferings = $this->record->offerings()->where('status', 'completed')
            ->whereMonth('offering_date', Carbon::now()->subMonth()->month)
            ->whereYear('offering_date', Carbon::now()->subMonth()->year)
            ->sum('amount');

        // Calculate growth rates
        $weeklyGrowth = $lastWeekOfferings > 0 ?
            (($thisWeekOfferings - $lastWeekOfferings) / $lastWeekOfferings) * 100 : 0;

        $monthlyGrowth = $lastMonthOfferings > 0 ?
            (($thisMonthOfferings - $lastMonthOfferings) / $lastMonthOfferings) * 100 : 0;

        // Calculate offering frequency
        $firstOffering = $this->record->offerings()->orderBy('offering_date')->first();
        $daysSinceStart = $firstOffering ?
            Carbon::parse($firstOffering->offering_date)->diffInDays(now()) : 0;

        $offeringFrequency = $daysSinceStart > 0 && $offeringCount > 0 ?
            $daysSinceStart / $offeringCount : 0; // days per offering

        // Format numbers
        $formattedTotal = Number::format($totalOfferings, 2);
        $formattedAvg = Number::format($avgOffering, 2);
        $formattedThisMonth = Number::format($thisMonthOfferings, 2);
        $formattedWeekly = Number::format($thisWeekOfferings, 2);

        // Get chart data for recent offerings
        $weeklyChart = $this->getWeeklyChartData();
        $monthlyChart = $this->getMonthlyChartData();

        return [
            Stat::make('Total Offerings', "{$formattedTotal}")
                ->description("{$offeringCount} offering" . ($offeringCount !== 1 ? 's' : ''))
                ->descriptionIcon('heroicon-o-gift')
                ->color($this->getTotalColor($totalOfferings))
                ->chart($weeklyChart)
                ->chartColor($this->getChartColor($totalOfferings)),

            Stat::make('Average Offering', "{$formattedAvg}")
                ->description($this->getAverageDescription($avgOffering, $offeringCount))
                ->descriptionIcon('heroicon-o-chart-bar')
                ->color($this->getAverageColor($avgOffering))
                ->extraAttributes([
                    'title' => "Based on {$offeringCount} offerings",
                ]),

            Stat::make('This Month', "{$formattedThisMonth}")
                ->description($this->getMonthlyChangeDescription($monthlyGrowth))
                ->descriptionIcon($this->getGrowthIcon($monthlyGrowth))
                ->color($this->getGrowthColor($monthlyGrowth))
                ->chart($monthlyChart)
                ->chartColor($this->getGrowthChartColor($monthlyGrowth)),

            Stat::make('Offering Frequency', $this->formatFrequency($offeringFrequency))
                ->description($this->getFrequencyDescription($offeringFrequency, $offeringCount))
                ->descriptionIcon($this->getFrequencyIcon($offeringFrequency))
                ->color($this->getFrequencyColor($offeringFrequency))
                ->extraAttributes([
                    'class' => 'cursor-help',
                    'title' => $offeringCount > 0 ?
                        "Average of " . Number::format($offeringFrequency, 1) . " days between offerings" :
                        "No offerings yet",
                ]),
        ];
    }

    private function getWeeklyChartData(): array
    {
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dailyTotal = $this->record->offerings()->where('status', 'completed')
                ->whereDate('offering_date', $date->toDateString())
                ->sum('amount');
            $chartData[] = $dailyTotal;
        }
        return $chartData;
    }

    private function getMonthlyChartData(): array
    {
        $chartData = [];
        $startOfMonth = Carbon::now()->startOfMonth();

        // Get 4 weeks of data
        for ($i = 3; $i >= 0; $i--) {
            $weekStart = $startOfMonth->copy()->addWeeks($i)->startOfWeek();
            $weekEnd = $weekStart->copy()->endOfWeek();

            $weeklyTotal = $this->record->offerings()->where('status', 'completed')
                ->whereBetween('offering_date', [$weekStart, $weekEnd])
                ->sum('amount');

            $chartData[] = $weeklyTotal;
        }
        return $chartData;
    }

    private function getTotalColor(float $total): string
    {
        return match (true) {
            $total >= 10000 => 'success',
            $total >= 5000 => 'info',
            $total >= 1000 => 'primary',
            $total > 0 => 'warning',
            default => 'gray',
        };
    }

    private function getChartColor(float $total): string
    {
        return match (true) {
            $total >= 10000 => 'green',
            $total >= 5000 => 'blue',
            $total >= 1000 => 'purple',
            $total > 0 => 'yellow',
            default => 'gray',
        };
    }

    private function getAverageDescription(float $average, int $count): string
    {
        if ($count === 0) return 'No offerings yet';

        if ($average >= 1000) return 'Large offerings';
        if ($average >= 500) return 'Moderate offerings';
        if ($average >= 100) return 'Standard offerings';
        return 'Small offerings';
    }

    private function getAverageColor(float $average): string
    {
        return match (true) {
            $average >= 1000 => 'success',
            $average >= 500 => 'info',
            $average >= 100 => 'primary',
            $average > 0 => 'warning',
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
            $growth > 20 => 'heroicon-o-arrow-trending-up',
            $growth > 0 => 'heroicon-o-arrow-up',
            $growth < -20 => 'heroicon-o-arrow-trending-down',
            $growth < 0 => 'heroicon-o-arrow-down',
            default => 'heroicon-o-minus',
        };
    }

    private function getGrowthColor(float $growth): string
    {
        return match (true) {
            $growth > 50 => 'success',    // High growth
            $growth > 10 => 'info',       // Moderate growth
            $growth > 0 => 'primary',     // Small growth
            $growth > -10 => 'warning',   // Small decline
            $growth > -30 => 'danger',    // Moderate decline
            default => 'danger',          // Large decline
        };
    }

    private function getGrowthChartColor(float $growth): string
    {
        return $growth >= 0 ? 'green' : 'red';
    }

    private function formatFrequency(float $days): string
    {
        if ($days == 0) return 'No pattern';

        if ($days < 1) return 'Multiple daily';
        if ($days == 1) return 'Daily';
        if ($days <= 7) return 'Weekly';
        if ($days <= 14) return 'Bi-weekly';
        if ($days <= 31) return 'Monthly';
        return 'Sporadic';
    }

    private function getFrequencyDescription(float $frequency, int $count): string
    {
        if ($count === 0) return 'No offerings recorded';

        return match (true) {
            $frequency < 1 => 'High frequency',
            $frequency <= 3 => 'Regular offerings',
            $frequency <= 7 => 'Weekly pattern',
            $frequency <= 14 => 'Bi-weekly pattern',
            $frequency <= 31 => 'Monthly pattern',
            default => 'Irregular pattern',
        };
    }

    private function getFrequencyIcon(float $frequency): string
    {
        return match (true) {
            $frequency < 1 => 'heroicon-o-clock',       // Multiple daily
            $frequency <= 3 => 'heroicon-o-bolt',       // Every few days
            $frequency <= 7 => 'heroicon-o-calendar',   // Weekly
            $frequency <= 14 => 'heroicon-o-calendar-days', // Bi-weekly
            $frequency <= 31 => 'heroicon-o-calendar-days', // Monthly
            default => 'heroicon-o-question-mark-circle', // Irregular
        };
    }

    private function getFrequencyColor(float $frequency): string
    {
        return match (true) {
            $frequency < 3 => 'success',     // Frequent
            $frequency <= 7 => 'info',       // Regular
            $frequency <= 14 => 'primary',   // Bi-weekly
            $frequency <= 31 => 'warning',   // Monthly
            default => 'gray',               // Irregular
        };
    }
}
