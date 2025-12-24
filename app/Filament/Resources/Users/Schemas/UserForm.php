<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\Department;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required()
                    ->maxLength(255),

                DateTimePicker::make('email_verified_at'),

                Select::make('department_id')
                    ->label('Department')
                    ->relationship('department', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Toggle::make('is_department_leader')
                    ->label('Department Leader')
                    ->default(false),

                TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->required(fn($context) => $context === 'create')
                    ->dehydrateStateUsing(fn($state) => filled($state) ? $state : null)
                    ->maxLength(255),

            ]);
    }
}
