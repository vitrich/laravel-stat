<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'student_id',
        'tasks_data',
        'answers',
        'score',
        'correct_count',
        'total_count',
        'submitted_at',
    ];

    protected $casts = [
        'tasks_data' => 'array',
        'answers' => 'array',
        'submitted_at' => 'datetime',
    ];

    /**
     * Урок
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Студент
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Генерация индивидуальных заданий
     */
    public function generateTasks()
    {
        $lessonDate = $this->lesson->date->format('Y-m-d');
        
        if ($lessonDate === '2026-02-03' || stripos($this->lesson->title, 'сравнение') !== false) {
            $tasks = $this->generateComparisonTasks();
        } else {
            $tasks = $this->generateMixedFractionTasks();
        }
        
        $this->tasks_data = $tasks;
        $this->total_count = count($tasks);
        $this->save();
    }

    /**
     * Генерация заданий Урок 1: Смешанные и неправильные дроби
     */
    private function generateMixedFractionTasks()
    {
        $tasks = [];
        
        // 3 задания на классификацию
        for ($i = 0; $i < 3; $i++) {
            $numerator = rand(1, 20);
            $denominator = rand(2, 15);
            $tasks[] = [
                'type' => 'classify',
                'numerator' => $numerator,
                'denominator' => $denominator,
                'answer' => $numerator < $denominator ? 'proper' : 'improper'
            ];
        }

        // 3 задания: смешанная → неправильная
        for ($i = 0; $i < 3; $i++) {
            $whole = rand(1, 10);
            $numerator = rand(1, 8);
            $denominator = rand(2, 9);
            $answerNum = $whole * $denominator + $numerator;
            $tasks[] = [
                'type' => 'mixed_to_improper',
                'whole' => $whole,
                'numerator' => $numerator,
                'denominator' => $denominator,
                'answer' => "$answerNum/$denominator"
            ];
        }

        // 4 задания: неправильная → смешанная
        for ($i = 0; $i < 4; $i++) {
            $denominator = rand(2, 9);
            $whole = rand(1, 8);
            $numerator = rand(1, $denominator - 1);
            $improperNum = $whole * $denominator + $numerator;
            $tasks[] = [
                'type' => 'improper_to_mixed',
                'numerator' => $improperNum,
                'denominator' => $denominator,
                'answer' => "$whole $numerator/$denominator"
            ];
        }
        
        return $tasks;
    }

    /**
     * Генерация заданий Урок 2: Сравнение и сокращение дробей
     */
    private function generateComparisonTasks()
    {
        $tasks = [];
        
        // 3 задания на сокращение
        for ($i = 0; $i < 3; $i++) {
            $gcdValue = [2, 3, 4, 5, 6][array_rand([2, 3, 4, 5, 6])];
            $numeratorReduced = rand(1, 8);
            $denominatorReduced = rand($numeratorReduced + 1, 12);
            
            $numerator = $numeratorReduced * $gcdValue;
            $denominator = $denominatorReduced * $gcdValue;
            
            $tasks[] = [
                'type' => 'reduce',
                'numerator' => $numerator,
                'denominator' => $denominator,
                'answer' => "$numeratorReduced/$denominatorReduced"
            ];
        }

        // 3 задания на сравнение с одинаковыми знаменателями
        for ($i = 0; $i < 3; $i++) {
            $denominator = rand(5, 15);
            $num1 = rand(1, $denominator - 1);
            $num2 = rand(1, $denominator - 1);
            while ($num1 == $num2) {
                $num2 = rand(1, $denominator - 1);
            }
            
            $answer = $num1 > $num2 ? '>' : ($num1 < $num2 ? '<' : '=');
            
            $tasks[] = [
                'type' => 'compare_same_denom',
                'numerator1' => $num1,
                'denominator1' => $denominator,
                'numerator2' => $num2,
                'denominator2' => $denominator,
                'answer' => $answer
            ];
        }

        // 3 задания на сравнение с разными знаменателями
        for ($i = 0; $i < 3; $i++) {
            $denominators = [2, 3, 4, 5, 6, 8, 10, 12];
            $denom1 = $denominators[array_rand($denominators)];
            $denom2Keys = array_keys(array_filter($denominators, fn($d) => $d != $denom1));
            $denom2 = $denominators[$denom2Keys[array_rand($denom2Keys)]];
            
            $num1 = rand(1, $denom1 - 1);
            $num2 = rand(1, $denom2 - 1);
            
            $frac1 = $num1 / $denom1;
            $frac2 = $num2 / $denom2;
            
            $answer = $frac1 > $frac2 ? '>' : ($frac1 < $frac2 ? '<' : '=');
            
            $tasks[] = [
                'type' => 'compare_diff_denom',
                'numerator1' => $num1,
                'denominator1' => $denom1,
                'numerator2' => $num2,
                'denominator2' => $denom2,
                'answer' => $answer
            ];
        }

        // 1 задание повышенной сложности
        $gcdLarge = [6, 8, 9, 12, 15][array_rand([6, 8, 9, 12, 15])];
        $numBase = rand(3, 10);
        $denomBase = rand($numBase + 2, 15);
        
        $numeratorLarge = $numBase * $gcdLarge;
        $denominatorLarge = $denomBase * $gcdLarge;
        
        $tasks[] = [
            'type' => 'reduce_hard',
            'numerator' => $numeratorLarge,
            'denominator' => $denominatorLarge,
            'answer' => "$numBase/$denomBase",
            'difficulty' => 'hard',
            'points' => 2
        ];
        
        return $tasks;
    }

    /**
     * Проверка ответов и выставление оценки
     */
    public function checkAnswers($submittedAnswers)
    {
        $tasks = $this->tasks_data;
        $correct = 0;
        $totalPoints = 0;
        $earnedPoints = 0;
        
        foreach ($tasks as $i => $task) {
            $userAnswer = trim($submittedAnswers[$i] ?? '');
            $correctAnswer = trim($task['answer']);
            
            $taskPoints = $task['points'] ?? 1;
            $totalPoints += $taskPoints;
            
            $isCorrect = false;
            
            if (in_array($task['type'], ['compare_same_denom', 'compare_diff_denom'])) {
                $userAnswer = str_replace(' ', '', $userAnswer);
                $correctAnswer = str_replace(' ', '', $correctAnswer);
                $isCorrect = $userAnswer === $correctAnswer;
            } else {
                $isCorrect = strtolower($userAnswer) === strtolower($correctAnswer);
            }
            
            if ($isCorrect) {
                $correct++;
                $earnedPoints += $taskPoints;
            }
        }
        
        $this->correct_count = $correct;
        $this->answers = $submittedAnswers;
        $this->submitted_at = now();

        // Выставление оценки по 7-бальной системе
        $percentage = $totalPoints > 0 ? ($earnedPoints / $totalPoints) * 100 : 0;
        
        if ($percentage >= 95) $this->score = 7;
        elseif ($percentage >= 85) $this->score = 6;
        elseif ($percentage >= 75) $this->score = 5;
        elseif ($percentage >= 65) $this->score = 4;
        elseif ($percentage >= 50) $this->score = 3;
        elseif ($percentage >= 35) $this->score = 2;
        else $this->score = 1;

        $this->save();
        return $this->score;
    }
}
