<?php

namespace App\Filament\Resources\Deposits\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DepositInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('member.full_name')->label('Member'),
                TextEntry::make('program.name')->label('Program'),
                TextEntry::make('project.name')->label('Project'),
                TextEntry::make('amount')
                    ->numeric(),
                TextEntry::make('deposit_date')
                    ->date(),

                TextEntry::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'completed',
                    ]),
                TextEntry::make('reference'),
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
