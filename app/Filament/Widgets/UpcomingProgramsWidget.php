<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Programs\ProgramResource;
use App\Models\Program;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class UpcomingProgramsWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 1;

    protected static ?string $heading = 'Upcoming Programmes';


    public function table(Table $table): Table
    {
        return $table
            ->query(
                Program::query()
                    ->whereDate('end_date', '>=', now())
                    ->orderBy('start_date')
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Program Name')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold')
                    ->description(fn(Program $record): string => $record->description ? substr($record->description, 0, 50) . '...' : 'No description'),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Start Date')
                    ->date('M d, Y')
                    ->sortable()
                    ->badge()
                    ->color(
                        fn(Program $record): string =>
                        $this->getDateColor($record->start_date)
                    ),

                Tables\Columns\TextColumn::make('end_date')
                    ->label('End Date')
                    ->date('M d, Y')
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('location')
                    ->label('Location')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-map-pin')
                    ->iconColor('primary'),

                Tables\Columns\IconColumn::make('is_budgeted')
                    ->label('Budgeted')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->trueColor('success')
                    ->falseIcon('heroicon-o-x-circle')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('budget')
                    ->label('Budget')
                    ->money('NGN', true)
                    ->sortable()
                    ->color(
                        fn(Program $record): string =>
                        $record->is_budgeted ? 'success' : 'gray'
                    ),

                Tables\Columns\TextColumn::make('coordinators.name')
                    ->label('Coordinators')
                    ->badge()
                    ->separator(',')
                    ->color('info')
                    ->limitList(2),
            ])
            ->actions([
                ViewAction::make()
                    ->url(fn(Program $record): string => ProgramResource::getUrl('view', ['record' => $record])),
            ])
            ->emptyStateHeading('No upcoming programmes')
            ->emptyStateDescription('Create a new program to get started.')
            ->emptyStateIcon('heroicon-o-calendar');
    }

    /**
     * Get badge color based on date
     */
    private function getDateColor($date): string
    {
        // Ensure we're working with a Carbon instance
        if (!$date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        $now = Carbon::now();

        if ($date->isToday()) {
            return 'warning';
        } elseif ($date->isPast()) {
            return 'gray';
        } else {
            return 'success';
        }
    }

    public static function canView(): bool
    {
        return true;
    }
}
