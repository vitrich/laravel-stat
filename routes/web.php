<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StatisticsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Главная страница
Route::get('/', [HomeController::class, 'index'])->name('home');

<<<<<<< HEAD
=======
// Новости
Route::get('/news', function () {
    return view('news');
})->name('news');

Route::get('/news/archive', function () {
    return view('news', ['archive' => true]);
})->name('news.archive');

// Уроки (только для авторизованных пользователей)
Route::middleware(['auth'])->group(function () {
    Route::get('/lesson/{lesson_date}', [LessonController::class, 'view'])->name('lesson.view');
    Route::post('/lesson/{lesson_date}/submit', [LessonController::class, 'submit'])->name('lesson.submit');
    Route::get('/lesson/{lesson_date}/result', [LessonController::class, 'result'])->name('lesson.result');
});

// Задачи
Route::middleware(['auth'])->prefix('tasks')->group(function () {
    Route::get('/', function () {
        return view('tasks');
    })->name('tasks');
    
    Route::get('/active', function () {
        return view('tasks', ['filter' => 'active']);
    })->name('tasks.active');
    
    Route::get('/completed', function () {
        return view('tasks', ['filter' => 'completed']);
    })->name('tasks.completed');
    
    Route::get('/create', function () {
        return view('tasks', ['mode' => 'create']);
    })->name('tasks.create');
});

// Ученики (только для преподавателей)
Route::middleware(['auth', 'teacher'])->prefix('students')->group(function () {
    Route::get('/', [StudentController::class, 'index'])->name('students');
    
    Route::get('/list', function () {
        return view('students', ['view' => 'list']);
    })->name('students.list');
    
    Route::get('/groups', function () {
        return view('students', ['view' => 'groups']);
    })->name('students.groups');
    
    Route::get('/progress', function () {
        return view('students', ['view' => 'progress']);
    })->name('students.progress');
});

// Статистика (только для преподавателей)
Route::middleware(['auth', 'teacher'])->prefix('statistics')->group(function () {
    Route::get('/', [StatisticsController::class, 'index'])->name('statistics');
    
    Route::get('/overview', function () {
        return view('statistics', ['view' => 'overview']);
    })->name('stats.overview');
    
    Route::get('/reports', function () {
        return view('statistics', ['view' => 'reports']);
    })->name('stats.reports');
    
    Route::get('/analytics', function () {
        return view('statistics', ['view' => 'analytics']);
    })->name('stats.analytics');
});

>>>>>>> a351b8ff5bfaa518e9cb441ecdbb953d0f9e538b
// Аутентификация (Laravel Breeze добавит свои маршруты)
require __DIR__.'/auth.php';
