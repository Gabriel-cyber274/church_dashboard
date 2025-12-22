<?php

namespace App\Filament\Resources\ProgrammeAttendees\Pages;

use App\Filament\Resources\ProgrammeAttendees\ProgrammeAttendeeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditProgrammeAttendee extends EditRecord
{
    protected static string $resource = ProgrammeAttendeeResource::class;

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
