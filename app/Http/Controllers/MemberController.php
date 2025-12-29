<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    /**
     * Show the form for creating a new member.
     */
    public function create()
    {
        $departments = Department::all();
        return view('members.create', compact('departments'));
    }

    /**
     * Store a newly created member in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'nullable|email|unique:members,email',
            'date_of_birth' => 'required|date|before:today',
            'address' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20|unique:members,phone_number',
            'gender' => ['required', Rule::in(['male', 'female'])],
            'marital_status' => ['required', Rule::in(['single', 'married', 'divorced', 'widowed'])],
            'departments' => 'nullable|array',
            'departments.*' => 'exists:departments,id'
        ]);

        // Create the member
        $member = Member::create($validated);

        // Attach departments if provided
        if ($request->has('departments') && !empty($request->departments)) {
            $member->departments()->attach($request->departments);
        }

        // Redirect back with success message
        return back()->with('success', 'Member created successfully!');
    }
}
