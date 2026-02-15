<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'student_id',
        'answer_text',
        'file_path',
        'grade',
        'teacher_comment',
    ];

    /**
     * Задание
     */
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    /**
     * Студент
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
