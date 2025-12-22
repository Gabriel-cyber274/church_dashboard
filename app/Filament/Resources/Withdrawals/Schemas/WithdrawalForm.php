<?php

namespace App\Filament\Resources\Withdrawals\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use App\Models\Program;
use App\Models\Project;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

class WithdrawalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Select::make('program_id')
                    ->label('Program')
                    ->options(Program::all()->pluck('name', 'id'))
                    ->searchable(),

                Select::make('project_id')
                    ->label('Project')
                    ->options(Project::all()->pluck('name', 'id'))
                    ->searchable(),

                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                DatePicker::make('withdrawal_date')
                    ->required(),
                Textarea::make('description')
                    ->default(null),
            ]);
    }
}
