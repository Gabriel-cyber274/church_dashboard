<?php

namespace App\Filament\Resources\Offerings\RelationManagers;

use App\Filament\Resources\Programs\ProgramResource;
use App\Filament\Resources\Roles\RoleResource;
use App\Filament\Resources\Users\UserResource;
use App\Models\Program;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class ProgramsRelationManager extends RelationManager
{
    protected static string $relationship = 'program';

    protected static ?string $relatedResource = ProgramResource::class;
    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('programs.id', 'desc') // if joining programs table

            ->headerActions([
                // CreateAction::make(),
            ])->recordActions([
                ViewAction::make()
                    ->url(fn(Program $record): string => ProgramResource::getUrl('view', ['record' => $record])),
                // DeleteAction::make(),
            ]);
    }
}
