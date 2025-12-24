<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
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
                DatePicker::make('deadline'),
                TextInput::make('budget')
                    ->numeric()
                    ->default(null),
            ]);
    }
}
