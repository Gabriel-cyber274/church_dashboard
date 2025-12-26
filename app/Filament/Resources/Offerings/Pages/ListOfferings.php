<?php

namespace App\Filament\Resources\Offerings\Pages;

use App\Exports\OfferingsExport;
use App\Filament\Resources\Offerings\OfferingResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListOfferings extends ListRecords
{
    protected static string $resource = OfferingResource::class;

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
                        new OfferingsExport,
                        'offerings.xlsx'
                    )
                )
                ->visible(fn() => auth()->user()?->hasAnyRole([
                    'super_admin',
                    'finance',
                ])),
        ];
    }
}
