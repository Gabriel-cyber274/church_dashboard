<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Resources\Projects\ProjectResource;
use App\Filament\Resources\Projects\Widgets\ProgramDepositProgress;
use App\Filament\Resources\Projects\Widgets\ProgramPledgeStats;
use App\Filament\Resources\Projects\Widgets\ProgramWithdrawalProgress;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProject extends ViewRecord
{
    protected static string $resource = ProjectResource::class;

    protected $queryString = ['activeRelationManager'];

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()->visible(fn() => auth()->user()?->hasAnyRole([
                'super_admin',
            ])),
        ];
    }

    protected function getFooterWidgets(): array
    {
        // Check if we're on the Pledges tab (index 2)
        if ($this->activeRelationManager === '1') {
            return [
                ProgramPledgeStats::class,
            ];
        } else if ($this->activeRelationManager === '2') {
            return [
                ProgramDepositProgress::class,
            ];
        } else if ($this->activeRelationManager === '3') {
            return [
                ProgramWithdrawalProgress::class
            ];
        }





        return [];
    }
}
