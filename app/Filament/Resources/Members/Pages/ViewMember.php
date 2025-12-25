<?php

namespace App\Filament\Resources\Members\Pages;

use App\Filament\Resources\Members\MemberResource;
use App\Mail\MemberNotification;
use App\Models\Member;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Mail;

class ViewMember extends ViewRecord
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
                    $member = $this->getRecord();

                    // Check if member has a valid email
                    if (!$member->email || empty(trim($member->email))) {
                        Notification::make()
                            ->warning()
                            ->title('No Email Address')
                            ->body('This member does not have a valid email address to send to.')
                            ->send();
                        return;
                    }

                    try {
                        Mail::to($member->email)
                            ->queue(new MemberNotification(
                                $data['subject'],
                                $data['message']
                            ));

                        Notification::make()
                            ->success()
                            ->title('Email Queued')
                            ->body("Email has been queued to be sent to {$member->full_name} ({$member->email}).")
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->danger()
                            ->title('Error Sending Email')
                            ->body('There was an error queueing the email. Please try again.')
                            ->send();
                    }
                })
                ->modalHeading('Send Email to Member')
                ->modalSubmitActionLabel('Send Email')
                ->modalWidth('lg')
                ->requiresConfirmation(),

            EditAction::make()->visible(fn() => auth()->user()?->doesntHaveAnyRole([
                'hod',
                'assistant_hod',
            ])),
        ];
    }
}
