<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Program;
use Illuminate\Http\Request;

class PublicContributionController extends Controller
{
    /**
     * List all projects and programs
     */
    public function index()
    {
        $projects = Project::whereNull('deleted_at')->get();
        $programs = Program::whereNull('deleted_at')->get();

        return view('contributions.index', compact('projects', 'programs'));
    }

    /**
     * Show single project + banks
     */
    public function showProject(Project $project)
    {
        $project->load('banks');

        return view('contributions.project', compact('project'));
    }

    /**
     * Show single program + banks
     */
    public function showProgram(Program $program)
    {
        $program->load('banks');

        return view('contributions.program', compact('program'));
    }

    /**
     * Confirm payment sent
     */
    public function confirm(Request $request)
    {
        // You can later store this in DB (logs / confirmations)
        return back()->with('success', 'Thank you! Your contribution has been acknowledged.');
    }
}
