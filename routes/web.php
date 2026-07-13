<?php

use App\Http\Controllers\CollaborationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpertiseMapController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\TopicController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/export/excel', [DashboardController::class, 'export'])->name('dashboard.export.excel');
Route::get('/dashboard/export/pdf', [DashboardController::class, 'exportPdf'])->name('dashboard.export.pdf');

Route::get('/topik-dominan', [TopicController::class, 'index'])->name('topics.index');
Route::get('/peta-keahlian', [ExpertiseMapController::class, 'index'])->name('expertise.index');

// Route export statis harus didaftarkan sebelum /dosen/{lecturer} supaya
// "export" tidak ditangkap sebagai parameter {lecturer} (kode NIP).
Route::get('/dosen/export/excel', [LecturerController::class, 'export'])->name('lecturers.export.excel');
Route::get('/dosen/export/pdf', [LecturerController::class, 'exportPdf'])->name('lecturers.export.pdf');
Route::get('/dosen', [LecturerController::class, 'index'])->name('lecturers.index');
Route::get('/dosen/{lecturer}', [LecturerController::class, 'show'])->name('lecturers.show');

Route::get('/kolaborasi/export/excel', [CollaborationController::class, 'export'])->name('collaborations.export.excel');
Route::get('/kolaborasi/export/pdf', [CollaborationController::class, 'exportPdf'])->name('collaborations.export.pdf');
Route::get('/kolaborasi', [CollaborationController::class, 'index'])->name('collaborations.index');

Route::get('/rekomendasi', [RecommendationController::class, 'index'])->name('recommendations.index');
