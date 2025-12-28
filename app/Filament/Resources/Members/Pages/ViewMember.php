<?php

namespace App\Filament\Resources\Members\Pages;

use App\Filament\Resources\Members\MemberResource;
use App\Filament\Resources\Members\Widgets\MemberDepositStats;
use App\Mail\MemberNotification;
use App\Models\Member;
use App\Models\Report;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Mail;

class ViewMember extends ViewRecord
{
    protected static string $resource = MemberResource::class;


    protected $queryString = ['activeRelationManager'];

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

            Action::make('createSubmission')
                ->label('Report Submission')
                ->icon('heroicon-o-plus')
                ->form([
                    Select::make('report_id')
                        ->label('Select Report')
                        ->searchable()
                        ->preload()
                        ->getSearchResultsUsing(function (string $search): array {
                            $member = $this->getRecord();

                            return Report::whereDoesntHave('departments') // Only reports without departments
                                ->where('title', 'like', "%{$search}%")
                                ->limit(50)
                                ->pluck('title', 'id')
                                ->toArray();
                        })
                        ->getOptionLabelUsing(function ($value): ?string {
                            return Report::find($value)?->title;
                        })
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $member = $this->getRecord();
                    $reportId = $data['report_id'];

                    $url = route('submissions.create', $reportId) . '?member_id=' . $member->id;

                    // Open in new tab
                    $this->js("window.open('{$url}', '_blank')");
                })
                ->modalHeading('Create a Submission for Member')
                ->modalSubmitActionLabel('Go')
                ->modalWidth('md')->visible(fn() => auth()->user()?->doesntHaveAnyRole([
                    'finance'
                ])),

            EditAction::make()->visible(fn() => auth()->user()?->doesntHaveAnyRole([
                'finance',
            ])),
        ];
    }


    protected function getFooterWidgets(): array
    {
        // Check if we're on the Pledges tab (index 2)
        if ($this->activeRelationManager === '3') {
            return [
                MemberDepositStats::class,
            ];
        }



        return [];
    }
}
