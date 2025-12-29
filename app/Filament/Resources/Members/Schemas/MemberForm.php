<?php

namespace App\Filament\Resources\Members\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('first_name')
                    ->required(),
                TextInput::make('last_name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->default(null),
                DatePicker::make('date_of_birth'),
                TextInput::make('country')
                    ->default(null),
                TextInput::make('state')
                    ->default(null),
                TextInput::make('city')
                    ->default(null),
                TextInput::make('address')
                    ->default(null),
                TextInput::make('phone_number')
                    ->tel()
                    ->default(null),

                Select::make('gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                        // 'other' => 'Other',
                    ])
                    ->placeholder('Select Gender')
                    ->default(null),

                Select::make('marital_status')
                    ->options([
                        'single' => 'Single',
                        'married' => 'Married',
                        'divorced' => 'Divorced',
                        'widowed' => 'Widowed',
                    ])
                    ->placeholder('Select Marital Status')
                    ->default(null),
            ]);
    }
}
