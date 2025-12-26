<?php

namespace App\Filament\Resources\Reports\RelationManagers;

use App\Models\ReportSubmission;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class ReportSubmissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'submissions';

    // ✅ table() must be an instance method, not static
    public function table(Table $table): Table
    {
        $user = Auth::user();

        return $table
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(function (Builder $query) use ($user) {
                if ($user->hasAnyRole(['super_admin', 'admin'])) {
                    // Super admin / admin sees everything
                    return $query;
                }

                if ($user->is_department_leader && $user->department_id) {
                    // Department leader sees submissions from their department
                    $query->whereHas('user', function ($q) use ($user) {
                        $q->where('department_id', $user->department_id);
                    });
                } else {
                    $query->where('user_id', $user->id);
                }

                return $query;
            })

            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('user.name')
                    ->label('Submitted By')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('member.full_name')
                    ->label('Member')
                    ->formatStateUsing(fn($state, ReportSubmission $record) => $record->member_id ? $state : 'N/A / Department Submission')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('user.department.name')
                    ->label('Department')
                    ->formatStateUsing(fn($state, ReportSubmission $record) => $record->member_id ? 'N/A / Member Submission' : $state ?? 'N/A')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('user.is_department_leader')
                    ->label('Dept. Leader?')
                    ->formatStateUsing(fn($state, ReportSubmission $record) => $record->member_id ? 'N/A' : ($state ? 'Yes' : 'No'))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Submitted At')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('answers_count')
                    ->label('Number of Answers')
                    ->counts('answers'),
            ])
            ->headerActions([
                // CreateAction::make()->label('Add Submission'),
            ])
            ->actions([
                ViewAction::make('view')
                    ->label('View')
                    ->url(fn(ReportSubmission $record) => route('reports.submissions.show', [$record->report_id, $record->id])),
                DeleteAction::make()
                    ->label('Delete'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
