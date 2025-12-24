<?php

use App\Http\Controllers\PublicContributionController;
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
