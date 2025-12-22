<?php

namespace App\Filament\Resources\ProgramCoordinators\Pages;

use App\Filament\Resources\ProgramCoordinators\ProgramCoordinatorResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProgramCoordinator extends ViewRecord
{
    protected static string $resource = ProgramCoordinatorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
