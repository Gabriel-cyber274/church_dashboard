<?php

namespace App\Http\Controllers;

use App\Models\QuestionaireProgramme;
use App\Models\QuestionaireQuestion;
use Illuminate\Http\Request;

class QuestionaireController extends Controller
{
    public function index()
    {
        $programmes = QuestionaireProgramme::withCount('questions')
            ->latest()
            ->get();

        return view('questionaires.index', compact('programmes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $programme = QuestionaireProgramme::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'code' => QuestionaireProgramme::generateUniqueCode(),
        ]);

        return redirect()
            ->route('questionaires.show', $programme)
            ->with('success', 'Questionnaire created. Share code ' . $programme->code . ' so people can ask questions.');
    }

    public function show(QuestionaireProgramme $questionaire)
    {
        $questions = $questionaire->questions()->oldest()->get();

        return view('questionaires.show', [
            'programme' => $questionaire,
            'questions' => $questions,
        ]);
    }

    public function update(Request $request, QuestionaireProgramme $questionaire)
    {
        $validated = $request->validateWithBag('updateProgramme', [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $questionaire->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('questionaires.index')
            ->with('success', 'Questionnaire updated successfully.');
    }

    public function destroy(QuestionaireProgramme $questionaire)
    {
        $questionaire->delete();

        return redirect()
            ->route('questionaires.index')
            ->with('success', 'Questionnaire and its questions were deleted.');
    }

    public function ask()
    {
        return view('questionaires.ask', ['programme' => null]);
    }

    public function lookup(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20',
        ]);

        $code = strtoupper(preg_replace('/\s+/', '', $validated['code']));

        $programme = QuestionaireProgramme::where('code', $code)->first();

        if (!$programme) {
            return redirect()
                ->route('questionaires.ask')
                ->withErrors(['code' => 'No questionnaire found with that code.'])
                ->withInput();
        }

        return redirect()->route('questionaires.ask.show', $programme->code);
    }

    public function askShow(string $code)
    {
        $programme = QuestionaireProgramme::where('code', strtoupper($code))->firstOrFail();

        return view('questionaires.ask', compact('programme'));
    }

    public function storeQuestion(Request $request, string $code)
    {
        $programme = QuestionaireProgramme::where('code', strtoupper($code))->firstOrFail();

        $validated = $request->validate([
            'question' => 'required|string|max:2000',
        ]);

        QuestionaireQuestion::create([
            'questionaire_id' => $programme->id,
            'question' => $validated['question'],
        ]);

        return redirect()
            ->route('questionaires.ask.show', $programme->code)
            ->with('success', 'Your question has been submitted.');
    }
}
