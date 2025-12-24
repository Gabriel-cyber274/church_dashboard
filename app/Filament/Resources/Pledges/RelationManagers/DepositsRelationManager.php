<?php

namespace App\Filament\Resources\Pledges\RelationManagers;

use App\Filament\Resources\Deposits\DepositResource;
use App\Models\Deposit;
use Filament\Actions\BulkActionGroup as ActionsBulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Model;

class DepositsRelationManager extends RelationManager
{
    protected static string $relationship = 'deposits';

    protected static ?string $relatedResource = null;

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole([
            'super_admin',
            'admin',
            'finance'
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('deposits.id', 'desc')
            ->columns([
                TextColumn::make('amount')
                    ->money('NGN')
                    ->sortable(),

                TextColumn::make('deposit_date')
                    ->date()
                    ->sortable(),

                BadgeColumn::make('status')
                    ->sortable()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'completed',
                    ])
                    ->label('Status'),

                TextColumn::make('description')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    }),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                CreateAction::make('createDeposit')
                    ->visible(fn() => auth()->user()?->hasAnyRole([
                        'super_admin',
                        'finance',
                    ]))
                    ->modalHeading(fn() => 'Add Deposit for Pledge #' . $this->getOwnerRecord()->id)
                    ->form([
                        // These fields will be automatically filled from the pledge
                        TextInput::make('member_id')
                            ->default(fn() => $this->getOwnerRecord()->member_id)
                            ->hidden(),

                        TextInput::make('program_id')
                            ->default(fn() => $this->getOwnerRecord()->program_id)
                            ->hidden(),

                        TextInput::make('project_id')
                            ->default(fn() => $this->getOwnerRecord()->project_id)
                            ->hidden(),

                        TextInput::make('amount')
                            ->label('Deposit Amount')
                            ->required()
                            ->numeric()
                            ->minValue(1),

                        DatePicker::make('deposit_date')
                            ->required()
                            ->default(now()),

                        Textarea::make('description')
                            ->rows(3)
                            ->placeholder('Optional description or notes'),
                    ])
                    ->action(function ($data) {
                        // Add pledge_id to the data
                        $data['pledge_id'] = $this->getOwnerRecord()->id;

                        // Create deposit for this pledge
                        $this->getOwnerRecord()->deposits()->create($data);
                    })
                    ->after(function () {
                        // Optionally refresh the table
                        $this->getOwnerRecord()->refresh();
                    }),
            ])
            ->filters([
                Filter::make('deposit_date')
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
                                $query->whereDate('deposit_date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date) =>
                                $query->whereDate('deposit_date', '<=', $date),
                            );
                    }),

                Filter::make('amount_range')
                    ->form([
                        TextInput::make('min_amount')
                            ->numeric()
                            ->label('Minimum Amount'),
                        TextInput::make('max_amount')
                            ->numeric()
                            ->label('Maximum Amount'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['min_amount'],
                                fn(Builder $query, $amount) => $query->where('amount', '>=', $amount),
                            )
                            ->when(
                                $data['max_amount'],
                                fn(Builder $query, $amount) => $query->where('amount', '<=', $amount),
                            );
                    }),

                Filter::make('status')
                    ->form([
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'completed' => 'Completed',
                            ])
                            ->placeholder('All statuses'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['status'],
                            fn(Builder $query, $status) => $query->where('status', $status)
                        );
                    }),

                TrashedFilter::make(),
            ])
            ->actions([
                ViewAction::make()
                    ->url(fn(Deposit $record): string => DepositResource::getUrl('view', ['record' => $record])),

                EditAction::make()
                    ->visible(fn() => auth()->user()?->hasAnyRole([
                        'super_admin',
                        'finance',
                    ]))
                    ->form([
                        TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->minValue(1),

                        DatePicker::make('deposit_date')
                            ->required(),


                        Textarea::make('description')
                            ->rows(3),
                    ]),

                DeleteAction::make()
                    ->visible(fn() => auth()->user()?->hasAnyRole([
                        'super_admin',
                        'finance',
                    ])),

                RestoreAction::make()
                    ->visible(fn() => auth()->user()?->hasAnyRole([
                        'super_admin',
                    ])),

                ForceDeleteAction::make()
                    ->visible(fn() => auth()->user()?->hasAnyRole([
                        'super_admin',
                    ])),
            ])
            ->bulkActions([
                ActionsBulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn() => auth()->user()?->hasAnyRole([
                            'super_admin',
                            'finance',
                        ])),

                    RestoreBulkAction::make()
                        ->visible(fn() => auth()->user()?->hasAnyRole([
                            'super_admin',
                        ])),

                    ForceDeleteBulkAction::make()
                        ->visible(fn() => auth()->user()?->hasAnyRole([
                            'super_admin',
                        ])),
                ]),
            ]);
    }
}
