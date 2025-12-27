<?php

namespace App\Filament\Resources\Deposits\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use App\Models\Member;
use App\Models\Program;
use App\Models\Project;
use Filament\Forms\Components\Textarea;

class DepositForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Select::make('member_id')
                    ->label('Member')
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search) {
                        return Member::query()
                            ->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone_number', 'like', "%{$search}%")
                            ->limit(50)
                            ->get() // fetch the models, not just pluck
                            ->mapWithKeys(function ($member) {
                                return [$member->id => "{$member->first_name} {$member->last_name} ({$member->email}, {$member->phone_number})"];
                            })
                            ->toArray();
                    })
                    ->getOptionLabelUsing(function ($value): ?string {
                        $member = Member::find($value);
                        return $member ? "{$member->first_name} {$member->last_name} ({$member->email}, {$member->phone_number})" : null;
                    }),
                // ->required(),

                Select::make('program_id')
                    ->label('Program')
                    ->options(Program::all()->pluck('name', 'id'))
                    ->searchable(),

                Select::make('project_id')
                    ->label('Project')
                    ->options(Project::all()->pluck('name', 'id'))
                    ->searchable(),


                Select::make('status')
                    ->label('Status')
                    ->options(
                        fn() => auth()->user()?->hasRole('super_admin')
                            ? [
                                'pending' => 'Pending',
                                'completed' => 'Completed',
                            ]
                            : [
                                'pending' => 'Pending',
                            ]
                    )
                    ->default('pending')
                    ->required()
                    ->visible(fn() => auth()->user()?->hasAnyRole(['super_admin', 'finance'])),

                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                DatePicker::make('deposit_date')
                    ->required(),
                TextInput::make('reference')
                    ->default(null),
                Textarea::make('description')
                    ->default(null),
            ]);
    }
}
