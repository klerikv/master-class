<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CraftTypeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InstructorController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/show/{craftType}', [CraftTypeController::class, 'show'])->name('craft-type.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::prefix('instructor')->name('instructor.')->group(function () {
        Route::get('/dashboard', [InstructorController::class, 'dashboard'])->name('dashboard');
        Route::get('/create', [InstructorController::class, 'showCreateForm'])->name('create-form');
        Route::post('/create', [InstructorController::class, 'createMasterClass'])->name('create');
        Route::get('/edit/{masterClass}', [InstructorController::class, 'showEditForm'])->name('edit-form');
        Route::put('/update/{masterClass}', [InstructorController::class, 'updateMasterClass'])->name('update');
        Route::get('/check-slots', [InstructorController::class, 'checkOccupiedSlots'])->name('check-slots');
    });

    Route::get('/booking/confirm/{masterClass}', [BookingController::class, 'showConfirmForm'])->name('booking.confirm-form');
    Route::post('/booking/confirm/{masterClass}', [BookingController::class, 'confirm'])->name('booking.confirm');
});
