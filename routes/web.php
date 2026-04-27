<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
// require __DIR__ . '/auth.php';


// Route::get('/login', fn() => view('auth.login'))->name('login');
// Route::middleware(['auth'])->group(function () {
 
    Route::get('/', fn() => redirect()->route('dashboard'));
    Route::get('/dashboard', fn() => view('dashboard.index'))->name('dashboard');
 
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
 
// });