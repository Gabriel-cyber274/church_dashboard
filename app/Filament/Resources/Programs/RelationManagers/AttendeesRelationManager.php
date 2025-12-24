<?php

namespace App\Filament\Resources\Programs\RelationManagers;

use App\Filament\Resources\ProgrammeAttendees\ProgrammeAttendeeResource;
use App\Models\ProgrammeAttendee;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\BulkActionGroup;

class AttendeesRelationManager extends RelationManager
{
    protected static string $relationship = 'attendees';

    protected static ?string $relatedResource = null;

    protected static ?string $title = 'Programme Attendees';

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('programme_attendees.id', 'desc')
            ->columns([
                TextColumn::make('member.full_name')->label('Member'),
                TextColumn::make('attendance_time')->time(),
                TextColumn::make('name')->label('Guest Name'),
                TextColumn::make('phone_number')->label('Guest Phone'),
            ])
            ->headerActions([
                CreateAction::make('createAttendee')
                    ->modalHeading(fn() => 'Add Attendee for ' . $this->getOwnerRecord()->name)
                    ->form([
                        Select::make('program_id')
                            ->default(fn() => $this->getOwnerRecord()->id)
                            ->hidden(),

                        Select::make('member_id')
                            ->label('Member')
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search) {
                                return \App\Models\Member::query()
                                    ->where('first_name', 'like', "%{$search}%")
                                    ->orWhere('last_name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%")
                                    ->orWhere('phone_number', 'like', "%{$search}%")
                                    ->limit(50)
                                    ->get()
                                    ->mapWithKeys(function ($member) {
                                        return [$member->id => "{$member->first_name} {$member->last_name} ({$member->email}, {$member->phone_number})"];
                                    })
                                    ->toArray();
                            })
                            ->getOptionLabelUsing(function ($value): ?string {
                                $member = \App\Models\Member::find($value);
                                return $member ? "{$member->first_name} {$member->last_name} ({$member->email}, {$member->phone_number})" : null;
                            })
                            // Add validation to prevent duplicate entries
                            ->rules([
                                function () {
                                    return function (string $attribute, $value, \Closure $fail) {
                                        if ($value) {
                                            $programId = $this->getOwnerRecord()->id;

                                            // Check if this member is already registered for this program
                                            $exists = ProgrammeAttendee::where('program_id', $programId)
                                                ->where('member_id', $value)
                                                ->exists();

                                            if ($exists) {
                                                $member = \App\Models\Member::find($value);
                                                $memberName = $member ? $member->first_name . ' ' . $member->last_name : 'This member';
                                                $fail("{$memberName} is already registered for this program.");
                                            }
                                        }
                                    };
                                },
                            ]),

                        TimePicker::make('attendance_time')
                            ->required(),
                        TextInput::make('name')
                            ->label('Guest Name')
                            ->default(null),
                        TextInput::make('phone_number')
                            ->label('Guest Phone')
                            ->tel()
                            ->default(null),
                    ])
                    ->action(function ($data) {
                        // Check for duplicate before creating
                        if (isset($data['member_id']) && $data['member_id']) {
                            $exists = ProgrammeAttendee::where('program_id', $data['program_id'])
                                ->where('member_id', $data['member_id'])
                                ->exists();

                            if ($exists) {
                                throw new \Exception('This member is already registered for this program.');
                            }
                        }

                        $this->getOwnerRecord()->attendees()->create($data);
                    }),
            ])
            ->filters([
                SelectFilter::make('member_type')
                    ->label('Attendee Type')
                    ->options([
                        'members' => 'Members Only',
                        'guests' => 'Guests Only',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['value'])) {
                            return $query;
                        }

                        return match ($data['value']) {
                            'members' => $query->whereNotNull('member_id'),
                            'guests' => $query->whereNull('member_id'),
                            default => $query,
                        };
                    }),

                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->url(fn(ProgrammeAttendee $record): string => ProgrammeAttendeeResource::getUrl('view', ['record' => $record])),
                EditAction::make()
                    ->form([
                        Select::make('member_id')
                            ->label('Member')
                            ->disabled() // Disable editing of member_id to prevent duplicate constraint violation
                            ->dehydrated() // Still send the value when disabled
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search) {
                                return \App\Models\Member::query()
                                    ->where('first_name', 'like', "%{$search}%")
                                    ->orWhere('last_name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%")
                                    ->orWhere('phone_number', 'like', "%{$search}%")
                                    ->limit(50)
                                    ->get()
                                    ->mapWithKeys(function ($member) {
                                        return [$member->id => "{$member->first_name} {$member->last_name} ({$member->email}, {$member->phone_number})"];
                                    })
                                    ->toArray();
                            })
                            ->getOptionLabelUsing(function ($value): ?string {
                                $member = \App\Models\Member::find($value);
                                return $member ? "{$member->first_name} {$member->last_name} ({$member->email}, {$member->phone_number})" : null;
                            }),

                        TimePicker::make('attendance_time')
                            ->required(),
                        TextInput::make('name')
                            ->label('Guest Name')
                            ->default(null),
                        TextInput::make('phone_number')
                            ->label('Guest Phone')
                            ->tel()
                            ->default(null),
                    ])
                    ->action(function ($data, ProgrammeAttendee $record) {
                        try {
                            $record->update($data);
                        } catch (\Illuminate\Database\QueryException $e) {
                            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                                throw new \Exception('This member is already registered for this program. Please choose a different member or delete the existing registration first.');
                            }
                            throw $e;
                        }
                    }),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
