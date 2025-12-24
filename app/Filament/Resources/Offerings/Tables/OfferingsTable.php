<?php

namespace App\Filament\Resources\Offerings\Tables;

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
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;

class OfferingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                // TextColumn::make('program_id')
                //     ->numeric()
                //     ->sortable(),
                TextColumn::make('program.name')->label('Program')->sortable(),

                TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('offering_date')
                    ->date()
                    ->sortable(),

                BadgeColumn::make('status')
                    ->sortable()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'completed',
                    ])
                    ->label('Status')
                    ->searchable(),
                TextColumn::make('description')
                    ->searchable(),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Filter::make('offering_date')
                    ->form([
                        DatePicker::make('from')
                            ->label('From date'),
                        DatePicker::make('until')
                            ->label('Until date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date) =>
                                $query->whereDate('offering_date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date) =>
                                $query->whereDate('offering_date', '<=', $date),
                            );
                    }),

                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()->visible(fn() => auth()->user()?->hasAnyRole([
                    'super_admin',
                    'finance',
                ])),
                DeleteAction::make()->visible(fn() => auth()->user()?->hasAnyRole([
                    'super_admin',
                    'finance',
                ])),
                ForceDeleteAction::make()->visible(fn() => auth()->user()?->hasAnyRole([
                    'super_admin',
                ])),
                RestoreAction::make()->visible(fn() => auth()->user()?->hasAnyRole([
                    'super_admin',
                ])),
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
