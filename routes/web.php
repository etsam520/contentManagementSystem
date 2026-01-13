<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UploadController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Home/Landing page
Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/home', function () {
    return Inertia::render('Welcome');
});

// Video upload routes
Route::group(['prefix' => 'upload'], function () {
    Route::post('/init', [UploadController::class, 'init'])->name('upload.init');
    Route::post('/chunk', [UploadController::class, 'uploadChunk'])->name('upload.chunk');
    Route::post('/complete', [UploadController::class, 'complete'])->name('upload.complete');
});

// Upload page
Route::get('/upload', function () {
    return Inertia::render('Upload');
})->name('upload.page');

// Video list
Route::get('/videos', [UploadController::class, 'index'])->name('videos.index');
Route::delete('/videos/{id}', [UploadController::class, 'destroy'])->name('videos.destroy');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
