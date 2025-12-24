<?php

namespace App\Filament\Resources\Tithes;

use App\Filament\Resources\Tithes\Pages\CreateTithe;
use App\Filament\Resources\Tithes\Pages\EditTithe;
use App\Filament\Resources\Tithes\Pages\ListTithes;
use App\Filament\Resources\Tithes\Pages\ViewTithe;
use App\Filament\Resources\Tithes\Schemas\TitheForm;
use App\Filament\Resources\Tithes\Schemas\TitheInfolist;
use App\Filament\Resources\Tithes\Tables\TithesTable;
use App\Filament\Resources\Tithes\RelationManagers\ProgramsRelationManager;
use App\Models\Tithe;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TitheResource extends Resource
{
    protected static ?string $model = Tithe::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?string $recordTitleAttribute = 'amount';

    protected static ?string $navigationLabel = 'Gratitudes';

    protected static ?string $modelLabel = 'gratitude';

    protected static ?string $pluralModelLabel = 'gratitudes';

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
        return TitheForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TitheInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TithesTable::configure($table);
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
            'index' => ListTithes::route('/'),
            'create' => CreateTithe::route('/create'),
            'view' => ViewTithe::route('/{record}'),
            'edit' => EditTithe::route('/{record}/edit'),
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
