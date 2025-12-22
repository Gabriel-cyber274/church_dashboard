<?php

namespace App\Filament\Resources\Roles\RelationManagers;

use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    protected static ?string $relatedResource = UserResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                // CreateAction::make(),
            ])->recordActions([
                ViewAction::make()
                    ->url(fn(User $record): string => userResource::getUrl('view', ['record' => $record])),
                // DeleteAction::make(),
            ]);
    }
}
