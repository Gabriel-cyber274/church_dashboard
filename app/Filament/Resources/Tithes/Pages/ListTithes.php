<?php

namespace App\Filament\Resources\Tithes\Pages;

use App\Exports\TithesExport;
use App\Filament\Resources\Tithes\TitheResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;


class ListTithes extends ListRecords
{
    protected static string $resource = TitheResource::class;

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
                        new TithesExport,
                        'tithes.xlsx'
                    )
                )
                ->visible(fn() => auth()->user()?->hasAnyRole([
                    'super_admin',
                    'finance',
                ])),
        ];
    }
}
