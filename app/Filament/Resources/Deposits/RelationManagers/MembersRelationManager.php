<?php

namespace App\Filament\Resources\Deposits\RelationManagers;

use App\Filament\Resources\Members\MemberResource;
use App\Filament\Resources\Programs\ProgramResource;
use App\Filament\Resources\Roles\RoleResource;
use App\Filament\Resources\Users\UserResource;
use App\Models\Member;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class MembersRelationManager extends RelationManager
{
    protected static string $relationship = 'member';

    protected static ?string $relatedResource = MemberResource::class;
    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                // CreateAction::make(),
            ])->recordActions([
                ViewAction::make()
                    ->url(fn(Member $record): string => MemberResource::getUrl('view', ['record' => $record])),
                DeleteAction::make(),
            ]);
    }
}
