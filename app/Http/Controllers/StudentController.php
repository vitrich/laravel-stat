<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Lesson;
use App\Models\LessonTask;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'teacher']);
    }

    /**
     * Таблица результатов всех учеников
     */
    public function index(Request $request)
    {
        $teacher = $request->user()->teacher;
        $teacherGroups = $teacher->groups;
        
        $students = Student::whereIn('current_group_id', $teacherGroups->pluck('id'))
            ->orderBy('full_name')
            ->get();
        
        $lessons = Lesson::where('is_active', true)
            ->orderBy('date')
            ->get();
        
        $resultsTable = [];
        foreach ($students as $student) {
            $studentData = [
                'student' => $student,
                'results' => []
            ];
            
            foreach ($lessons as $lesson) {
                $task = LessonTask::where('lesson_id', $lesson->id)
                    ->where('student_id', $student->id)
                    ->first();
                
                if ($task && $task->submitted_at) {
                    $studentData['results'][] = [
                        'lesson' => $lesson,
                        'score' => $task->score,
                        'submitted' => true
                    ];
                } else {
                    $studentData['results'][] = [
                        'lesson' => $lesson,
                        'score' => null,
                        'submitted' => false
                    ];
                }
            }
            
            $resultsTable[] = $studentData;
        }
        
        return view('students', [
            'results_table' => $resultsTable,
            'lessons' => $lessons,
            'teacher_groups' => $teacherGroups
        ]);
    }
}
