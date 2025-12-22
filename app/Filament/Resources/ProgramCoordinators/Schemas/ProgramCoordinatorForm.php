<?php

namespace App\Filament\Resources\ProgramCoordinators\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProgramCoordinatorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('program_id')
                    ->required()
                    ->numeric(),
                TextInput::make('member_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}
