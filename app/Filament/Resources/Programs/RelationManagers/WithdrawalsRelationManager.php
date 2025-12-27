<?php

namespace App\Filament\Resources\Programs\RelationManagers;

use App\Filament\Resources\Withdrawals\WithdrawalResource;
use App\Models\Withdrawal;
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
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Model;


class WithdrawalsRelationManager extends RelationManager
{
    protected static string $relationship = 'withdrawals';

    protected static ?string $relatedResource = null; // we will handle form inline

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
            ->defaultSort('withdrawals.id', 'desc')
            ->columns([
                TextColumn::make('amount'),
                TextColumn::make('withdrawal_date')->date(),

                BadgeColumn::make('status')
                    ->sortable()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'completed',
                    ])
                    ->label('Status')
                    ->searchable(),
                TextColumn::make('description'),
                TextColumn::make('project.name')->label('Project'),
            ])
            ->headerActions([
                CreateAction::make('createWithdrawal')
                    ->visible(function () {
                        // Check if user has permission AND if program has deposits
                        $user = auth()->user();
                        $hasPermission = $user?->hasAnyRole(['super_admin', 'finance']);
                        $hasDeposits = $this->getOwnerRecord()->deposits()->exists();

                        return $hasPermission && $hasDeposits;
                    })
                    ->modalHeading(fn() => 'Add Withdrawal for ' . $this->getOwnerRecord()->name)
                    ->form([
                        // program_id is hidden and prefilled
                        Select::make('program_id')
                            ->default(fn() => $this->getOwnerRecord()->id)
                            ->hidden(),

                        Select::make('project_id')
                            ->label('Project')
                            ->options(\App\Models\Project::all()->pluck('name', 'id'))
                            ->searchable(),

                        TextInput::make('amount')
                            ->required()
                            ->numeric(),
                        DatePicker::make('withdrawal_date')
                            ->required(),
                        Textarea::make('description')
                            ->default(null),
                    ])
                    ->action(function ($data) {
                        // create withdrawal for this program
                        $this->getOwnerRecord()->withdrawals()->create($data);
                    }),
            ])
            ->filters([
                Filter::make('withdrawal_date')
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
                                $query->whereDate('withdrawal_date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date) =>
                                $query->whereDate('withdrawal_date', '<=', $date),
                            );
                    }),

                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->url(fn(Withdrawal $record): string => WithdrawalResource::getUrl('view', ['record' => $record])),
                EditAction::make()->visible(fn() => auth()->user()?->hasAnyRole([
                    'super_admin',
                    'finance',
                ]))
                    ->form([
                        Select::make('project_id')
                            ->label('Project')
                            ->options(\App\Models\Project::all()->pluck('name', 'id'))
                            ->searchable(),

                        TextInput::make('amount')
                            ->required()
                            ->numeric(),
                        DatePicker::make('withdrawal_date')
                            ->required(),
                        Textarea::make('description')
                            ->default(null),
                    ]),
                DeleteAction::make()->visible(fn() => auth()->user()?->hasAnyRole([
                    'super_admin',
                    'finance',
                ])),
                RestoreAction::make()->visible(fn() => auth()->user()?->hasAnyRole([
                    'super_admin',
                ])),
                ForceDeleteAction::make()->visible(fn() => auth()->user()?->hasAnyRole([
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
