<?php

namespace App\Filament\Resources\Pledges\Pages;

use App\Filament\Resources\Pledges\PledgeResource;
use App\Filament\Resources\Pledges\Widgets\PledgeDepositStatsWidget;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPledge extends ViewRecord
{
    protected static string $resource = PledgeResource::class;

    protected $queryString = ['activeRelationManager'];


    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()->visible(fn() => auth()->user()?->hasAnyRole([
                'super_admin',
                'finance',
            ])),
        ];
    }

    protected function getFooterWidgets(): array
    {
        // Check if we're on the Pledges tab (index 2)
        if ($this->activeRelationManager === '3') {
            return [
                PledgeDepositStatsWidget::class,
            ];
        }





        return [];
    }
}
