<?php

namespace App\Filament\Resources\ProgramCoordinators\Pages;

use App\Filament\Resources\ProgramCoordinators\ProgramCoordinatorResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditProgramCoordinator extends EditRecord
{
    protected static string $resource = ProgramCoordinatorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
