<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'class_name',
        'current_group_id',
    ];

    /**
     * Связь с пользователем
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Текущая группа студента
     */
    public function currentGroup()
    {
        return $this->belongsTo(Group::class, 'current_group_id');
    }

    /**
     * История групп студента
     */
    public function groupHistory()
    {
        return $this->hasMany(GroupHistory::class);
    }

    /**
     * Задания студента
     */
    public function lessonTasks()
    {
        return $this->hasMany(LessonTask::class);
    }

    /**
     * Сданные работы
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
