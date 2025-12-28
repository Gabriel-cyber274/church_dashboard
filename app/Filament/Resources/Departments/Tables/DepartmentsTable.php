<?php

namespace App\Filament\Resources\Departments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class DepartmentsTable
{
    public static function configure(Table $table): Table
    {
        $user = Auth::user();

        return $table
            ->defaultSort('id', 'desc')
            ->modifyQueryUsing(function (Builder $query) use ($user) {
                // If user has department_id and is department leader, filter by department
                if ($user->is_department_leader && $user->department_id) {
                    $query->where('id', $user->department_id);
                }
                return $query;
            })
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('description')
                    ->limit(50)
                    ->wrap(),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()->visible(fn() => auth()->user()?->hasAnyRole([
                    'super_admin',
                    'admin',
                    'hod',
                    'pastors'
                ])),
                DeleteAction::make()->visible(fn() => auth()->user()?->hasAnyRole([
                    'super_admin',
                    'admin',
                    'hod',
                    'pastors'
                ])),
                ForceDeleteAction::make()->visible(fn() => auth()->user()?->hasAnyRole([
                    'super_admin',
                    'admin',
                    'hod',
                ])),
                RestoreAction::make()->visible(fn() => auth()->user()?->hasAnyRole([
                    'super_admin',
                    'admin',
                    'hod',
                ])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ])->visible(fn() => auth()->user()?->hasAnyRole([
                    'super_admin',
                    'admin',
                    'hod',
                ])),
            ]);
    }
}
