<?php

use App\Http\Controllers\AppSettingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnterpriseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TranslationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function(){
    return redirect()->route("login");
});

Route::get('/dashboard', [DashboardController::class, "index"])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])
        ->name('users.index');

    // Single user details
    Route::get('/users/{user}', [\App\Http\Controllers\UserController::class, 'show'])
        ->name('users.show');

    Route::post('/users/{userid}/change-plan',
        [UserController::class, 'changePlan']
    )->name('users.change-plan');

    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/transactions/print/{id}', [TransactionController::class, 'print'])->name('transactions.print');

    Route::resource('translations', TranslationController::class)->except(['show']);

    Route::resource('enterprises', EnterpriseController::class);
    Route::get('/app-settings/legal', [AppSettingController::class, 'editLegal'])->name('legal.index');
    Route::post('/app-settings/legal', [AppSettingController::class, 'updateLegal'])->name('legal.update');
});

require __DIR__.'/auth.php';
