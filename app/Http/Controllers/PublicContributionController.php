<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\Member;
use App\Models\Pledge;
use App\Models\Project;
use App\Models\Program;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PublicContributionController extends Controller
{
    /**
     * List all projects and programs
     */
    public function index()
    {
        // Filter projects by status = 'pending'
        $projects = Project::whereNull('deleted_at')
            ->where('status', 'pending')
            ->orderBy('id', 'desc')
            ->get();

        $today = Carbon::today();

        $programs = Program::whereNull('deleted_at')
            ->where('is_budgeted', true)
            ->where(function ($query) use ($today) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', $today);
            })
            ->orderBy('id', 'desc')
            ->get();

        return view('contributions.index', compact('projects', 'programs'));
    }

    /**
     * Show single project + banks
     */
    public function showProject(Project $project)
    {
        // Additional check to ensure only pending projects are accessible
        if ($project->status !== 'pending' || $project->deleted_at !== null) {
            abort(404, 'Project not found or not available for contributions');
        }

        $project->load('banks');

        return view('contributions.project', compact('project'));
    }

    /**
     * Show single program + banks
     */
    public function showProgram(Program $program)
    {
        $today = Carbon::today();

        // Additional checks for program accessibility
        if (
            $program->deleted_at !== null ||
            !$program->is_budgeted ||
            ($program->end_date && $program->end_date < $today)
        ) {
            abort(404, 'Program not found or not available for contributions');
        }

        $program->load('banks');

        return view('contributions.program', compact('program'));
    }

    /**
     * Confirm payment sent
     */
    public function showConfirmForm(Request $request)
    {
        $type = $request->input('type'); // 'project' or 'program'
        $id = $request->input('id');

        return view('contributions.confirm-form', compact('type', 'id'));
    }

    /**
     * Process confirmation with name and phone
     */
    public function confirm(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:project,program',
            'id' => 'required|integer',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'amount' => 'required|numeric|min:1',
            'is_pledge' => 'nullable|boolean',
        ]);

        $isPledge = $validated['is_pledge'] ?? false;

        // Start database transaction
        DB::beginTransaction();

        try {
            // Find or create member based on phone number
            $member = Member::firstOrCreate(
                ['phone_number' => $validated['phone']],
                [
                    'first_name' => $validated['name'], // Using name as first name
                    'last_name' => '', // You might want to split name into first/last
                ]
            );

            // Load the project/program for description
            $contributionItem = null;
            if ($validated['type'] === 'project') {
                $contributionItem = Project::find($validated['id']);
            } else {
                $contributionItem = Program::find($validated['id']);
            }

            // If this is a pledge payment, find the specific pledge
            $pledge = null;
            if ($isPledge) {
                // Find pledge for this specific member and project/program
                if ($validated['type'] === 'project') {
                    $pledge = Pledge::where('member_id', $member->id)
                        ->where('project_id', $validated['id'])
                        ->where('status', 'pending')
                        ->first();
                } else {
                    $pledge = Pledge::where('member_id', $member->id)
                        ->where('program_id', $validated['id'])
                        ->where('status', 'pending')
                        ->first();
                }

                // If no pledge found but user said it's a pledge, create one
                if (!$pledge) {
                    $pledgeData = [
                        'member_id' => $member->id,
                        'amount' => $validated['amount'],
                        'pledge_date' => Carbon::now(),
                        'status' => 'pending',
                        'name' => $validated['name'],
                        'phone_number' => $validated['phone'],
                    ];

                    if ($validated['type'] === 'project') {
                        $pledgeData['project_id'] = $validated['id'];
                    } else {
                        $pledgeData['program_id'] = $validated['id'];
                    }

                    $pledge = Pledge::create($pledgeData);
                }
            }

            // Build detailed description for deposit
            $description = $this->buildDepositDescription([
                'type' => $validated['type'],
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'amount' => $validated['amount'],
                'isPledge' => $isPledge,
                'item' => $contributionItem,
                'pledgeId' => $pledge?->id,
                'date' => Carbon::now(),
            ]);

            // Create deposit record
            $depositData = [
                'member_id' => $member->id,
                'amount' => $validated['amount'],
                'deposit_date' => Carbon::now(),
                'description' => $description,
            ];

            // Add pledge ID if this is a pledge payment
            if ($isPledge && $pledge) {
                $depositData['pledge_id'] = $pledge->id;
            }

            // Add project or program ID
            if ($validated['type'] === 'project') {
                $depositData['project_id'] = $validated['id'];
            } else {
                $depositData['program_id'] = $validated['id'];
            }

            $deposit = Deposit::create($depositData);

            DB::commit();

            // Send email notification to admin
            $this->sendDepositNotification($deposit, $member, $contributionItem, $validated, $pledge);

            // Success message
            $message = 'Thank you, ' . $validated['name'] . '! Your contribution of ₦' .
                number_format($validated['amount'], 2) . ' has been recorded.';

            if ($isPledge) {
                $message .= ' Your pledge has been marked as paid.';
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();

            // Log the error for debugging
            Log::error('Contribution confirmation failed: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Something went wrong. Please try again.')
                ->withInput();
        }
    }

    /**
     * Build detailed deposit description
     */
    private function buildDepositDescription(array $data): string
    {
        $typeLabel = $data['type'] === 'project' ? 'Project' : 'Program';
        $itemName = $data['item'] ? $data['item']->name : 'Unknown ' . $typeLabel;

        $description = $typeLabel . " Contribution: " . $itemName . " | ";
        $description .= "Donor: " . $data['name'] . " (" . $data['phone'] . ") | ";
        $description .= "Amount: ₦" . number_format($data['amount'], 2) . " | ";

        if ($data['isPledge']) {
            $description .= "Type: Pledge Payment" . ($data['pledgeId'] ? " (Pledge #" . $data['pledgeId'] . ")" : "") . " | ";
        } else {
            $description .= "Type: Direct Contribution | ";
        }

        $description .= "Date: " . $data['date']->format('Y-m-d H:i:s') . " | ";
        $description .= "Method: Online Portal ";

        return $description;
    }


    private function sendDepositNotification(Deposit $deposit, Member $member, $contributionItem, array $data, ?Pledge $pledge = null)
    {
        try {
            $adminEmail = config('app.admin_email');

            if (empty($adminEmail)) {
                Log::warning('Admin email not configured. Skipping email notification.');
                return;
            }

            $typeLabel = $data['type'] === 'project' ? 'Project' : 'Program';
            $itemName = $contributionItem ? $contributionItem->name : 'Unknown ' . $typeLabel;

            $mailData = [
                'deposit' => $deposit,
                'pledge' => $pledge,
                'member' => $member,
                'contributionItem' => $contributionItem,
                'typeLabel' => $typeLabel,
                'itemName' => $itemName,
                'isPledge' => $data['is_pledge'] ?? false,
                'logoPath' => public_path('images/logo.png'),
                'logoUrl' => asset('images/logo.png'),
            ];

            // Send email
            Mail::to($adminEmail)->send(new \App\Mail\DepositNotification($mailData));

            Log::info('Deposit notification email sent to admin: ' . $adminEmail);
        } catch (\Exception $e) {
            Log::error('Failed to send deposit notification email: ' . $e->getMessage());
            // Don't throw the error to avoid breaking the main flow
        }
    }
}
