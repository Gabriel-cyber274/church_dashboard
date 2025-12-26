<?php

namespace App\Filament\Resources\Departments\RelationManagers;

use App\Filament\Resources\Reports\ReportResource;
use App\Models\Report;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class ReportsRelationManager extends RelationManager
{
    protected static string $relationship = 'reports';

    protected static ?string $relatedResource = ReportResource::class;

    public function table(Table $table): Table
    {
        $user = Auth::user();

        return $table
            ->defaultSort('reports.id', 'desc')
            ->modifyQueryUsing(function (Builder $query) use ($user) {
                if ($user?->hasAnyRole(['super_admin', 'admin'])) {
                    return $query;
                }
                // Only show reports that the logged-in user has submitted
                $query->whereHas('submissions', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });

                return $query;
            })
            ->actions([
                ViewAction::make()->url(fn(Report $record): string => ReportResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
