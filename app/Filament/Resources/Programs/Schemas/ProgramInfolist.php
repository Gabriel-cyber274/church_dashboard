<?php

namespace App\Filament\Resources\Programs\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProgramInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ImageEntry::make('flier_url')
                    ->label('Flier/Poster')
                    ->default(null)
                    ->height(200)
                    ->square()
                    ->visibility('public'),
                TextEntry::make('name'),
                TextEntry::make('start_date')
                    ->dateTime(),
                TextEntry::make('end_date')
                    ->dateTime(),
                TextEntry::make('location'),
                IconEntry::make('is_budgeted')
                    ->boolean(),
                TextEntry::make('budget')
                    ->numeric(),
                TextEntry::make('deleted_at')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
