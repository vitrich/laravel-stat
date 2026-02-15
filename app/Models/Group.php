<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'teacher_id',
        'description',
        'color',
    ];

    protected $casts = [
        'number' => 'float',
    ];

    /**
     * Преподаватель группы
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Студенты группы
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'current_group_id');
    }

    /**
     * История группы
     */
    public function groupHistory()
    {
        return $this->hasMany(GroupHistory::class);
    }

    /**
     * Форматированное название группы
     */
    public function getFormattedNameAttribute()
    {
        return "Группа {$this->number}";
    }
}
