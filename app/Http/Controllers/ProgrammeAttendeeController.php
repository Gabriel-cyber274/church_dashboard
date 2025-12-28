<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\ProgrammeAttendee;
use App\Models\Program;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProgrammeAttendeeController extends Controller
{
    /**
     * Show the form for creating a new ProgrammeAttendee for a specific program.
     *
     * @param  int  $programId
     * @return \Illuminate\Http\Response
     */
    public function create($programId)
    {
        // Find the program or return 404
        $program = Program::findOrFail($programId);

        return view('programme-attendees.create', compact('program'));
    }

    /**
     * Store a newly created ProgrammeAttendee in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $programId
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request, $programId)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'nullable|string',
    //         'phone_number' => 'nullable|string|max:20',
    //         'attendance_time' => 'required|date',
    //     ]);

    //     $member = Member::where('email', $validated['email'])
    //         ->orWhere('phone_number', $validated['phone_number'] ?? null)
    //         ->first();


    //     ProgrammeAttendee::create([
    //         'program_id'       => $programId,
    //         'member_id'        => $member?->id, // null if not found
    //         'name'            => $validated['name'],
    //         'phone_number'    => $validated['phone_number'],
    //         'email'    => $validated['email'],
    //         'attendance_time' => Carbon::parse($validated['attendance_time'])
    //             ->format('Y-m-d H:i:s'),
    //     ]);

    //     return redirect()
    //         ->route('programme-attendees.create', $programId)
    //         ->with('success', 'Attendee registered successfully!');
    // }

    public function store(Request $request, $programId)
    {
        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone_number' => 'nullable|string|max:20',
            'attendance_time' => 'required|date',
        ]);

        // Try to find an existing member
        $member = null;
        if (!empty($validated['email']) || !empty($validated['phone_number'])) {
            $member = Member::where(function ($query) use ($validated) {
                if (!empty($validated['email'])) {
                    $query->where('email', $validated['email']);
                }
                if (!empty($validated['phone_number'])) {
                    $query->orWhere('phone_number', $validated['phone_number']);
                }
            })->first();
        }

        // Check if this attendee is already registered
        $existing = ProgrammeAttendee::where('program_id', $programId)
            ->where(function ($q) use ($member, $validated) {
                if ($member) {
                    $q->where('member_id', $member->id);
                } else {
                    $q->where('name', $validated['name'])
                        ->where('email', $validated['email'] ?? null);
                }
            })
            ->first();

        if ($existing) {
            return redirect()
                ->route('programme-attendees.create', $programId)
                ->with('error', 'This attendee is already registered for this program.');
        }

        // Create the attendee
        ProgrammeAttendee::create([
            'program_id'       => $programId,
            'member_id'        => $member?->id, // null if no member found
            'name'             => $validated['name'],
            'phone_number'     => $validated['phone_number'] ?? null,
            'email'            => $validated['email'] ?? null,
            'attendance_time'  => Carbon::parse($validated['attendance_time'])
                ->format('Y-m-d H:i:s'),
        ]);

        return redirect()
            ->route('programme-attendees.create', $programId)
            ->with('success', 'Attendee registered successfully!');
    }

    /**
     * Display a listing of ProgrammeAttendees for a specific program.
     *
     * @param  int  $programId
     * @return \Illuminate\Http\Response
     */
    public function index($programId)
    {
        $program = Program::findOrFail($programId);
        $attendees = ProgrammeAttendee::where('program_id', $programId)
            ->with('member')
            ->latest()
            ->get();

        return view('programme-attendees.index', compact('program', 'attendees'));
    }
}
