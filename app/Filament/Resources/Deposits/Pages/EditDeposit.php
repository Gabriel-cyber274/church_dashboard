<?php

namespace App\Filament\Resources\Deposits\Pages;

use App\Filament\Resources\Deposits\DepositResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDeposit extends EditRecord
{
    protected static string $resource = DepositResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make()->visible(fn() => auth()->user()?->hasAnyRole([
                'super_admin',
                'finance',
            ])),
            ForceDeleteAction::make()->visible(fn() => auth()->user()?->hasAnyRole([
                'super_admin',
            ])),
            RestoreAction::make()->visible(fn() => auth()->user()?->hasAnyRole([
                'super_admin',
            ])),
        ];
    }
}
