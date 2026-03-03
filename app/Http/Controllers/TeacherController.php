<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!$request->user()->is_teacher) {
                abort(403, 'Доступ запрещен. Только для учителей.');
            }
            return $next($request);
        });
    }

    /**
     * Список всех уроков/контрольных работ
     */
    public function index()
    {
        $lessons = Lesson::orderBy('date', 'desc')->paginate(20);
        return view('teacher.lessons.index', compact('lessons'));
    }

    /**
     * Форма создания новой контрольной работы
     */
    public function create()
    {
        return view('teacher.lessons.create');
    }

    /**
     * Сохранение новой контрольной работы
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'description' => 'nullable|string',
            'theory' => 'nullable|string',
            'lesson_number' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        Lesson::create($validated);

        return redirect()->route('teacher.lessons.index')
            ->with('success', 'Контрольная работа успешно создана!');
    }

    /**
     * Форма редактирования контрольной работы
     */
    public function edit($id)
    {
        $lesson = Lesson::findOrFail($id);
        return view('teacher.lessons.edit', compact('lesson'));
    }

    /**
     * Обновление контрольной работы
     */
    public function update(Request $request, $id)
    {
        $lesson = Lesson::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'description' => 'nullable|string',
            'theory' => 'nullable|string',
            'lesson_number' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $lesson->update($validated);

        return redirect()->route('teacher.lessons.index')
            ->with('success', 'Контрольная работа успешно обновлена!');
    }

    /**
     * Удаление контрольной работы
     */
    public function destroy($id)
    {
        $lesson = Lesson::findOrFail($id);
        $lesson->delete();

        return redirect()->route('teacher.lessons.index')
            ->with('success', 'Контрольная работа успешно удалена!');
    }
}
