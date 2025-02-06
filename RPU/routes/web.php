<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;


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
Route::get('/', [AuthController::class, 'showAdminLogin'])->name('loginForm');
Route::post('admin/login', [AuthController::class, 'adminLogin'])->name('login');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('logout', [AuthController::class, 'adminLogout'])->name('logout');
        Route::get('profile', [AuthController::class, 'showAdminProfile'])->name('profile');
        Route::put('profile', [AuthController::class, 'updateAdminProfile'])->name('updateProfile');
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('dashmony', [DashboardController::class, 'index1'])->name('dashmony');

        Route::post('fee', [DashboardController::class, 'fee'])->name('fee');
        Route::post('date', [DashboardController::class, 'date'])->name('date');
        Route::post('empinf', [DashboardController::class, 'empinf'])->name('empinf');
        Route::get('dashemp', [DashboardController::class, 'index2'])->name('dashemp');



        Route::post('calculate', [DashboardController::class, 'calculate'])->name('calculate');
        

        Route::get('rabat', [DashboardController::class, 'rabat'])->name('rabat');

        Route::post('adduni', [DashboardController::class, 'adduni'])->name('adduni');
        Route::post('addspe', [DashboardController::class, 'addspe'])->name('addspe');
        Route::post('addinf', [DashboardController::class, 'addinf'])->name('addinf');

        Route::get('edituni/{university}', [DashboardController::class, 'edituni'])->name('edituni');
        Route::get('editspe/{specialization}', [DashboardController::class, 'editspe'])->name('editspe');
        Route::get('editinf/{unis}', [DashboardController::class, 'editinf'])->name('editinf');

        Route::put('updateuni/{university}', [DashboardController::class, 'updateuni'])->name('updateuni');
        Route::put('updatespe/{specialization}', [DashboardController::class, 'updatespe'])->name('updatespe');
        Route::put('updateinf/{unis}', [DashboardController::class, 'updateinf'])->name('updateinf');

        Route::delete('deleteuni/{university}', [DashboardController::class, 'deleteuni'])->name('deleteuni');
        Route::delete('deletespe/{specialization}', [DashboardController::class, 'deletespe'])->name('deletespe');
        Route::delete('deleteinf/{unis}', [DashboardController::class, 'deleteinf'])->name('deleteinf');

        Route::get('infouni/{university}', [DashboardController::class, 'infouni'])->name('infouni');



    });
}); 