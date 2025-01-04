<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuestionsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::post('/logout', [QuestionsController::class, 'getAllQuestionsForAdmin'])->name('admin.questions.index');

    Route::get('/questions', [QuestionsController::class, 'getAllQuestionsForAdmin'])->name('admin.questions.index');
    Route::get('/questions/{id}/answer', [QuestionsController::class, 'answerQuestionForm'])->name('admin.questions.answerForm');
    Route::post('/questions/{id}/answer', [QuestionsController::class, 'answerQuestion'])->name('admin.questions.answer');
    Route::get('/questions/{id}/mark-as-frequent', [QuestionsController::class, 'markAsFrequent'])->name('admin.questions.markAsFrequent');
    Route::post('/admin/questions/{id}/toggle-frequent', [QuestionsController::class, 'toggleFrequentStatus'])->name('questions.toggleFrequent');


    Route::middleware(['auth', 'can:admin'])->prefix('admin')->name('admin.')->group(function () {
    
});

