<?php

namespace App\Filament\Resources\Reports\Schemas;

use App\Models\Member;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;


class ReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextInput::make('title')
                //     ->required(),
                // Textarea::make('description')
                //     ->required()
                //     ->columnSpanFull(),

                TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                Textarea::make('description')
                    ->rows(3),

                // 🔥 Assignment Type Selector
                Select::make('assign_to')
                    ->label('Assign Report To')
                    ->options([
                        'departments' => 'Departments',
                        'members' => 'Members',
                    ])
                    ->required()
                    ->reactive(),

                // 🔹 Departments selector
                Select::make('departments')
                    ->relationship('departments', 'name')
                    ->multiple()
                    ->preload()
                    ->visible(fn($get) => $get('assign_to') === 'departments'),

                // 🔹 Members selector
                Select::make('members')
                    ->label('Members')
                    ->relationship('members', 'first_name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->visible(fn($get) => $get('assign_to') === 'members')

                    ->getSearchResultsUsing(function (string $search) {
                        return Member::query()
                            ->where(function ($query) use ($search) {
                                $query
                                    ->where('first_name', 'like', "%{$search}%")
                                    ->orWhere('last_name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%")
                                    ->orWhere('phone_number', 'like', "%{$search}%");
                            })
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(fn($member) => [
                                $member->id => "{$member->first_name} {$member->last_name} ({$member->email}, {$member->phone_number})",
                            ])
                            ->toArray();
                    })

                    ->getOptionLabelUsing(function ($value): ?string {
                        $member = Member::find($value);

                        return $member
                            ? "{$member->first_name} {$member->last_name} ({$member->email}, {$member->phone_number})"
                            : null;
                    }),

                // 🧩 QUESTIONS
                Repeater::make('questions')
                    ->relationship()
                    ->schema([
                        TextInput::make('question')
                            ->required(),

                        Select::make('type')
                            ->options([
                                'text' => 'Text',
                                'textarea' => 'Textarea',
                                'radio' => 'Radio',
                                'checkbox' => 'Checkbox',
                                'select' => 'Select',
                            ])
                            ->required()
                            ->reactive(),

                        Textarea::make('options')
                            ->helperText('Comma separated values')
                            ->visible(
                                fn($get) =>
                                in_array($get('type'), ['radio', 'checkbox', 'select'])
                            ),

                        Toggle::make('is_required')
                            ->default(false),
                    ])
                    ->collapsible()
                    ->defaultItems(1)
                    ->columnSpanFull(),
            ]);
    }
}
