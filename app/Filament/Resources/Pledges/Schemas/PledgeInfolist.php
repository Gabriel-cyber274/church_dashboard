<?php

namespace App\Filament\Resources\Pledges\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PledgeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('member.full_name')->label('Member'),
                TextEntry::make('program.name')->label('Program'),
                TextEntry::make('project.name')->label('Project'),
                TextEntry::make('amount')
                    ->numeric(),
                TextEntry::make('pledge_date')
                    ->date(),
                TextEntry::make('status'),
                TextEntry::make('name'),
                TextEntry::make('phone_number'),
                TextEntry::make('deleted_at')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
