<?php

namespace App\Filament\Resources\ProgrammeAttendees;

use App\Filament\Resources\ProgrammeAttendees\Pages\CreateProgrammeAttendee;
use App\Filament\Resources\ProgrammeAttendees\Pages\EditProgrammeAttendee;
use App\Filament\Resources\ProgrammeAttendees\Pages\ListProgrammeAttendees;
use App\Filament\Resources\ProgrammeAttendees\Pages\ViewProgrammeAttendee;
use App\Filament\Resources\ProgrammeAttendees\Schemas\ProgrammeAttendeeForm;
use App\Filament\Resources\ProgrammeAttendees\Schemas\ProgrammeAttendeeInfolist;
use App\Filament\Resources\ProgrammeAttendees\Tables\ProgrammeAttendeesTable;
use App\Models\ProgrammeAttendee;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProgrammeAttendeeResource extends Resource
{
    protected static ?string $model = ProgrammeAttendee::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'program_id';

    protected static bool $shouldRegisterNavigation = false;


    public static function form(Schema $schema): Schema
    {
        return ProgrammeAttendeeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProgrammeAttendeeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProgrammeAttendeesTable::configure($table);
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
            'index' => ListProgrammeAttendees::route('/'),
            'create' => CreateProgrammeAttendee::route('/create'),
            'view' => ViewProgrammeAttendee::route('/{record}'),
            'edit' => EditProgrammeAttendee::route('/{record}/edit'),
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
