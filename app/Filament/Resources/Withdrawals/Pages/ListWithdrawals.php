<?php

namespace App\Filament\Resources\Withdrawals\Pages;

use App\Exports\WithdrawalsExport;
use App\Filament\Resources\Withdrawals\WithdrawalResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;


class ListWithdrawals extends ListRecords
{
    protected static string $resource = WithdrawalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->visible(fn() => auth()->user()?->hasAnyRole([
                'super_admin',
                'finance',
            ])),

            Action::make('export')
                ->label('Download Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(
                    fn() =>
                    Excel::download(
                        new WithdrawalsExport,
                        'withdrawals.xlsx'
                    )
                )
                ->visible(fn() => auth()->user()?->hasAnyRole([
                    'super_admin',
                    'finance',
                ])),
        ];
    }
}
