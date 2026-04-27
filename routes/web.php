<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
require __DIR__ . '/auth.php';


// Route::get('/login', fn() => view('auth.login'))->name('login');
Route::middleware(['auth'])->group(function () {
 
    Route::get('/', fn() => redirect()->route('dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::prefix('bookings')->name('bookings.')->group(function () {
 
        // Resource routes standar
        Route::get('/',[BookingController::class, 'index'])->name('index');
        Route::get('/create',[BookingController::class, 'create'])->name('create');
        Route::post('/',[BookingController::class, 'store'])->name('store');
        Route::get('/{booking}',[BookingController::class, 'show'])->name('show');
        Route::get('/{booking}/edit',[BookingController::class, 'edit'])->name('edit');
        Route::put('/{booking}',[BookingController::class, 'update'])->name('update');
        Route::delete('/{booking}',[BookingController::class, 'destroy'])
             ->middleware('role:admin')
             ->name('destroy');
 
        // Status action routes
        Route::patch('/{booking}/approve',[BookingController::class, 'approve'])
             ->middleware('role:admin,staff')
             ->name('approve');
 
        Route::patch('/{booking}/reject',[BookingController::class, 'reject'])
             ->middleware('role:admin,staff')
             ->name('reject');
 
        Route::patch('/{booking}/cancel',[BookingController::class, 'cancel'])
             ->name('cancel');
 
        Route::patch('/{booking}/complete',[BookingController::class, 'complete'])
             ->middleware('role:admin,staff')
             ->name('complete');
 
        // AJAX availability check
        Route::get('/check-availability',[BookingController::class, 'checkAvailability'])
             ->name('check-availability');
    });
    
    /*
    |----------------------------------------------------------------------
    | User Management — hanya admin
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin')->prefix('users')->name('users.')->group(function () {
        Route::get('/',[UserController::class, 'index'])->name('index');
        Route::get('/create',[UserController::class, 'create'])->name('create');
        Route::post('/',[UserController::class, 'store'])->name('store');
        Route::get('/{user}',[UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit',[UserController::class, 'edit'])->name('edit');
        Route::put('/{user}',[UserController::class, 'update'])->name('update');
        Route::delete('/{user}',[UserController::class, 'destroy'])->name('destroy');
        Route::patch('/{user}/toggle-active',[UserController::class, 'toggleActive'])->name('toggle-active');
    });
 
});