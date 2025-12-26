<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Display a listing of reports.
     */
    public function index()
    {
        $reports = Report::with(['departments', 'members', 'questions'])
            ->latest()
            ->paginate(10);

        return view('reports.index', compact('reports'));
    }

    /**
     * Display the specified report.
     */
    public function show(Report $report)
    {
        $report->load(['questions', 'departments', 'members']);
        return view('reports.show', compact('report'));
    }

    /**
     * Show submissions for a specific report.
     */
    public function submissions(Report $report)
    {
        if (!Auth::user()->hasAnyRole(['super_admin', 'admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $submissions = $report->submissions()
            ->with(['user', 'answers.question'])
            ->latest()
            ->paginate(10);

        return view('reports.submissions', compact('report', 'submissions'));
    }

    /**
     * Show individual submission details.
     */
    public function showSubmission(Report $report, ReportSubmission $submission)
    {
        $submission->load(['user', 'answers.question']);

        return view('reports.submission-show', compact('report', 'submission'));
    }
}
