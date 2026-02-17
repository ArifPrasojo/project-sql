<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    // Jika belum, arahkan ke login
    return redirect()->route('login');
});

// ROUTE DASHBOARD KHUSUS
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('users', \App\Http\Controllers\AdminUserController::class)
        ->names('admin.users');

    // === ROUTE DOSEN ===
    Route::middleware('role:dosen')->prefix('dosen')->name('dosen.')->group(function() {
        Route::get('/create-class', [LecturerController::class, 'createClass'])->name('class.create');
        Route::post('/store-class', [LecturerController::class, 'storeClass'])->name('class.store');
        Route::get('/class/{classroom}', [LecturerController::class, 'showClass'])->name('class.show');
        
        // Materi & Soal
        Route::post('/class/{classroom}/material', [LecturerController::class, 'storeMaterial'])->name('material.store');
        Route::get('/material/{material}/edit', [LecturerController::class, 'editMaterial'])->name('material.edit');
        Route::put('/material/{material}', [LecturerController::class, 'updateMaterial'])->name('material.update');
        Route::delete('/material/{material}', [LecturerController::class, 'destroyMaterial'])->name('material.destroy');

// 1. Halaman List Soal
    Route::get('/material/{material}/questions', [LecturerController::class, 'manageQuestions'])->name('questions.index');
    
    // 2. Simpan Soal (Ini yang menyebabkan error "not defined")
    Route::post('/material/{material}/questions', [LecturerController::class, 'storeQuestion'])->name('questions.store');
    
    // 3. Edit & Update Soal
    Route::get('/question/{question}/edit', [LecturerController::class, 'editQuestion'])->name('questions.edit');
    Route::put('/question/{question}', [LecturerController::class, 'updateQuestion'])->name('questions.update');
    
    // 4. Hapus Soal
    Route::delete('/question/{question}', [LecturerController::class, 'destroyQuestion'])->name('questions.destroy');
    });

    // === ROUTE MAHASISWA ===
    Route::post('/join-class', [StudentController::class, 'joinClass'])->name('student.join');

});



require __DIR__.'/auth.php';
