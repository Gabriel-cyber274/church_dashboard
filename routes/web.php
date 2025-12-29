<?php

use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProgrammeAttendeeController;
use App\Http\Controllers\PublicContributionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportSubmissionController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return redirect('/admin');
});



Route::get('/contributions', [PublicContributionController::class, 'index'])
    ->name('contributions.index');

Route::get('/contributions/project/{project}', [PublicContributionController::class, 'showProject'])
    ->name('contributions.project.show');

Route::get('/contributions/program/{program}', [PublicContributionController::class, 'showProgram'])
    ->name('contributions.program.show');

// Route::post('/contributions/confirm', [PublicContributionController::class, 'confirm'])
//     ->name('contributions.confirm');


Route::get('/contributions/confirm', [PublicContributionController::class, 'showConfirmForm'])
    ->name('contributions.confirm.form');

// Process confirmation
Route::post('/contributions/confirm', [PublicContributionController::class, 'confirm'])
    ->name('contributions.confirm');


// Paystack routes
Route::post('/paystack/initiate', [PublicContributionController::class, 'initiatePaystack'])->name('contributions.paystack.initiate');
Route::get('/paystack/callback', [PublicContributionController::class, 'handlePaystackCallback'])->name('paystack.callback');

// Pledge routes
Route::post('/pledges', [PublicContributionController::class, 'storePledge'])->name('pledges.store');




Route::get('/members/create', [MemberController::class, 'create'])->name('members.create');
Route::post('/members', [MemberController::class, 'store'])->name('members.store');


Route::prefix('programs/{programId}/attendees')->name('programme-attendees.')->group(function () {
    Route::get('/', [ProgrammeAttendeeController::class, 'index'])->name('index');
    Route::get('/create', [ProgrammeAttendeeController::class, 'create'])->name('create');
    Route::post('/store', [ProgrammeAttendeeController::class, 'store'])->name('store');
});



Route::middleware(['auth'])->group(function () {
    // Report viewing routes
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
    Route::get('/reports/{report}/submissions', [ReportController::class, 'submissions'])->name('reports.submissions');
    Route::get('/reports/{report}/submissions/{submission}', [ReportController::class, 'showSubmission'])->name('reports.submissions.show');

    // Submission/Answer routes
    Route::get('/reports/{report}/submit', [ReportSubmissionController::class, 'create'])->name('submissions.create');
    Route::post('/reports/{report}/submit', [ReportSubmissionController::class, 'store'])->name('submissions.store');

    // Edit submission (if allowed)
    Route::get('/reports/{report}/submissions/{submission}/edit', [ReportSubmissionController::class, 'edit'])->name('submissions.edit');
    Route::put('/reports/{report}/submissions/{submission}', [ReportSubmissionController::class, 'update'])->name('submissions.update');
    Route::delete('/reports/{report}/submissions/{submission}', [ReportSubmissionController::class, 'destroy'])->name('submissions.destroy');
});



Route::get('/test-email', function () {
    try {
        Mail::raw('Test email from Laravel', function ($message) {
            $message->to('gabrielimoh30@gmail.com')
                ->subject('Test Email');
        });
        return 'Email sent successfully!';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});
