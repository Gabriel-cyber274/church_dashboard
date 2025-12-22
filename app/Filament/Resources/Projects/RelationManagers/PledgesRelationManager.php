<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use App\Filament\Resources\Banks\BankResource;
use App\Filament\Resources\Members\MemberResource;
use App\Filament\Resources\Pledges\PledgeResource;
use App\Filament\Resources\Programs\ProgramResource;
use App\Filament\Resources\Roles\RoleResource;
use App\Filament\Resources\Users\UserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class PledgesRelationManager extends RelationManager
{
    protected static string $relationship = 'pledges';

    protected static ?string $relatedResource = PledgeResource::class;
    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
