<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\QuestionsController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/register', [AuthController::class, 'createAccount']);
Route::post('login', [AuthController::class, 'login']);
Route::post('employeeLogin', [AuthController::class, 'employeeLogin']);

Route::middleware(['auth:sanctum', 'ability:user-abilities'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user-profile', [AuthController::class, 'userProfile']);
    Route::post('user-profile', [AuthController::class, 'updateAccount']);

    Route::get('search', [UserController::class, 'search1']);
    Route::post('search', [UserController::class, 'search']);
    Route::get('universities', [UserController::class, 'getUniversities']);
    Route::get('universities/{universityId}/specializations', [UserController::class, 'getSpecializationsForUniversity']);
    Route::post('requests', [UserController::class, 'submitRequest']);
    Route::get('requests', [UserController::class, 'getUserRequests']);

    Route::get('pay', [PaymentController::class, 'createPayment']);

    Route::get('/frequent-questions', [QuestionsController::class, 'getFrequentQuestions']);
    Route::post('/submit-question', [QuestionsController::class, 'submitQuestion']);
    Route::get('/user-questions', [QuestionsController::class, 'getUserQuestions']);
});
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');


Route::middleware(['auth:sanctum', 'ability:employee-abilities'])->group(function () {
    
    Route::post('employeeLogout', [AuthController::class, 'employeeLogout']);

    Route::get('/questions', [QuestionsController::class, 'getAllQuestions']);
    Route::get('/questions/{id}', [QuestionsController::class, 'getQuestionDetails']);
    Route::post('/questions/{id}/mark-frequent', [QuestionsController::class, 'markAsFrequent']);
    Route::post('/questions/{id}/remove-frequent', [QuestionsController::class, 'removeFromFrequent']);
    Route::post('/questions/{id}/answer', [QuestionsController::class, 'answerQuestion']);
});