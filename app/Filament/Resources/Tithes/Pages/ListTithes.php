<?php

namespace App\Filament\Resources\Tithes\Pages;

use App\Filament\Resources\Tithes\TitheResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTithes extends ListRecords
{
    protected static string $resource = TitheResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->visible(fn() => auth()->user()?->hasAnyRole([
                'super_admin',
                'finance',
            ])),
        ];
    }
}
