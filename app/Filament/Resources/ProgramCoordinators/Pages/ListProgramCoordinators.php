<?php

namespace App\Filament\Resources\ProgramCoordinators\Pages;

use App\Filament\Resources\ProgramCoordinators\ProgramCoordinatorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProgramCoordinators extends ListRecords
{
    protected static string $resource = ProgramCoordinatorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
