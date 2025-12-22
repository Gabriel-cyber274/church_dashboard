<?php

namespace App\Filament\Resources\Programs\RelationManagers;

use App\Filament\Resources\Offerings\OfferingResource;
use App\Models\Offering;
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
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\BulkActionGroup;

class OfferingsRelationManager extends RelationManager
{
    protected static string $relationship = 'offerings';

    protected static ?string $relatedResource = null; // we will handle form inline

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('amount'),
                TextColumn::make('offering_date')->date(),
                TextColumn::make('description'),
            ])
            ->headerActions([
                CreateAction::make('createOffering')
                    ->modalHeading(fn() => 'Add Offering for ' . $this->getOwnerRecord()->name)
                    ->form([
                        // program_id is hidden and prefilled
                        \Filament\Forms\Components\Select::make('program_id')
                            ->default(fn() => $this->getOwnerRecord()->id)
                            ->hidden(),

                        TextInput::make('amount')
                            ->required()
                            ->numeric(),
                        DatePicker::make('offering_date')
                            ->required(),
                        Textarea::make('description')
                            ->default(null),
                    ])
                    ->action(function ($data) {
                        // create offering for this program
                        $this->getOwnerRecord()->offerings()->create($data);
                    }),
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
                ViewAction::make()
                    ->url(fn(Offering $record): string => OfferingResource::getUrl('view', ['record' => $record])),
                EditAction::make()
                    ->form([
                        TextInput::make('amount')
                            ->required()
                            ->numeric(),
                        DatePicker::make('offering_date')
                            ->required(),
                        Textarea::make('description')
                            ->default(null),
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
            ]);
    }
}
