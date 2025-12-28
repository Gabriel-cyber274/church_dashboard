<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use App\Filament\Resources\Deposits\DepositResource;
use App\Models\Deposit;
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


class DepositsRelationManager extends RelationManager
{
    protected static string $relationship = 'deposits';

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
            ->defaultSort('deposits.id', 'desc')
            ->columns([
                TextColumn::make('amount'),
                TextColumn::make('deposit_date')->date(),

                BadgeColumn::make('status')
                    ->sortable()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'completed',
                    ])
                    ->label('Status')
                    ->searchable(),
                TextColumn::make('description'),
                TextColumn::make('member.full_name')->label('Member'),
                TextColumn::make('program.name')->label('Program'),
            ])
            ->headerActions([
                CreateAction::make('createDeposit')->visible(fn() => auth()->user()?->hasAnyRole([
                    'super_admin',
                    'finance',
                ]))
                    ->modalHeading(fn() => 'Add Deposit for ' . $this->getOwnerRecord()->name)
                    ->form([
                        // project_id is hidden and prefilled
                        Select::make('project_id')
                            ->default(fn() => $this->getOwnerRecord()->id)
                            ->hidden(),

                        Select::make('member_id')
                            ->label('Member')
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search) {
                                return \App\Models\Member::query()
                                    ->where('first_name', 'like', "%{$search}%")
                                    ->orWhere('last_name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%")
                                    ->orWhere('phone_number', 'like', "%{$search}%")
                                    ->limit(50)
                                    ->get()
                                    ->mapWithKeys(function ($member) {
                                        return [$member->id => "{$member->first_name} {$member->last_name} ({$member->email}, {$member->phone_number})"];
                                    })
                                    ->toArray();
                            })
                            ->getOptionLabelUsing(function ($value): ?string {
                                $member = \App\Models\Member::find($value);
                                return $member ? "{$member->first_name} {$member->last_name} ({$member->email}, {$member->phone_number})" : null;
                            }),
                        // ->required(),


                        TextInput::make('amount')
                            ->required()
                            ->numeric(),
                        DatePicker::make('deposit_date')
                            ->required(),
                        Textarea::make('description')
                            ->default(null),
                    ])
                    ->action(function ($data) {
                        // create deposit for this project
                        $this->getOwnerRecord()->deposits()->create($data);
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

                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->url(fn(Deposit $record): string => DepositResource::getUrl('view', ['record' => $record])),
                EditAction::make()->visible(fn() => auth()->user()?->hasAnyRole([
                    'super_admin',
                    'finance',
                ]))
                    ->form([
                        Select::make('member_id')
                            ->label('Member')
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search) {
                                return \App\Models\Member::query()
                                    ->where('first_name', 'like', "%{$search}%")
                                    ->orWhere('last_name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%")
                                    ->orWhere('phone_number', 'like', "%{$search}%")
                                    ->limit(50)
                                    ->get()
                                    ->mapWithKeys(function ($member) {
                                        return [$member->id => "{$member->first_name} {$member->last_name} ({$member->email}, {$member->phone_number})"];
                                    })
                                    ->toArray();
                            })
                            ->getOptionLabelUsing(function ($value): ?string {
                                $member = \App\Models\Member::find($value);
                                return $member ? "{$member->first_name} {$member->last_name} ({$member->email}, {$member->phone_number})" : null;
                            }),
                        // ->required(),

                        TextInput::make('amount')
                            ->required()
                            ->numeric(),
                        DatePicker::make('deposit_date')
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
