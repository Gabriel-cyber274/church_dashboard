<?php

namespace App\Filament\Resources\Tithes\Pages;

use App\Filament\Resources\Tithes\TitheResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTithe extends ViewRecord
{
    protected static string $resource = TitheResource::class;

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
