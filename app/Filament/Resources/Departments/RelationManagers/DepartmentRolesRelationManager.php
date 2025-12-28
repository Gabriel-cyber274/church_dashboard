<?php

namespace App\Filament\Resources\Departments\RelationManagers;

use App\Models\Member;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;

class DepartmentRolesRelationManager extends RelationManager
{
    protected static string $relationship = 'departmentRoles';

    protected static ?string $title = 'Department Roles';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('role_name')
            ->columns([
                Tables\Columns\TextColumn::make('member.first_name')
                    ->label('First Name'),

                Tables\Columns\TextColumn::make('member.last_name')
                    ->label('Last Name'),

                Tables\Columns\TextColumn::make('member.email')
                    ->label('Email'),

                Tables\Columns\TextColumn::make('role_name')
                    ->label('Role'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                CreateAction::make()
                    ->form($this->getFormSchema()),
            ])
            ->actions([
                EditAction::make()->visible(fn() => auth()->user()?->hasAnyRole([
                    'super_admin',
                    'admin',
                    'hod',
                    'pastors'
                ]))
                    ->form($this->getFormSchema()),

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
            ->bulkActions([
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

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('member_id')
                ->label('Member')
                ->required()
                ->searchable()
                ->preload()
                ->options(
                    fn() => $this->getOwnerRecord()
                        ->members()
                        ->orderBy('first_name')
                        ->get()
                        ->mapWithKeys(fn(Member $member) => [
                            $member->id => "{$member->first_name} {$member->last_name} ({$member->email})",
                        ])
                ),

            Forms\Components\TextInput::make('role_name')
                ->label('Role Name')
                ->required()
                ->maxLength(255),

            Forms\Components\Textarea::make('role_description')
                ->label('Role Description')
                ->rows(3),
        ];
    }
}
