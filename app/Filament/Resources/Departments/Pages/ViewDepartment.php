<?php

namespace App\Filament\Resources\Departments\Pages;

use App\Filament\Resources\Departments\DepartmentResource;
use App\Mail\MemberNotification;
use App\Models\Department;
use App\Models\Member;
use App\Models\Report;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
// use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\Facades\Mail;

class ViewDepartment extends ViewRecord
{
    protected static string $resource = DepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sendEmail')
                ->label('Send Email to Department Members')
                ->icon('heroicon-o-envelope')
                ->color('success')
                ->visible(fn() => auth()->user()?->hasAnyRole([
                    'super_admin',
                    'admin',
                    'pastors'
                ]))
                ->form([
                    Checkbox::make('send_to_all')
                        ->label('Send to all department members with email')
                        ->default(true)
                        ->live(),

                    Select::make('selected_members')
                        ->label('Select Specific Members')
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->optionsLimit(50)
                        ->getSearchResultsUsing(
                            fn(string $search, Get $get): array =>
                            Department::find($this->getRecord()->id)
                                ->members()
                                ->whereNotNull('email')
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
                                ->whereHas('departments', function ($query) {
                                    $query->where('departments.id', $this->getRecord()->id);
                                })
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
                        ->maxLength(255)
                        ->placeholder('Department Announcement'),

                    Textarea::make('message')
                        ->label('Message')
                        ->required()
                        ->rows(10)
                        ->placeholder('Dear department members,...'),
                ])
                ->action(function (array $data): void {
                    $department = $this->getRecord();
                    $members = collect();

                    // Get department members with valid emails
                    $departmentMembersQuery = $department->members()
                        ->whereNotNull('email')
                        ->where('email', '!=', '');

                    // Method 1: Send to all department members
                    if ($data['send_to_all']) {
                        $members = $departmentMembersQuery->get();
                    }
                    // Method 2: Use selected members
                    elseif (!empty($data['selected_members'])) {
                        $members = $departmentMembersQuery
                            ->whereIn('members.id', $data['selected_members'])
                            ->get();
                    }
                    // No method selected (should not happen since send_to_all defaults to true)
                    else {
                        Notification::make()
                            ->warning()
                            ->title('No Recipients Selected')
                            ->body('Please select members to send the email to.')
                            ->send();
                        return;
                    }

                    if ($members->isEmpty()) {
                        Notification::make()
                            ->warning()
                            ->title('No Recipients')
                            ->body('No department members have valid email addresses to send to.')
                            ->send();
                        return;
                    }

                    $queuedCount = 0;
                    foreach ($members as $member) {
                        try {
                            Mail::to($member->email)
                                ->queue(new MemberNotification(
                                    $data['subject'],
                                    $data['message']
                                ));
                            $queuedCount++;
                        } catch (\Exception $e) {
                            // Continue with other emails if one fails
                            continue;
                        }
                    }

                    if ($queuedCount > 0) {
                        Notification::make()
                            ->success()
                            ->title('Emails Queued')
                            ->body("Successfully queued emails for {$queuedCount} department member(s).")
                            ->send();
                    } else {
                        Notification::make()
                            ->danger()
                            ->title('Failed to Send')
                            ->body('Could not queue any emails. Please check your email configuration.')
                            ->send();
                    }
                })
                ->modalHeading('Send Email to Department Members')
                ->modalDescription("Sending to members of: {$this->getRecord()->name}")
                ->modalSubmitActionLabel('Send Emails')
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
                            return $this->getRecord()
                                ->reports()
                                ->where('title', 'like', "%{$search}%")
                                ->limit(50)
                                ->pluck('title', 'reports.id')
                                ->toArray();
                        })
                        ->getOptionLabelUsing(function ($value): ?string {
                            return $this->getRecord()
                                ->reports()
                                ->where('reports.id', $value)
                                ->value('title');
                        })
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $reportId = $data['report_id'];
                    $report = Report::findOrFail($reportId);

                    $url = route('submissions.create', $report->id);
                    $this->js("window.open('{$url}', '_blank')");
                })
                ->modalHeading('Create a Submission')
                ->modalSubmitActionLabel('Go')
                ->modalWidth('md')->visible(fn() => auth()->user()->is_department_leader && auth()->user()->department_id == $this->getRecord()->id),

            
                EditAction::make()->visible(fn() => auth()->user()?->hasAnyRole([
                'super_admin',
                'admin',
                'pastors'
            ])),

        ];
    }
}
