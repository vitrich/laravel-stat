<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
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
=======
use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Models\LessonTask;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $context = [];

        if ($request->user()) {
            $student = $request->user()->student;
            
            if ($student) {
                $lessons = Lesson::where('is_active', true)
                    ->orderBy('date')
                    ->get();

                $lessonsWithStatus = [];
                $completedCount = 0;

                foreach ($lessons as $lesson) {
                    $lessonTask = LessonTask::where('lesson_id', $lesson->id)
                        ->where('student_id', $student->id)
                        ->first();

                    $lesson->completed = $lessonTask && $lessonTask->submitted_at !== null;
                    $lesson->score = $lessonTask && $lessonTask->submitted_at ? $lessonTask->score : null;

                    if ($lesson->completed) {
                        $completedCount++;
                    }

                    $lessonsWithStatus[] = $lesson;
                }

                $context['lessons'] = $lessonsWithStatus;
                $context['student'] = $student;
                $context['completed_count'] = $completedCount;
                $context['total_lessons'] = count($lessonsWithStatus);
            }
        }

        return view('home', $context);
>>>>>>> a351b8ff5bfaa518e9cb441ecdbb953d0f9e538b
    }
}
