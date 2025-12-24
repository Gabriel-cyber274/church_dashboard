<?php

namespace App\Filament\Resources\Programs\RelationManagers;

use App\Filament\Resources\Banks\BankResource;
use App\Filament\Resources\Members\MemberResource;
use App\Filament\Resources\Users\UserResource;
use App\Models\Member;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class CoordinatorsRelationManager extends RelationManager
{
    protected static string $relationship = 'coordinators';

    protected static ?string $relatedResource = MemberResource::class;

    protected static ?string $title = 'Programme Coordinators';
    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('members.id', 'desc')
            ->headerActions([
                // CreateAction::make(),
                AttachAction::make()
                    ->label('Add Coordinators')
                    ->preloadRecordSelect()
                    ->recordSelectSearchColumns([
                        'first_name',
                        'last_name',
                        'email',
                        'phone_number',
                    ])
                    ->multiple(),
            ])
            ->actions([
                ViewAction::make()
                    ->url(fn(Member $record): string => MemberResource::getUrl('view', ['record' => $record])),
                DetachAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }
}
