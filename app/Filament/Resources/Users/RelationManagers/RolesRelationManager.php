<?php

namespace App\Filament\Resources\Users\RelationManagers;

use App\Filament\Resources\Roles\RoleResource;
use App\Filament\Resources\Users\UserResource;
use App\Models\Role;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class RolesRelationManager extends RelationManager
{
    protected static string $relationship = 'roles';

    protected static ?string $relatedResource = RoleResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('roles.id', 'desc')
            ->headerActions([
                // CreateAction::make(),
                AttachAction::make()
                    ->label('Add Role')
                    ->preloadRecordSelect()
                    ->recordSelectSearchColumns([
                        'name',
                        'description',
                    ])
                    ->multiple(),
            ])
            ->actions([
                ViewAction::make()
                    ->url(fn(Role $record): string => RoleResource::getUrl('view', ['record' => $record])),
                DetachAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }
}
