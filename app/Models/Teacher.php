<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'email',
    ];

    /**
     * Связь с пользователем
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Группы преподавателя
     */
    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    /**
     * Задания преподавателя
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
