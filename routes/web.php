<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Маршруты для учителей
Route::middleware(['auth'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/lessons', [TeacherController::class, 'index'])->name('lessons.index');
    Route::get('/lessons/create', [TeacherController::class, 'create'])->name('lessons.create');
    Route::post('/lessons', [TeacherController::class, 'store'])->name('lessons.store');
    Route::get('/lessons/{id}/edit', [TeacherController::class, 'edit'])->name('lessons.edit');
    Route::put('/lessons/{id}', [TeacherController::class, 'update'])->name('lessons.update');
    Route::delete('/lessons/{id}', [TeacherController::class, 'destroy'])->name('lessons.destroy');
});

require __DIR__.'/auth.php';
