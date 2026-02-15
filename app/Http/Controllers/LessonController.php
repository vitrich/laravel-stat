<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Models\LessonTask;
use App\Models\Student;

class LessonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Просмотр урока и выполнение заданий
     */
    public function view(Request $request, $lessonDate)
    {
        $lesson = Lesson::where('date', $lessonDate)
            ->where('is_active', true)
            ->firstOrFail();
        
        $student = $request->user()->student;
        
        if (!$student) {
            return redirect()->route('home')
                ->with('error', 'Ваш профиль ученика не найден.');
        }
        
        $lessonTask = LessonTask::firstOrCreate(
            [
                'lesson_id' => $lesson->id,
                'student_id' => $student->id,
            ]
        );
        
        if (!$lessonTask->tasks_data || empty($lessonTask->tasks_data)) {
            $lessonTask->generateTasks();
        }
        
        $tasks = $lessonTask->tasks_data;
        
        return view('lesson', [
            'lesson' => $lesson,
            'lesson_task' => $lessonTask,
            'tasks' => $tasks,
            'already_submitted' => $lessonTask->submitted_at !== null
        ]);
    }

    /**
     * Отправка ответов
     */
    public function submit(Request $request, $lessonDate)
    {
        $lesson = Lesson::where('date', $lessonDate)
            ->where('is_active', true)
            ->firstOrFail();
        
        $student = $request->user()->student;
        
        if (!$student) {
            return redirect()->route('home')
                ->with('error', 'Ваш профиль ученика не найден.');
        }
        
        $lessonTask = LessonTask::where('lesson_id', $lesson->id)
            ->where('student_id', $student->id)
            ->firstOrFail();
        
        if ($lessonTask->submitted_at) {
            return redirect()->route('lesson.result', $lessonDate)
                ->with('warning', 'Вы уже сдали этот тест!');
        }
        
        $answers = [];
        $tasks = $lessonTask->tasks_data;
        
        for ($i = 0; $i < count($tasks); $i++) {
            $answers[$i] = $request->input("answer_$i", '');
        }
        
        $score = $lessonTask->checkAnswers($answers);
        
        return redirect()->route('lesson.result', $lessonDate)
            ->with('success', "Тест сдан! Ваша оценка: $score из 7");
    }

    /**
     * Результаты выполнения урока
     */
    public function result(Request $request, $lessonDate)
    {
        $lesson = Lesson::where('date', $lessonDate)->firstOrFail();
        
        $student = $request->user()->student;
        
        if (!$student) {
            return redirect()->route('home')
                ->with('error', 'Ваш профиль ученика не найден.');
        }
        
        $lessonTask = LessonTask::where('lesson_id', $lesson->id)
            ->where('student_id', $student->id)
            ->firstOrFail();
        
        if (!$lessonTask->submitted_at) {
            return redirect()->route('lesson.view', $lessonDate)
                ->with('warning', 'Сначала выполните тест!');
        }
        
        $tasks = $lessonTask->tasks_data;
        $answers = $lessonTask->answers ?? [];
        
        $results = [];
        foreach ($tasks as $i => $task) {
            $userAnswer = $answers[$i] ?? '';
            $correctAnswer = $task['answer'];
            $isCorrect = trim(strtolower($userAnswer)) === trim(strtolower($correctAnswer));
            
            $results[] = [
                'task' => $task,
                'user_answer' => $userAnswer,
                'correct_answer' => $correctAnswer,
                'is_correct' => $isCorrect
            ];
        }
        
        return view('lesson_result', [
            'lesson' => $lesson,
            'lesson_task' => $lessonTask,
            'results' => $results
        ]);
    }
}
