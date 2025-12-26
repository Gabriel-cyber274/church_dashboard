<?php

namespace App\Filament\Resources\ProgrammeAttendees\RelationManagers;

use App\Filament\Resources\Members\MemberResource;
use App\Models\Member;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;

class MembersRelationManager extends RelationManager
{
    protected static string $relationship = 'member';

    protected static ?string $relatedResource = MemberResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('members.id', 'desc')
            ->recordTitleAttribute('first_name')
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->label('First Name'),
                Tables\Columns\TextColumn::make('last_name')
                    ->label('Last Name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('Phone'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([])
            ->actions([
                ViewAction::make()
                    ->url(fn(Member $record): string => MemberResource::getUrl('view', ['record' => $record])),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }
}
