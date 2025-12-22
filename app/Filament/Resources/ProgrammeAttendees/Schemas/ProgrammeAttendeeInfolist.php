<?php

namespace App\Filament\Resources\ProgrammeAttendees\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProgrammeAttendeeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('member.full_name')->label('Member'),
                TextEntry::make('program.name')->label('Program'),
                TextEntry::make('attendance_time')
                    ->time(),
                TextEntry::make('name'),
                TextEntry::make('phone_number'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
                TextEntry::make('deleted_at')
                    ->dateTime(),
            ]);
    }
}
