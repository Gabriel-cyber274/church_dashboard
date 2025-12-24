<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use App\Models\Deposit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class MemberStatsWidget extends BaseWidget
{
    protected static ?int $sort = 6;
    // protected static ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        $totalMembers = Member::count();
        $newThisMonth = Member::whereMonth('created_at', now()->month)->count();
        $activeMembers = $this->getActiveMembersCount();
        $averageAge = $this->getAverageAge();

        $totalDeposits = Deposit::whereMonth('created_at', now()->month)->where('status', 'completed')->sum('amount');
        $avgDepositPerMember = $totalMembers > 0 ? $totalDeposits / $totalMembers : 0;

        return [
            Stat::make('Total Members', $totalMembers)
                ->description($newThisMonth . ' new this month')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('primary')
                ->chart($this->getMemberGrowthChart()),

            Stat::make('Active Members', $activeMembers)
                ->description(round(($activeMembers / max($totalMembers, 1)) * 100) . '% of total')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Average Age', round($averageAge) . ' years')
                ->description('Based on date of birth')
                ->descriptionIcon('heroicon-o-cake')
                ->color('info'),

            Stat::make('Gender Distribution', $this->getGenderRatio())
                ->description($this->getGenderBreakdown())
                ->descriptionIcon('heroicon-o-users')
                ->color('warning'),

            Stat::make('Avg Deposit/Member', '₦' . number_format($avgDepositPerMember, 2))
                ->description('This month')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success')
                ->visible(fn() => auth()->user()?->hasAnyRole([
                    'super_admin',
                    'admin',
                    'finance',
                ])),

            Stat::make('Marital Status', $this->getMaritalStatusSummary())
                ->description('Members breakdown')
                ->descriptionIcon('heroicon-o-heart')
                ->color('danger'),
        ];
    }

    protected function getActiveMembersCount(): int
    {
        // Consider members active if they made a deposit in the last 3 months
        $threeMonthsAgo = now()->subMonths(3);
        return Member::whereHas('deposits', function ($query) use ($threeMonthsAgo) {
            $query->where('created_at', '>=', $threeMonthsAgo)->where('status', 'completed');
        })->count();
    }

    protected function getAverageAge(): float
    {
        $membersWithDob = Member::whereNotNull('date_of_birth')->get();

        if ($membersWithDob->count() === 0) {
            return 0;
        }

        $totalAge = $membersWithDob->sum(function ($member) {
            try {
                // Try to parse the date of birth as a Carbon instance
                $dob = Carbon::parse($member->date_of_birth);

                // Calculate age manually
                return $dob->diffInYears(now());
            } catch (\Exception $e) {
                // If date is invalid, skip this member
                return 0;
            }
        });

        // Only count members with valid dates
        $validMembersCount = $membersWithDob->filter(function ($member) {
            try {
                Carbon::parse($member->date_of_birth);
                return true;
            } catch (\Exception $e) {
                return false;
            }
        })->count();

        return $validMembersCount > 0 ? $totalAge / $validMembersCount : 0;
    }

    protected function getGenderRatio(): string
    {
        $maleCount = Member::where('gender', 'male')->count();
        $femaleCount = Member::where('gender', 'female')->count();
        $otherCount = Member::where('gender', 'other')->orWhereNull('gender')->count();
        $total = $maleCount + $femaleCount + $otherCount;

        if ($total === 0) return 'No data';

        $malePercent = round(($maleCount / $total) * 100);
        $femalePercent = round(($femaleCount / $total) * 100);

        return $malePercent . '%M / ' . $femalePercent . '%F';
    }

    protected function getGenderBreakdown(): string
    {
        $maleCount = Member::where('gender', 'male')->count();
        $femaleCount = Member::where('gender', 'female')->count();
        $otherCount = Member::where('gender', 'other')->orWhereNull('gender')->count();

        return "M:{$maleCount} F:{$femaleCount} O:{$otherCount}";
    }

    protected function getMaritalStatusSummary(): string
    {
        $single = Member::where('marital_status', 'single')->count();
        $married = Member::where('marital_status', 'married')->count();
        $divorced = Member::where('marital_status', 'divorced')->count();
        $widowed = Member::where('marital_status', 'widowed')->count();
        $total = $single + $married + $divorced + $widowed;

        if ($total === 0) return 'No data';

        $marriedPercent = round(($married / $total) * 100);
        return $marriedPercent . '% married';
    }

    protected function getMemberGrowthChart(): array
    {
        $growth = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $count = Member::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $growth[] = $count;
        }
        return $growth;
    }
}
