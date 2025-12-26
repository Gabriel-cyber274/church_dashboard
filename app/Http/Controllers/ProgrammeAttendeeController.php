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
    public function store(Request $request, $programId)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            // 'attendance_time' => 'required|date_format:Y-m-d H:i:s',
            'attendance_time' => 'required|date',
        ]);

        $member = Member::where('phone_number', $validated['phone_number'])->first();

        ProgrammeAttendee::create([
            'program_id'       => $programId,
            'member_id'        => $member?->id, // null if not found
            'name'            => $member ? null : $validated['name'],
            'phone_number'    => $member ? null : $validated['phone_number'],
            'attendance_time' => Carbon::parse($validated['attendance_time'])
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
