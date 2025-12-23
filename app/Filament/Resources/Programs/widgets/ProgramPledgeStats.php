<?php

namespace App\Filament\Resources\Programs\Widgets;

use App\Models\Program;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProgramPledgeStats extends BaseWidget
{
    public Program $record;

    protected ?string $pollingInterval = '10s';


    protected function getStats(): array
    {
        $totalPledge = $this->record->pledges()->sum('amount');
        $totalPending = $this->record->pledges()->where('status', 'pending')->sum('amount');
        $totalCompleted = $this->record->pledges()->where('status', 'completed')->sum('amount');
        $pendingCount = $this->record->pledges()->where('status', 'pending')->count();
        $completedCount = $this->record->pledges()->where('status', 'completed')->count();

        return [
            Stat::make('Total Pledge Amount', number_format($totalPledge, 2))
                ->description('Sum of all pledges')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('primary'),

            Stat::make('Pending Pledges', number_format($totalPending, 2))
                ->description("{$pendingCount} pending pledge(s)")
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Completed Pledges', number_format($totalCompleted, 2))
                ->description("{$completedCount} completed pledge(s)")
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Completion Rate', $totalPledge > 0 ? round(($totalCompleted / $totalPledge) * 100, 1) . '%' : '0%')
                ->description('Percentage of pledged amount completed')
                ->descriptionIcon('heroicon-o-chart-bar')
                ->color('info'),
        ];
    }
}
