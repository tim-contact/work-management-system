<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\Auth\Register;
use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\Auth\Logout;

Route::get('/', function() {
    return view('auth.login');
});

Route::middleware('auth')->group(function () {
    Route::get('/works', [WorkController::class, 'index'])->name('works.work');
    Route::post('/works', [WorkController::class, 'store'])->name('works.store');
    Route::get('/works/{work}/edit', [WorkController::class, 'edit'])->name('works.edit');
    Route::put('/works/{work}', [WorkController::class, 'update'])->name('works.update');
    Route::delete('/works/{work}', [WorkController::class, 'destroy'])->name('works.destroy');

    Route::post('/works/{work}/start', [WorkController::class, 'start'])->name('works.start');
    Route::post('/works/{work}/stop', [WorkController::class, 'stop'])->name('works.stop'); 
    Route::post('/works/{work}/completed', [WorkController::class, 'completed'])->name('works.completed');
});

Route::view('/register', 'auth.register')
    ->middleware('guest')
    ->name('register');
 
Route::post('/register', Register::class)
    ->middleware('guest');


//Login Routes

Route::view('/login', 'auth.login')
    ->middleware('guest')
    ->name('login');

Route::post('/login', Login::class)
    ->middleware('guest');

// Logout Route
Route::post('/logout', Logout::class)
    ->middleware('auth')
    ->name('logout');