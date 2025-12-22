<?php

namespace App\Filament\Resources\ProgramCoordinators\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProgramCoordinatorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // TextColumn::make('program_id')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('member_id')
                //     ->numeric()
                //     ->sortable(),
                TextColumn::make('program_name')
                    ->label('Program')
                    ->getStateUsing(function ($record) {
                        return $record->program->name ?? 'N/A';
                    })
                    ->sortable(query: function ($query, $direction) {
                        $query->join('programs', 'your_table.program_id', '=', 'programs.id')
                            ->orderBy('programs.name', $direction);
                    })
                    ->searchable(query: function ($query, $search) {
                        $query->whereHas('program', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                    }),

                TextColumn::make('member.full_name')
                    ->label('Member Name')
                    ->getStateUsing(fn($record) => ($record->member?->first_name ?? '') . ' ' . ($record->member?->last_name ?? ''))
                    ->placeholder('No Member')
                    ->sortable(query: function ($query, $direction) {
                        $query->leftJoin('members', 'program_coordinators.member_id', '=', 'members.id')
                            ->orderBy('members.first_name', $direction)
                            ->orderBy('members.last_name', $direction);
                    })
                    ->searchable(query: function ($query, $search) {
                        $query->whereHas('member', function ($q) use ($search) {
                            $q->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        });
                    }),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
