<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Главная страница с списком уроков
     */
    public function index()
    {
        // Получаем активные уроки, отсортированные по дате
        $lessons = Lesson::where('is_active', true)
            ->orderBy('date', 'desc')
            ->get();

        return view('home', compact('lessons'));
    }
}
