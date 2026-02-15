<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'group_id',
        'teacher_id',
        'deadline',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    /**
     * Группа
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Преподаватель
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Сданные работы
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
