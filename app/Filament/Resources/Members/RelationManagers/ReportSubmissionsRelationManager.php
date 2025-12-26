<?php

namespace App\Filament\Resources\Members\RelationManagers;

use App\Models\ReportSubmission;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ReportSubmissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'submissions'; // Member::submissions()

    public static function canViewForRecord(Model $record, string $pageClass): bool
    {
        return $record->submissions()->exists();
    }

    public function table(Table $table): Table
    {
        $user = Auth::user();

        return $table
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(function ($query) use ($user) {
                // If the user is not super_admin or admin, show only their submissions
                if (! $user->hasAnyRole(['super_admin', 'admin'])) {
                    $query->where('user_id', $user->id);
                }
                return $query;
            })
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('report.title')
                    ->label('Report')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Submitted By')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Submitted At')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('answers_count')
                    ->label('Number of Answers')
                    ->counts('answers'),
            ])
            ->headerActions([
                // Optional: CreateAction::make()->label('Add Submission'),
            ])
            ->actions([
                ViewAction::make('view')
                    ->label('View')
                    ->url(fn(ReportSubmission $record) => route('reports.submissions.show', [$record->report_id, $record->id])),
                DeleteAction::make()->label('Delete')->visible(fn() => $user->hasAnyRole(['super_admin', 'admin'])),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ])->visible(fn() => $user->hasAnyRole(['super_admin', 'admin'])),
            ]);
    }
}
