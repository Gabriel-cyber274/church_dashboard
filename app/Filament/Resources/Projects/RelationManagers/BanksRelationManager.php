<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use App\Filament\Resources\Banks\BankResource;
use App\Filament\Resources\Members\MemberResource;
use App\Filament\Resources\Programs\ProgramResource;
use App\Filament\Resources\Roles\RoleResource;
use App\Filament\Resources\Users\UserResource;
use App\Models\Bank;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\ViewAction;

class BanksRelationManager extends RelationManager
{
    protected static string $relationship = 'banks';

    protected static ?string $relatedResource = BankResource::class;

    public function table(Table $table): Table
    {
        return $table
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
