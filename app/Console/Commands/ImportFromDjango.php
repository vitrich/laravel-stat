<?php

namespace App\Console\Commands;

use App\Models\Assignment;
use App\Models\Lesson;
use App\Models\LessonTask;
use App\Models\Student;
use App\Models\Submission;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportFromDjango extends Command
{
    protected $signature = 'import:from-django';
    protected $description = 'Импорт пользователей, уроков и результатов из БД Django co53144_vitr';

    public function handle()
    {
        $this->info('=== Импорт пользователей и профилей ===');
        $this->importUsersAndProfiles();

        $this->info('=== Импорт уроков ===');
        $this->importLessons();

        $this->info('=== Импорт результатов тестов (lesson_tasks) ===');
        $this->importLessonTasks();

        $this->info('=== Импорт домашних заданий (submissions) ===');
        $this->importSubmissions();

        $this->info('Готово.');
        return 0;
    }

    protected function importUsersAndProfiles(): void
    {
        DB::connection('django')
            ->table('auth_user')
            ->orderBy('id')
            ->chunk(100, function ($djangoUsers) {

                foreach ($djangoUsers as $du) {
                    $email = $du->email ?: $du->username.'@example.local';

                    $user = User::firstOrCreate(
                        ['email' => $email],
                        [
                            'name'     => trim($du->first_name.' '.$du->last_name) ?: $du->username,
                            'password' => bcrypt(Str::random(32)),
                        ]
                    );

                    // Учитель
                    $teacherProfile = DB::connection('django')
                        ->table('main_teacher')
                        ->where('user_id', $du->id)
                        ->first();

                    if ($teacherProfile) {
                        Teacher::firstOrCreate(
                            ['user_id' => $user->id],
                            [
                                'full_name' => $teacherProfile->full_name,
                                'email'     => $teacherProfile->email ?: $user->email,
                            ]
                        );
                    }

                    // Ученик
                    $studentProfile = DB::connection('django')
                        ->table('main_student')
                        ->where('user_id', $du->id)
                        ->first();

                    if ($studentProfile) {
                        Student::firstOrCreate(
                            ['user_id' => $user->id],
                            [
                                'full_name'        => $studentProfile->full_name,
                                'class_name'       => $studentProfile->class_name,
                                'current_group_id' => $studentProfile->current_group_id,
                            ]
                        );
                    }
                }
            });
    }

    protected function importLessons(): void
    {
        $djangoLessons = DB::connection('django')
            ->table('main_lesson')
            ->orderBy('id')
            ->get();

        foreach ($djangoLessons as $dl) {
            Lesson::firstOrCreate(
                ['id' => $dl->id], // сохраняем те же id
                [
                    'title'                 => $dl->title,
                    'date'                  => $dl->date,
                    'subject'               => $dl->subject,
                    'grade'                 => $dl->grade,
                    'theory_content'        => $dl->theory_content,
                    'duration_minutes'      => $dl->duration_minutes,
                    'test_duration_minutes' => $dl->test_duration_minutes,
                    'is_active'             => $dl->is_active,
                    'created_at'            => $dl->created_at,
                    'updated_at'            => $dl->created_at,
                ]
            );
        }
    }

    protected function importLessonTasks(): void
    {
        DB::connection('django')
            ->table('main_lessontask')
            ->orderBy('id')
            ->chunk(100, function ($rows) {
                foreach ($rows as $lt) {
                    // Ищем студента по id, если совпало
                    $student = DB::table('students')
                        ->where('id', $lt->student_id)
                        ->first();

                    if (!$student) {
                        // fallback через user/email
                        $djangoStudent = DB::connection('django')
                            ->table('main_student')
                            ->where('id', $lt->student_id)
                            ->first();

                        if ($djangoStudent && $djangoStudent->user_id) {
                            $djangoUser = DB::connection('django')
                                ->table('auth_user')
                                ->where('id', $djangoStudent->user_id)
                                ->first();

                            if ($djangoUser) {
                                $email = $djangoUser->email ?: $djangoUser->username.'@example.local';

                                $user = User::where('email', $email)->first();
                                if ($user) {
                                    $student = DB::table('students')
                                        ->where('user_id', $user->id)
                                        ->first();
                                }
                            }
                        }
                    }

                    if (!$student) {
                        continue;
                    }

                    // В Django longtext, в Laravel json
                    $tasksData = $this->decodeMaybeJson($lt->tasks_data);
                    $answers   = $this->decodeMaybeJson($lt->answers);

                    LessonTask::updateOrCreate(
                        [
                            'lesson_id'  => $lt->lesson_id,
                            'student_id' => $student->id,
                        ],
                        [
                            'tasks_data'    => $tasksData,
                            'answers'       => $answers,
                            'score'         => $lt->score,
                            'correct_count' => $lt->correct_count,
                            'total_count'   => $lt->total_count,
                            'submitted_at'  => $lt->submitted_at,
                        ]
                    );
                }
            });
    }

    protected function importSubmissions(): void
    {
        DB::connection('django')
            ->table('main_submission')
            ->orderBy('id')
            ->chunk(100, function ($rows) {
                foreach ($rows as $s) {
                    // Находим студента
                    $student = DB::table('students')
                        ->where('id', $s->student_id)
                        ->first();

                    if (!$student) {
                        $djangoStudent = DB::connection('django')
                            ->table('main_student')
                            ->where('id', $s->student_id)
                            ->first();

                        if ($djangoStudent && $djangoStudent->user_id) {
                            $djangoUser = DB::connection('django')
                                ->table('auth_user')
                                ->where('id', $djangoStudent->user_id)
                                ->first();

                            if ($djangoUser) {
                                $email = $djangoUser->email ?: $djangoUser->username.'@example.local';

                                $user = User::where('email', $email)->first();
                                if ($user) {
                                    $student = DB::table('students')
                                        ->where('user_id', $user->id)
                                        ->first();
                                }
                            }
                        }
                    }

                    if (!$student) {
                        continue;
                    }

                    // Assignment: предполагаем совпадение id (main_assignment.id = assignments.id)
                    $assignment = Assignment::find($s->assignment_id);
                    if (!$assignment) {
                        continue;
                    }

                    Submission::updateOrCreate(
                        [
                            'assignment_id' => $assignment->id,
                            'student_id'    => $student->id,
                        ],
                        [
                            'answer_text'     => $s->answer_text,
                            'file_path'       => $s->file,           // в Django поле `file` → в Laravel `file_path`
                            'grade'           => $s->grade,
                            'teacher_comment' => $s->teacher_comment,
                            'created_at'      => $s->submitted_at,
                            'updated_at'      => $s->submitted_at,
                        ]
                    );
                }
            });
    }

    protected function decodeMaybeJson(?string $value)
    {
        if ($value === null || $value === '') {
            return [];
        }

        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        return ['raw' => $value];
    }
}
