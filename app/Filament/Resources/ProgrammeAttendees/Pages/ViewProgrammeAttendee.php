<?php

namespace App\Filament\Resources\ProgrammeAttendees\Pages;

use App\Filament\Resources\ProgrammeAttendees\ProgrammeAttendeeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProgrammeAttendee extends ViewRecord
{
    protected static string $resource = ProgrammeAttendeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
