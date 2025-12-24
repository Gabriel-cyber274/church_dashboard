<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MemberDemographicsWidget extends BaseWidget
{
    protected static ?int $sort = 11;

    protected function getStats(): array
    {
        $total = Member::count();

        if ($total === 0) {
            return [
                Stat::make('No Members', 'Add members to see statistics')
                    ->color('gray'),
            ];
        }

        $maritalStats = $this->getMaritalStats($total);
        $departmentStats = $this->getDepartmentStats();
        $coordinatorStats = $this->getCoordinatorStats();

        return [
            Stat::make('Marital Status', '')
                ->description($maritalStats)
                ->descriptionIcon('heroicon-o-heart')
                ->color('pink'),

            Stat::make('Department Members', $departmentStats['count'])
                ->description($departmentStats['description'])
                ->descriptionIcon('heroicon-o-building-office')
                ->color('blue'),

            Stat::make('Program Coordinators', $coordinatorStats['count'])
                ->description($coordinatorStats['description'])
                ->descriptionIcon('heroicon-o-clipboard-document-check')
                ->color('green'),

            Stat::make('Members with Pledges', $this->getPledgeStats($total))
                ->description('Active commitments')
                ->descriptionIcon('heroicon-o-hand-raised')
                ->color('orange')
                ->visible(fn() => auth()->user()?->hasAnyRole([
                    'super_admin',
                    'admin',
                    'finance',
                ])),
        ];
    }

    protected function getMaritalStats($total): string
    {
        $married = Member::where('marital_status', 'married')->count();
        $single = Member::where('marital_status', 'single')->count();
        $divorced = Member::where('marital_status', 'divorced')->count();
        $widowed = Member::where('marital_status', 'widowed')->count();

        $marriedPercent = round(($married / $total) * 100);
        return "{$marriedPercent}% married · {$single} single";
    }

    protected function getDepartmentStats(): array
    {
        $membersWithDept = Member::whereHas('departments')->count();
        $total = Member::count();
        $percentage = $total > 0 ? round(($membersWithDept / $total) * 100) : 0;

        return [
            'count' => $membersWithDept,
            'description' => "{$percentage}% of members in departments",
        ];
    }

    protected function getCoordinatorStats(): array
    {
        $coordinators = Member::whereHas('programsCoordinated')->count();
        $total = Member::count();
        $percentage = $total > 0 ? round(($coordinators / $total) * 100) : 0;

        return [
            'count' => $coordinators,
            'description' => "{$percentage}% coordinate programs",
        ];
    }

    protected function getPledgeStats($total): string
    {
        $withPledges = Member::whereHas('pledges')->count();
        $percentage = $total > 0 ? round(($withPledges / $total) * 100) : 0;
        return "{$withPledges} ({$percentage}%)";
    }
}
