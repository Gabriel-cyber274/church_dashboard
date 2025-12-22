<?php

namespace App\Filament\Resources\Tithes\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use App\Models\Program;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

class TitheForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextInput::make('program_id')
                //     ->numeric()
                //     ->default(null),
                Select::make('program_id')
                    ->label('Program')
                    ->options(Program::all()->pluck('name', 'id'))
                    ->searchable(),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                DatePicker::make('tithe_date')
                    ->required(),
                Textarea::make('description')
                    ->default(null),
            ]);
    }
}
