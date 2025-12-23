<?php

namespace App\Filament\Resources\Programs\RelationManagers;

use App\Filament\Resources\Tithes\TitheResource;
use App\Models\Tithe;
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

use Illuminate\Database\Eloquent\Model;


class TithesRelationManager extends RelationManager
{
    protected static string $relationship = 'tithes';

    protected static ?string $relatedResource = null; // we will handle form inline

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
                TextColumn::make('amount'),
                TextColumn::make('tithe_date')->date(),
                TextColumn::make('description'),
            ])
            ->headerActions([
                CreateAction::make('createTithe')
                    ->modalHeading(fn() => 'Add Tithe for ' . $this->getOwnerRecord()->name)
                    ->form([
                        // program_id is hidden and prefilled
                        \Filament\Forms\Components\Select::make('program_id')
                            ->default(fn() => $this->getOwnerRecord()->id)
                            ->hidden(),

                        TextInput::make('amount')
                            ->required()
                            ->numeric(),
                        DatePicker::make('tithe_date')
                            ->required(),
                        Textarea::make('description')
                            ->default(null),
                    ])
                    ->action(function ($data) {
                        // create tithe for this program
                        $this->getOwnerRecord()->tithes()->create($data);
                    }),
            ])
            ->filters([
                Filter::make('tithe_date')
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
                                $query->whereDate('tithe_date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date) =>
                                $query->whereDate('tithe_date', '<=', $date),
                            );
                    }),

                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->url(fn(Tithe $record): string => TitheResource::getUrl('view', ['record' => $record])),
                EditAction::make()
                    ->form([
                        TextInput::make('amount')
                            ->required()
                            ->numeric(),
                        DatePicker::make('tithe_date')
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
