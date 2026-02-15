<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\LessonTask;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Главная страница со списком уроков и статусами для студента.
     */
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

        // Если пользователь не авторизован, просто покажем активные уроки
        if (empty($context['lessons'])) {
            $context['lessons'] = Lesson::where('is_active', true)
                ->orderBy('date')
                ->get();
        }

        return view('home', $context);
    }
}
