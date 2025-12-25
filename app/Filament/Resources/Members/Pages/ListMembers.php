<?php

namespace App\Filament\Resources\Members\Pages;

use App\Filament\Resources\Members\MemberResource;
use App\Mail\MemberNotification;
use App\Models\Member;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
// use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\Facades\Mail;

class ListMembers extends ListRecords
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sendEmail')
                ->label('Send Email')
                ->icon('heroicon-o-envelope')
                ->color('success')
                ->visible(fn() => auth()->user()?->doesntHaveAnyRole(['finance']))
                ->form([
                    Checkbox::make('send_to_all')
                        ->label('Send to all members with email')
                        ->default(false)
                        ->live(),

                    Select::make('selected_members')
                        ->label('Select Members')
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->optionsLimit(50)
                        ->getSearchResultsUsing(
                            fn(string $search): array =>
                            Member::whereNotNull('email')
                                ->where('email', '!=', '')
                                ->where(function ($query) use ($search) {
                                    $query->where('first_name', 'like', "%{$search}%")
                                        ->orWhere('last_name', 'like', "%{$search}%")
                                        ->orWhere('email', 'like', "%{$search}%")
                                        ->orWhere('phone_number', 'like', "%{$search}%");
                                })
                                ->limit(50)
                                ->get()
                                ->mapWithKeys(fn($member) => [
                                    $member->id => "{$member->first_name} {$member->last_name} ({$member->email}, {$member->phone_number})"
                                ])
                                ->toArray()
                        )
                        ->getOptionLabelsUsing(
                            fn(array $values): array =>
                            Member::whereIn('id', $values)
                                ->get()
                                ->mapWithKeys(fn($member) => [
                                    $member->id => "{$member->first_name} {$member->last_name} ({$member->email}, {$member->phone_number})"
                                ])
                                ->toArray()
                        )
                        ->hidden(fn(Get $get) => $get('send_to_all')),

                    TextInput::make('subject')
                        ->label('Subject')
                        ->required()
                        ->maxLength(255),

                    Textarea::make('message')
                        ->label('Message')
                        ->required()
                        ->rows(10),
                ])
                ->action(function (array $data): void {
                    $members = collect();

                    // Method 1: Send to all members
                    if ($data['send_to_all']) {
                        $members = Member::whereNotNull('email')
                            ->where('email', '!=', '')
                            ->get();
                    }
                    // Method 2: Use multi-select field
                    elseif (!empty($data['selected_members'])) {
                        $members = Member::whereIn('id', $data['selected_members'])
                            ->whereNotNull('email')
                            ->where('email', '!=', '')
                            ->get();
                    }
                    // No method selected
                    else {
                        Notification::make()
                            ->warning()
                            ->title('No Recipients Selected')
                            ->body('Please select members using the dropdown or check "Send to all members with email".')
                            ->send();
                        return;
                    }

                    if ($members->isEmpty()) {
                        Notification::make()
                            ->warning()
                            ->title('No Recipients')
                            ->body('No members have valid email addresses to send to.')
                            ->send();
                        return;
                    }

                    $queuedCount = 0;
                    foreach ($members as $member) {
                        Mail::to($member->email)
                            ->queue(new MemberNotification(
                                $data['subject'],
                                $data['message']
                            ));
                        $queuedCount++;
                    }

                    Notification::make()
                        ->success()
                        ->title('Emails Queued')
                        ->body("Successfully queued emails for {$queuedCount} member(s).")
                        ->send();
                })
                ->modalHeading('Send Email to Members')
                ->modalSubmitActionLabel('Send Emails')
                ->modalWidth('lg')
                ->requiresConfirmation(),

            CreateAction::make()->visible(fn() => auth()->user()?->doesntHaveAnyRole([
                'finance'
            ])),
        ];
    }
}
