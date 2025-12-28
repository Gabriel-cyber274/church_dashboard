<?php

namespace App\Filament\Resources\Members\Widgets;

use App\Models\Deposit;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;

class MemberDepositStats extends StatsOverviewWidget
{
    public ?Model $record = null;

    protected function getStats(): array
    {
        $memberId = $this->record->id;

        $totalDeposits = Deposit::where('member_id', $memberId)->sum('amount');

        $completedDeposits = Deposit::where('member_id', $memberId)
            ->where('status', 'completed')
            ->sum('amount');

        $pendingDeposits = Deposit::where('member_id', $memberId)
            ->where('status', 'pending')
            ->sum('amount');

        $depositCount = Deposit::where('member_id', $memberId)->count();

        return [
            Stat::make('Total Deposits', number_format($totalDeposits, 2))
                ->description('All deposits')
                ->color('primary'),

            Stat::make('Completed', number_format($completedDeposits, 2))
                ->description('Confirmed deposits')
                ->color('success'),

            Stat::make('Pending', number_format($pendingDeposits, 2))
                ->description('Awaiting confirmation')
                ->color('warning'),

            Stat::make('Deposit Count', $depositCount)
                ->description('Number of deposits')
                ->color('gray'),
        ];
    }
}
