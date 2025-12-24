<?php

namespace App\Filament\Resources\Offerings;

use App\Filament\Resources\Offerings\Pages\CreateOffering;
use App\Filament\Resources\Offerings\Pages\EditOffering;
use App\Filament\Resources\Offerings\Pages\ListOfferings;
use App\Filament\Resources\Offerings\Pages\ViewOffering;
use App\Filament\Resources\Offerings\Schemas\OfferingForm;
use App\Filament\Resources\Offerings\Schemas\OfferingInfolist;
use App\Filament\Resources\Offerings\Tables\OfferingsTable;
use App\Filament\Resources\Offerings\RelationManagers\ProgramsRelationManager;
use App\Models\Offering;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OfferingResource extends Resource
{
    protected static ?string $model = Offering::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHeart;

    protected static ?string $recordTitleAttribute = 'amount';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole([
            'super_admin',
            'admin',
            'finance',
        ]);
    }

    public static function form(Schema $schema): Schema
    {
        return OfferingForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return OfferingInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OfferingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
            ProgramsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOfferings::route('/'),
            'create' => CreateOffering::route('/create'),
            'view' => ViewOffering::route('/{record}'),
            'edit' => EditOffering::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
