<?php

namespace App\Filament\Resources\Programs\RelationManagers;

use App\Filament\Resources\Banks\BankResource;
use App\Filament\Resources\Users\UserResource;
use App\Models\Bank;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class BanksRelationManager extends RelationManager
{
    protected static string $relationship = 'banks';

    protected static ?string $relatedResource = BankResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('banks.id', 'desc')
            ->headerActions([
                // CreateAction::make(),
                AttachAction::make()
                    ->label('Add Bank')
                    ->preloadRecordSelect()
                    ->recordSelectSearchColumns([
                        'bank_name',
                        'account_number',
                        'account_holder_name',
                    ])
                    ->multiple(),
            ])
            ->actions([
                ViewAction::make()
                    ->url(fn(Bank $record): string => BankResource::getUrl('view', ['record' => $record])),
                DetachAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }
}
