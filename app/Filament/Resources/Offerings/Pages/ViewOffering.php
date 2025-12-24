<?php

namespace App\Filament\Resources\Offerings\Pages;

use App\Filament\Resources\Offerings\OfferingResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOffering extends ViewRecord
{
    protected static string $resource = OfferingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()->visible(fn() => auth()->user()?->hasAnyRole([
                'super_admin',
                'finance',
            ])),
        ];
    }
}
