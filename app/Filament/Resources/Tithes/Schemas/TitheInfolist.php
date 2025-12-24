<?php

namespace App\Filament\Resources\Tithes\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TitheInfolist
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
                TextEntry::make('tithe_date')
                    ->date(),
                TextEntry::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'completed',
                    ]),

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
