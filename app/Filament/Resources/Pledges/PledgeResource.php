<?php

namespace App\Filament\Resources\Pledges;

use App\Filament\Resources\Pledges\Pages\CreatePledge;
use App\Filament\Resources\Pledges\Pages\EditPledge;
use App\Filament\Resources\Pledges\Pages\ListPledges;
use App\Filament\Resources\Pledges\Pages\ViewPledge;
use App\Filament\Resources\Pledges\RelationManagers\MembersRelationManager;
use App\Filament\Resources\Pledges\RelationManagers\ProgramsRelationManager;
use App\Filament\Resources\Pledges\RelationManagers\ProjectsRelationManager;
use App\Filament\Resources\Pledges\Schemas\PledgeForm;
use App\Filament\Resources\Pledges\Schemas\PledgeInfolist;
use App\Filament\Resources\Pledges\Tables\PledgesTable;
use App\Models\Pledge;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PledgeResource extends Resource
{
    protected static ?string $model = Pledge::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHandRaised;

    protected static ?string $recordTitleAttribute = 'amount';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole([
            'super_admin',
            'admin',
        ]);
    }


    public static function form(Schema $schema): Schema
    {
        return PledgeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PledgeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PledgesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
            ProgramsRelationManager::class,
            MembersRelationManager::class,
            ProjectsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPledges::route('/'),
            'create' => CreatePledge::route('/create'),
            'view' => ViewPledge::route('/{record}'),
            'edit' => EditPledge::route('/{record}/edit'),
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
