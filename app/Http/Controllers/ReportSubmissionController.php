<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportSubmissionController extends Controller
{
    /**
     * Show the form for creating a new submission.
     */
    public function create(Report $report)
    {
        $report->load(['questions' => function ($query) {
            $query->orderBy('id', 'asc');
        }]);

        return view('submissions.create', compact('report'));
    }

    /**
     * Store a newly created submission.
     */
    public function store(Request $request, Report $report)
    {
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'nullable|string',
        ]);

        // Check required questions
        $requiredQuestions = $report->questions()->where('is_required', true)->get();

        foreach ($requiredQuestions as $question) {
            if (!isset($request->answers[$question->id]) || empty($request->answers[$question->id])) {
                return back()->withErrors(["answers.{$question->id}" => 'This question is required.']);
            }
        }

        // Create submission
        $submission = ReportSubmission::create([
            'report_id' => $report->id,
            'user_id' => Auth::id(),
        ]);

        // Save answers
        foreach ($request->answers as $questionId => $answerValue) {
            if (!empty($answerValue)) {
                $submission->answers()->create([
                    'question_id' => $questionId,
                    'answer' => is_array($answerValue) ? json_encode($answerValue) : $answerValue,
                ]);
            }
        }

        return redirect()->route('reports.submissions.show', [$report, $submission])
            ->with('success', 'Report submitted successfully!');
    }

    /**
     * Show the form for editing an existing submission.
     */
    public function edit(Report $report, ReportSubmission $submission)
    {
        if (!Auth::user()->hasAnyRole(['super_admin', 'admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $report->load(['questions' => function ($query) {
            $query->orderBy('id', 'asc');
        }]);

        // Load existing answers
        $existingAnswers = $submission->answers()->pluck('answer', 'question_id')->toArray();

        return view('submissions.edit', compact('report', 'submission', 'existingAnswers'));
    }

    /**
     * Update the specified submission.
     */
    public function update(Request $request, Report $report, ReportSubmission $submission)
    {
        if (!Auth::user()->hasAnyRole(['super_admin', 'admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'nullable|string',
        ]);

        // Check required questions
        $requiredQuestions = $report->questions()->where('is_required', true)->get();

        foreach ($requiredQuestions as $question) {
            if (!isset($request->answers[$question->id]) || empty($request->answers[$question->id])) {
                return back()->withErrors(["answers.{$question->id}" => 'This question is required.']);
            }
        }

        // Delete existing answers
        $submission->answers()->delete();

        // Save new answers
        foreach ($request->answers as $questionId => $answerValue) {
            if (!empty($answerValue)) {
                $submission->answers()->create([
                    'question_id' => $questionId,
                    'answer' => is_array($answerValue) ? json_encode($answerValue) : $answerValue,
                ]);
            }
        }

        return redirect()->route('reports.submissions.show', [$report, $submission])
            ->with('success', 'Submission updated successfully!');
    }

    /**
     * Remove the specified submission.
     */
    public function destroy(Report $report, ReportSubmission $submission)
    {
        if (!Auth::user()->hasAnyRole(['super_admin', 'admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $submission->answers()->delete();
        $submission->delete();

        return redirect()->route('reports.submissions', $report)
            ->with('success', 'Submission deleted successfully!');
    }
}
