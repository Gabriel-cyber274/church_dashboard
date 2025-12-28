<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use App\Filament\Resources\Pledges\PledgeResource;
use App\Models\Pledge;
use App\Models\Program;
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
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Model;


class PledgesRelationManager extends RelationManager
{
    protected static string $relationship = 'pledges';

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
            ->defaultSort('pledges.id', 'desc')
            ->columns([
                TextColumn::make('amount'),
                TextColumn::make('pledge_date')->date(),

                BadgeColumn::make('status')
                    ->sortable()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'completed',
                    ])
                    ->label('Status')
                    ->searchable(),
                TextColumn::make('member.full_name')->label('Member'),
                TextColumn::make('program.name')->label('Program'),
            ])
            ->headerActions([
                CreateAction::make('createPledge')->visible(fn() => auth()->user()?->hasAnyRole([
                    'super_admin',
                    'finance',
                ]))
                    ->modalHeading(fn() => 'Add Pledge for ' . $this->getOwnerRecord()->name)
                    ->form([
                        // project_id is hidden and prefilled
                        Select::make('project_id')
                            ->default(fn() => $this->getOwnerRecord()->id)
                            ->hidden(),

                        Select::make('member_id')
                            ->label('Member')
                            ->options(\App\Models\Member::all()->pluck('full_name', 'id'))
                            ->searchable(),
                        // ->required(),

                        TextInput::make('amount')->required()->numeric(),
                        DatePicker::make('pledge_date')->required(),
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'completed' => 'Completed',
                            ])
                            ->default('pending')
                            ->required(),
                        Textarea::make('note')->default(null),

                        TextInput::make('name')->default(null),
                        TextInput::make('phone_number')->tel()->default(null),
                        TextInput::make('email')
                            ->default(null),
                    ])
                    ->action(function ($data) {
                        // create pledge for this project
                        $this->getOwnerRecord()->pledges()->create($data);
                    }),
            ])
            ->filters([
                Filter::make('status')
                    ->form([
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'completed' => 'Completed',
                            ])
                            ->placeholder('All statuses'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['status'],
                            fn(Builder $query, string $status) =>
                            $query->where('status', $status),
                        );
                    }),

                Filter::make('pledge_date')
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
                                $query->whereDate('pledge_date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date) =>
                                $query->whereDate('pledge_date', '<=', $date),
                            );
                    }),

                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->url(fn(Pledge $record): string => PledgeResource::getUrl('view', ['record' => $record])),
                EditAction::make()->visible(fn() => auth()->user()?->hasAnyRole([
                    'super_admin',
                    'finance',
                ]))
                    ->form([
                        Select::make('member_id')
                            ->label('Member')
                            ->options(\App\Models\Member::all()->pluck('full_name', 'id'))
                            ->searchable(),
                        // ->required(),

                        TextInput::make('amount')->required()->numeric(),
                        DatePicker::make('pledge_date')->required(),
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'completed' => 'Completed',
                            ])
                            ->required(),
                        Textarea::make('note')->default(null),

                        TextInput::make('name')->default(null),
                        TextInput::make('phone_number')->tel()->default(null),
                        TextInput::make('email')
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
