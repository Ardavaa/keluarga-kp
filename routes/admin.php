<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LecturerController;
use App\Http\Controllers\Admin\PublicationController;
use Illuminate\Support\Facades\Route;

// Semua route di sini wajib login (Admin) — dashboard publik (routes/web.php)
// tetap bisa diakses tanpa login, sesuai docs/PRD.md §3.1.
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/dosen', [LecturerController::class, 'index'])->name('lecturers.index');
    Route::get('/dosen/{lecturer}', [LecturerController::class, 'edit'])->name('lecturers.edit');
    Route::put('/dosen/{lecturer}', [LecturerController::class, 'update'])->name('lecturers.update');

    Route::post('/dosen/{lecturer}/publikasi', [PublicationController::class, 'store'])->name('publications.store');
    Route::put('/dosen/{lecturer}/publikasi/{publication}', [PublicationController::class, 'update'])->name('publications.update');
    Route::delete('/dosen/{lecturer}/publikasi/{publication}', [PublicationController::class, 'destroy'])->name('publications.destroy');
});
