<?php

namespace App\Filament\Resources\Deposits\RelationManagers;

use App\Filament\Resources\Programs\ProgramResource;
use App\Filament\Resources\Projects\ProjectResource;
use App\Filament\Resources\Roles\RoleResource;
use App\Filament\Resources\Users\UserResource;
use App\Models\Project;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class ProjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'project';

    protected static ?string $relatedResource = ProjectResource::class;
    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                // CreateAction::make(),
            ])->recordActions([
                ViewAction::make()
                    ->url(fn(Project $record): string => ProjectResource::getUrl('view', ['record' => $record])),
                DeleteAction::make(),
            ])
        ;
    }
}
