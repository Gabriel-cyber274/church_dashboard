<?php

namespace App\Filament\Resources\Members\RelationManagers;

use App\Filament\Resources\Programs\ProgramResource;
use App\Models\Program;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class ProgramsCoordinatedRelationManager extends RelationManager
{
    protected static string $relationship = 'programsCoordinated';

    protected static ?string $relatedResource = ProgramResource::class;

    protected static ?string $title = 'Programmes Coordinated';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Program Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->limit(50),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Add Programme')
                    ->preloadRecordSelect()
                    ->recordSelectSearchColumns([
                        'name',
                        'description',
                    ])
                    ->multiple(),
            ])
            ->actions([
                ViewAction::make()
                    ->url(fn(Program $record): string => ProgramResource::getUrl('view', ['record' => $record])),
                DetachAction::make()
                    ->label('Remove'),
            ]);
    }
}
