<?php

namespace App\Filament\Resources\Members\RelationManagers;

use App\Filament\Resources\Departments\DepartmentResource;
use App\Filament\Resources\Pledges\PledgeResource;
use App\Models\Pledge;
use App\Models\Program;
use App\Models\Project;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
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


class PledgesRelationManager extends RelationManager
{
    protected static string $relationship = 'pledges';

    protected static ?string $relatedResource = null; // we will handle form inline

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('amount'),
                TextColumn::make('pledge_date')->date(),
                TextColumn::make('status'),
                TextColumn::make('program.name')->label('Program'),
                TextColumn::make('project.name')->label('Project'),
            ])
            ->headerActions([
                CreateAction::make('createPledge')
                    ->modalHeading(fn() => 'Add Pledge for ' . $this->getOwnerRecord()->full_name)
                    ->form([
                        // member_id is hidden and prefilled
                        Select::make('member_id')
                            ->default(fn() => $this->getOwnerRecord()->id)
                            ->hidden(),

                        Select::make('program_id')
                            ->label('Program')
                            ->options(\App\Models\Program::all()->pluck('name', 'id'))
                            ->searchable(),
                        // ->required(),

                        Select::make('project_id')
                            ->label('Project')
                            ->options(\App\Models\Project::all()->pluck('name', 'id'))
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
                        TextInput::make('name')->default(null),
                        TextInput::make('phone_number')->tel()->default(null),
                    ])
                    ->action(function ($data) {
                        // create pledge for this member
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
