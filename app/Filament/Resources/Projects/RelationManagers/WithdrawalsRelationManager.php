<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use App\Filament\Resources\Banks\BankResource;
use App\Filament\Resources\Deposits\DepositResource;
use App\Filament\Resources\Members\MemberResource;
use App\Filament\Resources\Programs\ProgramResource;
use App\Filament\Resources\Roles\RoleResource;
use App\Filament\Resources\Users\UserResource;
use App\Filament\Resources\Withdrawals\WithdrawalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class WithdrawalsRelationManager extends RelationManager
{
    protected static string $relationship = 'withdrawals';

    protected static ?string $relatedResource = WithdrawalResource::class;
    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
