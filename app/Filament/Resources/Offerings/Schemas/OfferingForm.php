<?php

namespace App\Filament\Resources\Offerings\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use App\Models\Program;
use Filament\Forms\Components\Select;

class OfferingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('program_id')
                    ->label('Program')
                    ->options(Program::all()->pluck('name', 'id'))
                    ->searchable(),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Select::make('status')
                    ->label('Status')
                    ->options(
                        fn() => auth()->user()?->hasRole('super_admin')
                            ? [
                                'pending' => 'Pending',
                                'completed' => 'Completed',
                            ]
                            : [
                                'pending' => 'Pending',
                            ]
                    )
                    ->default('pending')
                    ->required()
                    ->visible(fn() => auth()->user()?->hasAnyRole(['super_admin', 'finance'])),
                DatePicker::make('offering_date')
                    ->required(),
                Textarea::make('description')
                    ->default(null),
            ]);
    }
}
