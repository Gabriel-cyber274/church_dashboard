<?php

namespace App\Http\Controllers;

use App\Mail\PledgeNotification;
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
use App\Services\PaystackService;

class PublicContributionController extends Controller
{
    /**
     * List all projects and programs
     */

    public function initiatePaystack(Request $request, PaystackService $paystack)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'name' => 'required|string',
            'phone_number' => 'required|string|max:20',
            'amount' => 'required|numeric|min:100',
            'type' => 'required|in:program,project',
            'id' => 'required|integer',
        ]);

        $response = $paystack->initializeTransaction(
            $validated['email'],
            $validated['amount'] * 100, // convert to kobo
            route('paystack.callback'),
            [
                'type' => $validated['type'],
                'id' => $validated['id'],
                'phone_number' => $validated['phone_number'],
                'name' => $validated['name']
            ]
        );

        return redirect($response['data']['authorization_url']);
    }


    public function handlePaystackCallback(Request $request, PaystackService $paystack)
    {
        $reference = $request->query('reference');

        if (!$reference) {
            return redirect()->back()
                ->with('error', 'Payment reference missing');
        }

        $response = $paystack->verifyTransaction($reference);

        if ($response['data']['status'] !== 'success') {
            return redirect()->back()
                ->with('error', 'Payment failed');
        }

        $data = $response['data'];
        $metadata = $data['metadata'] ?? [];

        DB::beginTransaction();

        try {
            // Find or create member
            $member = Member::where('email', $data['customer']['email'])
                ->orWhere('phone_number', $metadata['phone_number'] ?? null)
                ->first();

            if (!$member) {
                $fullName = trim($metadata['name'] ?? '');

                $nameParts = preg_split('/\s+/', $fullName);

                $firstName = $nameParts[0] ?? '';
                $lastName  = count($nameParts) > 1
                    ? implode(' ', array_slice($nameParts, 1))
                    : '';

                $member = Member::create([
                    'email' => $data['customer']['email'],
                    'phone_number' => $metadata['phone_number'] ?? null,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                ]);
            }

            // Initialize program/project variables
            $program = $project = null;

            if (($metadata['type'] ?? null) === 'program') {
                $program = Program::find($metadata['id']);
            } elseif (($metadata['type'] ?? null) === 'project') {
                $project = Project::find($metadata['id']);
            }

            // Build description
            $typeLabel = ($metadata['type'] ?? 'unknown') === 'program' ? 'Program' : 'Project';
            $itemName = $program?->name ?? $project?->name ?? 'Unknown ' . $typeLabel;

            $description = sprintf(
                'Online Paystack Contribution: %s | Donor: %s (%s) | Amount: ₦%s | Reference: %s',
                $typeLabel . ': ' . $itemName,
                $data['customer']['email'],
                $metadata['phone_number'] ?? 'N/A',
                number_format($data['amount'] / 100, 2),
                $reference
            );

            // Prepare deposit data
            $depositData = [
                'member_id' => $member->id,
                'amount' => $data['amount'] / 100,
                'deposit_date' => now(),
                'status' => 'completed',
                'description' => $description,
                'reference' => $reference,
            ];

            // Assign program/project ID and redirect route
            $redirectRoute = route('contributions.index');
            $contributionItem = null;

            if ($program) {
                $depositData['program_id'] = $program->id;
                $redirectRoute = route('contributions.program.show', $program);
                $contributionItem = $program;
            } elseif ($project) {
                $depositData['project_id'] = $project->id;
                $redirectRoute = route('contributions.project.show', $project);
                $contributionItem = $project;
            }

            // Create deposit
            $deposit = Deposit::create($depositData);


            if ($deposit->member && $deposit->member->email) {
                Mail::to($deposit->member->email)->queue(new \App\Mail\DepositCompletedMail($deposit));
            }

            // Send email to admin
            $adminEmail = config('app.admin_email');
            if (!empty($adminEmail)) {
                $logoPath = public_path('images/logo.png');
                $logoUrl = asset('images/logo.png');

                Mail::to($adminEmail)->queue(new \App\Mail\DepositNotification([
                    'deposit' => $deposit,
                    'member' => $member,
                    'contributionItem' => $contributionItem,
                    'itemName' => $itemName,
                    'isPledge' => false,
                    'pledge' => null,
                    'description' => $description,
                    'phone_number' => $metadata['phone_number'] ?? 'N/A',
                    'typeLabel' => $typeLabel,
                    'logoPath' => $logoPath,
                    'logoUrl' => $logoUrl,
                ]));
            }

            DB::commit();

            return redirect($redirectRoute)
                ->with('success', 'Payment successful. Thank you!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Paystack callback error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Payment completed but saving failed.');
        }
    }




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


    public function storePledge(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone_number' => 'required|string|max:20',
            'amount' => 'required|numeric|min:100',
            'type' => 'required|in:program,project',
            'id' => 'required|integer',
            'note' => 'nullable|string',
            'fulfillment_date' => 'required|date|after_or_equal:today',
        ]);

        Log::info('Pledge form submitted', [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'amount' => $validated['amount'],
            'type' => $validated['type'],
            'id' => $validated['id'],
            'fulfillment_date' => $validated['fulfillment_date'],
        ]);

        DB::beginTransaction();

        try {
            // Find or create member
            $member = Member::where('email', $validated['email'])
                ->orWhere('phone_number', $validated['phone_number'])
                ->first();

            Log::info('Member lookup result', [
                'found' => $member ? 'yes' : 'no',
                'member_id' => $member->id ?? null
            ]);

            if (!$member) {

                $fullName = trim($validated['name'] ?? '');

                $nameParts = preg_split('/\s+/', $fullName);

                $firstName = $nameParts[0] ?? '';
                $lastName  = count($nameParts) > 1
                    ? implode(' ', array_slice($nameParts, 1))
                    : '';
                $member = Member::create([
                    'email' => $validated['email'],
                    'phone_number' => $validated['phone_number'],
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                ]);
                Log::info('New member created', ['member_id' => $member->id]);
            }

            // Prepare pledge data
            $pledgeData = [
                'member_id' => $member->id,
                'amount' => $validated['amount'],
                'pledge_date' => Carbon::parse($validated['fulfillment_date']),
                'status' => 'pending',
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'],
                'note' => $validated['note'] ?? null,
            ];

            $contributionItem = null;
            $redirectRoute = route('contributions.index');

            if ($validated['type'] === 'program') {
                $pledgeData['program_id'] = $validated['id'];
                $contributionItem = Program::find($validated['id']);
                $redirectRoute = route('contributions.program.show', $validated['id']);
                Log::info('Program pledge', ['program_id' => $validated['id']]);
            } else {
                $pledgeData['project_id'] = $validated['id'];
                $contributionItem = Project::find($validated['id']);
                $redirectRoute = route('contributions.project.show', $validated['id']);
                Log::info('Project pledge', ['project_id' => $validated['id']]);
            }

            $pledge = Pledge::create($pledgeData);

            Log::info('Pledge created successfully', [
                'pledge_id' => $pledge->id,
                'amount' => $pledge->amount,
                'fulfillment_date' => $pledge->pledge_date
            ]);

            DB::commit();

            $adminEmail = config('app.admin_email');

            if ($adminEmail) {
                Mail::to($adminEmail)->queue(new PledgeNotification([
                    'pledge' => $pledge,
                    'member' => $member,
                    'itemName' => $contributionItem?->name,
                    'logoPath' => public_path('images/logo.png'),
                    'typeLabel' => ucfirst($validated['type']),
                ]));
            }

            // ADD THIS RETURN STATEMENT
            return redirect($redirectRoute)
                ->with('success', 'Thank you! Your pledge has been recorded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Pledge creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $validated
            ]);

            return redirect()->back()
                ->with('error', 'Unable to save pledge. Please try again.')
                ->withInput();
        }
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
            'email' => 'required|email|max:255',
        ]);

        $isPledge = $validated['is_pledge'] ?? false;

        // Start database transaction
        DB::beginTransaction();

        try {

            $fullName = trim($validated['name'] ?? '');

            $nameParts = preg_split('/\s+/', $fullName);

            $firstName = $nameParts[0] ?? '';
            $lastName  = count($nameParts) > 1
                ? implode(' ', array_slice($nameParts, 1))
                : '';
            // Find or create member based on phone number
            $member = Member::where('email', $validated['email'])
                ->orWhere('phone_number', $validated['phone'])
                ->first();

            if (!$member) {
                $member = Member::create([
                    'email' => $validated['email'],
                    'phone_number' => $validated['phone'],
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                ]);
            }


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
                        'email' => $validated['email'],
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
            Mail::to($adminEmail)->queue(new \App\Mail\DepositNotification($mailData));

            Log::info('Deposit notification email sent to admin: ' . $adminEmail);
        } catch (\Exception $e) {
            Log::error('Failed to send deposit notification email: ' . $e->getMessage());
            // Don't throw the error to avoid breaking the main flow
        }
    }
}
