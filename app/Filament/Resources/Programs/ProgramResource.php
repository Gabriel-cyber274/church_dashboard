<?php

namespace App\Filament\Resources\Programs;

use App\Filament\Resources\Programs\Pages\CreateProgram;
use App\Filament\Resources\Programs\Pages\EditProgram;
use App\Filament\Resources\Programs\Pages\ListPrograms;
use App\Filament\Resources\Programs\Pages\ViewProgram;
use App\Filament\Resources\Programs\RelationManagers\AttendeesRelationManager;
use App\Filament\Resources\Programs\RelationManagers\BanksRelationManager;
use App\Filament\Resources\Programs\RelationManagers\CoordinatorsRelationManager;
use App\Filament\Resources\Programs\RelationManagers\DepositsRelationManager;
use App\Filament\Resources\Programs\RelationManagers\OfferingsRelationManager;
use App\Filament\Resources\Programs\RelationManagers\PledgesRelationManager;
use App\Filament\Resources\Programs\RelationManagers\TithesRelationManager;
use App\Filament\Resources\Programs\RelationManagers\WithdrawalsRelationManager;
use App\Filament\Resources\Programs\Schemas\ProgramForm;
use App\Filament\Resources\Programs\Schemas\ProgramInfolist;
use App\Filament\Resources\Programs\Tables\ProgramsTable;
use App\Models\Program;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProgramResource extends Resource
{
    protected static ?string $model = Program::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Programmes';

    protected static ?string $modelLabel = 'programme';

    protected static ?string $pluralModelLabel = 'programmes';

    public static function form(Schema $schema): Schema
    {
        return ProgramForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProgramInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProgramsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
            BanksRelationManager::class,
            CoordinatorsRelationManager::class,
            PledgesRelationManager::class,
            DepositsRelationManager::class,
            WithdrawalsRelationManager::class,
            OfferingsRelationManager::class,
            TithesRelationManager::class,
            AttendeesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPrograms::route('/'),
            'create' => CreateProgram::route('/create'),
            'view' => ViewProgram::route('/{record}'),
            'edit' => EditProgram::route('/{record}/edit'),
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
