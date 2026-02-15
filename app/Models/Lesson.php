<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'date',
        'subject',
        'grade',
        'theory_content',
        'duration_minutes',
        'test_duration_minutes',
        'is_active',
    ];

    protected $casts = [
        'date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Задания урока
     */
    public function tasks()
    {
        return $this->hasMany(LessonTask::class);
    }

    /**
     * Получить задание для конкретного студента
     */
    public function getTaskForStudent($studentId)
    {
        return $this->tasks()->where('student_id', $studentId)->first();
    }
}
