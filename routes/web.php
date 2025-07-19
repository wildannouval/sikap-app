<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

// Halaman Awal
Route::get('/', function () {
    return view('welcome');
});

// Grup Route untuk Pengguna yang Sudah Login
Route::middleware(['auth', 'verified'])->group(function () {

    // Route Dasbor Bapendik
    Route::middleware('role:bapendik')->prefix('bapendik')->name('bapendik.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'bapendik'])->name('dashboard');
    });

    // Route Dasbor Dosen
    Route::middleware('role:dosen')->prefix('dosen')->name('dosen.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'dosen'])->name('dashboard');
    });

    // Route Dasbor Mahasiswa
    Route::middleware('role:mahasiswa')->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'mahasiswa'])->name('dashboard');
    });

    // Route Profil (umum untuk semua role)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route Autentikasi Bawaan Breeze
require __DIR__.'/auth.php';
