<?php

namespace App\Filament\Resources\Offerings\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OfferingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextEntry::make('program_id')
                //     ->numeric(),
                TextEntry::make('program.name')->label('Program'),

                TextEntry::make('amount')
                    ->numeric(),
                TextEntry::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'completed',
                    ]),
                TextEntry::make('offering_date')
                    ->date(),

                TextEntry::make('description'),
                TextEntry::make('deleted_at')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
