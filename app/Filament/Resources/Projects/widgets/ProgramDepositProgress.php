<?php

namespace App\Filament\Resources\Projects\Widgets;

use App\Models\Program;
use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class ProgramDepositProgress extends BaseWidget
{
    public Project $record;

    protected ?string $pollingInterval = '10s';



    protected function getStats(): array
    {
        $totalDeposits = $this->record->deposits()->where('status', 'completed')->sum('amount');
        $budget = $this->record->budget ?? 0;

        // Calculate percentage with precision
        $percentage = $budget > 0 ? ($totalDeposits / $budget) * 100 : 0;
        $percentage = min($percentage, 100);

        // Determine color based on progress
        $color = match (true) {
            $percentage >= 100 => 'danger',    // Over/at budget
            $percentage >= 80 => 'warning',    // Close to budget
            $percentage >= 50 => 'info',       // Halfway there
            $percentage >= 20 => 'success',    // Started
            default => 'gray'                  // Just beginning
        };

        // Calculate remaining budget
        $remainingBudget = $budget - $totalDeposits;
        $isOverBudget = $remainingBudget < 0;

        // Format numbers for display
        $formattedDeposits = Number::format($totalDeposits, 2);
        $formattedBudget = Number::format($budget, 2);
        $formattedRemaining = Number::format(abs($remainingBudget), 2);
        $formattedPercentage = Number::format($percentage, 1);

        // Create a chart data array to simulate progress bar
        $chartData = [];
        if ($budget > 0) {
            // Create a smooth gradient of data points for the chart
            for ($i = 0; $i <= 100; $i += 10) {
                $chartData[] = $i <= $percentage ? $percentage : 0;
            }
        }

        // Get additional stats
        $depositCount = $this->record->deposits()->where('status', 'completed')->count();
        $avgDeposit = $depositCount > 0 ? $totalDeposits / $depositCount : 0;
        $formattedAvg = Number::format($avgDeposit, 2);

        return [
            Stat::make('Budget Progress', "{$formattedPercentage}%")
                ->description("{$formattedDeposits} of {$formattedBudget}")
                ->descriptionIcon($isOverBudget ? 'heroicon-m-exclamation-triangle' : 'heroicon-o-banknotes')
                ->color($color)
                ->chart($chartData)
                ->chartColor($color)
                ->extraAttributes([
                    'class' => 'relative',
                    'x-data' => '{ percentage: ' . $percentage . ' }',
                ]),

            Stat::make('Deposits', $depositCount)
                ->description(
                    $depositCount > 0 ?
                        "Avg: {$formattedAvg}" :
                        'No deposits yet'
                )
                ->descriptionIcon('heroicon-o-document-text')
                ->color($depositCount > 0 ? 'primary' : 'gray'),

            Stat::make(
                $isOverBudget ? 'Over Budget' : 'Remaining Budget',
                $isOverBudget ? "-{$formattedRemaining}" : $formattedRemaining
            )
                ->description($isOverBudget ? 'Budget exceeded' : 'To reach target')
                ->descriptionIcon(
                    $isOverBudget ?
                        'heroicon-m-exclamation-triangle' :
                        'heroicon-o-arrow-trending-up'
                )
                ->color($isOverBudget ? 'danger' : 'success'),

            // Bonus stat: Progress status with emoji
            Stat::make('Status', $this->getProgressStatus($percentage))
                ->description($this->getProgressDescription($percentage, $budget))
                ->descriptionIcon($this->getProgressIcon($percentage))
                ->color('info')
                ->extraAttributes([
                    'class' => 'text-center',
                ]),
        ];
    }

    private function getProgressStatus(float $percentage): string
    {
        return match (true) {
            $percentage == 0 => 'Not Started',
            $percentage < 25 => 'Early Stage',
            $percentage < 50 => 'In Progress',
            $percentage < 75 => 'Good Progress',
            $percentage < 90 => 'Almost There',
            $percentage < 100 => 'Nearly Complete',
            $percentage >= 100 => 'Completed',
            default => 'In Progress',
        };
    }

    private function getProgressDescription(float $percentage, float $budget): string
    {
        if ($budget == 0) return 'Set a budget to track progress';

        return match (true) {
            $percentage == 0 => 'No deposits yet',
            $percentage < 50 => 'Keep going!',
            $percentage < 75 => 'Over halfway there!',
            $percentage < 90 => 'Approaching budget goal',
            $percentage < 100 => 'Almost at target',
            $percentage >= 100 => 'Budget goal reached',
            default => 'Tracking progress',
        };
    }

    private function getProgressIcon(float $percentage): string
    {
        return match (true) {
            $percentage == 0 => 'heroicon-o-clock',
            $percentage < 50 => 'heroicon-o-arrow-up-right',
            $percentage < 90 => 'heroicon-o-chart-bar',
            $percentage < 100 => 'heroicon-o-check-circle',
            $percentage >= 100 => 'heroicon-o-trophy',
            default => 'heroicon-o-chart-bar',
        };
    }
}
