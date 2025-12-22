<?php

namespace App\Filament\Resources\ProgramCoordinators;

use App\Filament\Resources\ProgramCoordinators\Pages\CreateProgramCoordinator;
use App\Filament\Resources\ProgramCoordinators\Pages\EditProgramCoordinator;
use App\Filament\Resources\ProgramCoordinators\Pages\ListProgramCoordinators;
use App\Filament\Resources\ProgramCoordinators\Pages\ViewProgramCoordinator;
use App\Filament\Resources\ProgramCoordinators\Schemas\ProgramCoordinatorForm;
use App\Filament\Resources\ProgramCoordinators\Schemas\ProgramCoordinatorInfolist;
use App\Filament\Resources\ProgramCoordinators\Tables\ProgramCoordinatorsTable;
use App\Models\ProgramCoordinator;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProgramCoordinatorResource extends Resource
{
    protected static ?string $model = ProgramCoordinator::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserCircle;

    protected static ?string $recordTitleAttribute = 'program_id';

    protected static ?string $navigationLabel = 'Programme Coordinators';

    protected static bool $shouldRegisterNavigation = false;



    public static function form(Schema $schema): Schema
    {
        return ProgramCoordinatorForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProgramCoordinatorInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProgramCoordinatorsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProgramCoordinators::route('/'),
            'create' => CreateProgramCoordinator::route('/create'),
            'view' => ViewProgramCoordinator::route('/{record}'),
            'edit' => EditProgramCoordinator::route('/{record}/edit'),
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
