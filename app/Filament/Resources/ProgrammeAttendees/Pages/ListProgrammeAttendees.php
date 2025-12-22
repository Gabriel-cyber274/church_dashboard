<?php

namespace App\Filament\Resources\ProgrammeAttendees\Pages;

use App\Filament\Resources\ProgrammeAttendees\ProgrammeAttendeeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProgrammeAttendees extends ListRecords
{
    protected static string $resource = ProgrammeAttendeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
