<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\LawController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TemplateController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('dashboard'));

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('laws', LawController::class);
    Route::resource('companies', CompanyController::class);
    Route::resource('projects', ProjectController::class);
    Route::resource('templates', TemplateController::class);

    Route::prefix('projects/{project}')->name('projects.')->group(function () {
        Route::resource('documents', DocumentController::class)->names([
            'index'   => 'documents.index',
            'create'  => 'documents.create',
            'store'   => 'documents.store',
            'show'    => 'documents.show',
            'edit'    => 'documents.edit',
            'update'  => 'documents.update',
            'destroy' => 'documents.destroy',
        ]);
        Route::get('export', [ExportController::class, 'projectZip'])->name('export');
    });
});

// Admin Technical Section
Route::prefix('admin/technical')->name('admin.technical.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/backups', [\App\Http\Controllers\Admin\BackupController::class, 'index'])->name('backups.index');
    Route::post('/backups', [\App\Http\Controllers\Admin\BackupController::class, 'store'])->name('backups.store');
    Route::get('/backups/restore/{restoreUuid}/status', [\App\Http\Controllers\Admin\BackupController::class, 'restoreStatus'])->name('backups.restore-status');
    Route::get('/backups/{backup}/status', [\App\Http\Controllers\Admin\BackupController::class, 'status'])->name('backups.status');
    Route::get('/backups/{backup}/download', [\App\Http\Controllers\Admin\BackupController::class, 'download'])->name('backups.download');
    Route::post('/backups/{backup}/restore', [\App\Http\Controllers\Admin\BackupController::class, 'restore'])->name('backups.restore');
    Route::delete('/backups/{backup}', [\App\Http\Controllers\Admin\BackupController::class, 'destroy'])->name('backups.destroy');
});

require __DIR__ . '/auth.php';
