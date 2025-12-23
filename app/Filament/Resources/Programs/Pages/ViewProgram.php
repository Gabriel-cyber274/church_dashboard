<?php

namespace App\Filament\Resources\Programs\Pages;

use App\Filament\Resources\Programs\ProgramResource;
use App\Filament\Resources\Programs\Widgets\ProgramDepositProgress;
use App\Filament\Resources\Programs\Widgets\ProgramOfferingStats;
use App\Filament\Resources\Programs\Widgets\ProgramPledgeStats;
use App\Filament\Resources\Programs\Widgets\ProgramTitheStats;
use App\Filament\Resources\Programs\Widgets\ProgramWithdrawalProgress;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProgram extends ViewRecord
{
    protected static string $resource = ProgramResource::class;

    protected $queryString = ['activeRelationManager'];

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()->visible(fn() => auth()->user()?->hasAnyRole([
                'super_admin',
                'admin',
                'pastors'
            ])),
        ];
    }

    protected function getFooterWidgets(): array
    {
        // Check if we're on the Pledges tab (index 2)
        if ($this->activeRelationManager === '2') {
            return [
                ProgramPledgeStats::class,
            ];
        } else if ($this->activeRelationManager === '3') {
            return [
                ProgramDepositProgress::class,
            ];
        } else if ($this->activeRelationManager === '4') {
            return [
                ProgramWithdrawalProgress::class
            ];
        } else if ($this->activeRelationManager === '5') {
            return [
                ProgramOfferingStats::class
            ];
        } else if ($this->activeRelationManager === '6') {
            return [
                ProgramTitheStats::class
            ];
        }





        return [];
    }
}
