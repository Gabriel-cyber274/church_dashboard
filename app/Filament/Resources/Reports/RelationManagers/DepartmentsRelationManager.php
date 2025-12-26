<?php

namespace App\Filament\Resources\Reports\RelationManagers;

use App\Filament\Resources\Departments\DepartmentResource;
use App\Models\Department;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class DepartmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'departments';

    // protected static ?string $relatedResource = DepartmentResource::class;
    protected static ?string $relatedResource = null;

    protected static ?string $recordTitleAttribute = 'name';



    public function table(Table $table): Table
    {
        $user = Auth::user();
        return $table
            ->defaultSort('departments.id', 'desc')
            ->modifyQueryUsing(function (Builder $query) use ($user) {
                // If user has department_id and is department leader, filter by department
                if ($user->is_department_leader && $user->department_id) {
                    $query->where('departments.id', $user->department_id);
                }
                return $query;
            })
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('name')
                    ->label('Department Name')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->wrap(),
                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Add Department')
                    ->preloadRecordSelect()
                    ->recordSelectSearchColumns([
                        'name',
                    ])
                    ->multiple(),
            ])
            ->actions([
                ViewAction::make()
                    ->url(fn(Department $record): string => DepartmentResource::getUrl('view', ['record' => $record])),
                DetachAction::make()
                    ->label('Remove'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }
}
