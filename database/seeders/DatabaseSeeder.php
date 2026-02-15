<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Group;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Создаем преподавателя
        $teacherUser = User::create([
            'name' => 'Иванов Иван Иванович',
            'email' => 'teacher@example.com',
            'password' => Hash::make('password'),
        ]);

        $teacher = Teacher::create([
            'user_id' => $teacherUser->id,
            'full_name' => 'Иванов Иван Иванович',
            'email' => 'teacher@example.com',
        ]);

        // Создаем группы
        $groups = [
            ['number' => 1, 'color' => '#bd2e8d'],
            ['number' => 2, 'color' => '#2e8dbd'],
            ['number' => 2.1, 'color' => '#8d2ebd'],
            ['number' => 2.2, 'color' => '#bd8d2e'],
            ['number' => 3, 'color' => '#2ebd8d'],
        ];

        foreach ($groups as $groupData) {
            Group::create([
                'number' => $groupData['number'],
                'teacher_id' => $teacher->id,
                'description' => "Группа {$groupData['number']}",
                'color' => $groupData['color'],
            ]);
        }

        // Создаем тестового ученика
        $studentUser = User::create([
            'name' => 'Петров Петр Петрович',
            'email' => 'student@example.com',
            'password' => Hash::make('password'),
        ]);

        Student::create([
            'user_id' => $studentUser->id,
            'full_name' => 'Петров Петр Петрович',
            'class_name' => '5А',
            'current_group_id' => 1,
        ]);

        $this->command->info('Тестовые данные созданы!');
        $this->command->info('Преподаватель: teacher@example.com / password');
        $this->command->info('Ученик: student@example.com / password');
    }
}
