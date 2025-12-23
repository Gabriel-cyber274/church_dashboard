<?php

namespace App\Filament\Resources\Members\RelationManagers;

use App\Filament\Resources\Deposits\DepositResource;
use App\Models\Deposit;
use App\Models\Program;
use App\Models\Project;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Filters\TrashedFilter;

use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
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
        ]);
    }


    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('amount')
                    ->sortable(),
                TextColumn::make('deposit_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('description')
                    ->limit(50),
                TextColumn::make('program.name')
                    ->label('Program')
                    ->sortable(),
                TextColumn::make('project.name')
                    ->label('Project')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                CreateAction::make('createDeposit')
                    ->modalHeading(fn() => 'Add Deposit for ' . $this->getOwnerRecord()->full_name)
                    ->form([
                        // member_id is hidden and prefilled
                        Select::make('member_id')
                            ->default(fn() => $this->getOwnerRecord()->id)
                            ->hidden(),

                        Select::make('program_id')
                            ->label('Program')
                            ->options(Program::all()->pluck('name', 'id'))
                            ->searchable()
                            ->nullable(),

                        Select::make('project_id')
                            ->label('Project')
                            ->options(Project::all()->pluck('name', 'id'))
                            ->searchable()
                            ->nullable(),

                        TextInput::make('amount')
                            ->required()
                            ->numeric(),
                        DatePicker::make('deposit_date')
                            ->required()
                            ->default(now()),
                        Textarea::make('description')
                            ->rows(3)
                            ->maxLength(500)
                            ->nullable(),
                    ])
                    ->action(function ($data) {
                        // create deposit for this member
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

                Filter::make('amount')
                    ->form([
                        TextInput::make('min_amount')
                            ->label('Minimum Amount')
                            ->numeric(),
                        TextInput::make('max_amount')
                            ->label('Maximum Amount')
                            ->numeric(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['min_amount'],
                                fn(Builder $query, $amount) =>
                                $query->where('amount', '>=', $amount),
                            )
                            ->when(
                                $data['max_amount'],
                                fn(Builder $query, $amount) =>
                                $query->where('amount', '<=', $amount),
                            );
                    }),

                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->url(fn(Deposit $record): string => DepositResource::getUrl('view', ['record' => $record])),
                EditAction::make()
                    ->form([
                        Select::make('program_id')
                            ->label('Program')
                            ->options(Program::all()->pluck('name', 'id'))
                            ->searchable()
                            ->nullable(),

                        Select::make('project_id')
                            ->label('Project')
                            ->options(Project::all()->pluck('name', 'id'))
                            ->searchable()
                            ->nullable(),

                        TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->prefix('$'),
                        DatePicker::make('deposit_date')
                            ->required(),
                        Textarea::make('description')
                            ->rows(3)
                            ->maxLength(500)
                            ->nullable(),
                    ]),
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
            ])
            ->defaultSort('deposit_date', 'desc');
    }
}
