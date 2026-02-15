<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Профиль преподавателя
     */
    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    /**
     * Профиль ученика
     */
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    /**
     * Проверка, является ли пользователь преподавателем
     */
    public function isTeacher()
    {
        return $this->teacher !== null;
    }

    /**
     * Проверка, является ли пользователь учеником
     */
    public function isStudent()
    {
        return $this->student !== null;
    }
}
