<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\SuratPengantarController;
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

        //Surat Pengantar Bapendik
        Route::get('/surat-pengantar', [SuratPengantarController::class, 'adminIndex'])->name('surat-pengantar.index');
//        Route::get('/surat-pengantar/{suratPengantar}/edit', [SuratPengantarController::class, 'edit'])->name('surat-pengantar.edit');
        Route::patch('/surat-pengantar/{suratPengantar}', [SuratPengantarController::class, 'update'])->name('surat-pengantar.update');
        Route::get('/surat-pengantar/data', [SuratPengantarController::class, 'datatable'])->name('surat-pengantar.datatable');
        Route::get('/surat-pengantar/{suratPengantar}/export-word', [SuratPengantarController::class, 'exportWord'])->name('surat-pengantar.export-word');

    });

    // Route Dasbor Dosen
    Route::middleware('role:dosen')->prefix('dosen')->name('dosen.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'dosen'])->name('dashboard');
    });

    // Route Dasbor Mahasiswa
    Route::middleware('role:mahasiswa')->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'mahasiswa'])->name('dashboard');

        //Surat Pengantar Mahasiswa
        Route::get('/surat-pengantar', [SuratPengantarController::class, 'index'])->name('surat-pengantar.index');
        Route::post('/surat-pengantar', [SuratPengantarController::class, 'store'])->name('surat-pengantar.store');
        Route::get('/surat-pengantar/data', [SuratPengantarController::class, 'mahasiswaDatatable'])->name('surat-pengantar.datatable');
        Route::delete('/surat-pengantar/{suratPengantar}', [SuratPengantarController::class, 'cancel'])->name('surat-pengantar.cancel');
    });

    // Route Profil (umum untuk semua role)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

// Route Autentikasi Bawaan Breeze
require __DIR__.'/auth.php';
