<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GroupHistory;
use App\Models\Group;
use App\Models\Student;

class StatisticsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'teacher']);
    }

    /**
     * Статистика переходов между группами
     */
    public function index(Request $request)
    {
        // Автоматическое определение всех уникальных дат из БД
        $keyDates = GroupHistory::selectRaw('DISTINCT transfer_date')
            ->orderBy('transfer_date')
            ->pluck('transfer_date')
            ->toArray();
        
        if (empty($keyDates)) {
            $keyDates = [
                '2025-09-01',
                '2025-10-15',
                '2025-12-16',
                '2026-01-12'
            ];
        }
        
        $allHistory = GroupHistory::with(['student', 'group'])
            ->orderBy('student_id')
            ->orderBy('transfer_date')
            ->get();
        
        $studentsHistoryRaw = [];
        $studentsInfo = [];
        
        foreach ($allHistory as $entry) {
            $studentId = $entry->student_id;
            
            if (!isset($studentsHistoryRaw[$studentId])) {
                $studentsHistoryRaw[$studentId] = [];
            }
            
            $studentsHistoryRaw[$studentId][] = [
                'date' => $entry->transfer_date->format('Y-m-d'),
                'group' => (float) $entry->group->number
            ];
            
            if (!isset($studentsInfo[$studentId])) {
                $studentsInfo[$studentId] = [
                    'id' => $studentId,
                    'name' => $entry->student->full_name
                ];
            }
        }
        
        // Дополняем историю промежуточными точками
        $studentsFullHistory = [];
        
        foreach ($studentsHistoryRaw as $studentId => $history) {
            usort($history, function($a, $b) {
                return strcmp($a['date'], $b['date']);
            });
            
            $fullHistory = [];
            $firstDate = $history[0]['date'];
            $currentGroup = $history[0]['group'];
            $historyIndex = 0;
            
            foreach ($keyDates as $keyDate) {
                if ($keyDate < $firstDate) {
                    continue;
                }
                
                foreach ($history as $i => $h) {
                    if ($h['date'] == $keyDate) {
                        $currentGroup = $h['group'];
                        break;
                    } elseif ($h['date'] > $keyDate) {
                        break;
                    }
                }
                
                $fullHistory[] = [
                    'date' => $keyDate,
                    'group' => $currentGroup
                ];
            }
            
            $studentsFullHistory[$studentId] = $fullHistory;
        }
        
        $studentsWithTransitions = [];
        foreach ($studentsFullHistory as $studentId => $history) {
            if (count($history) > 0) {
                $studentsWithTransitions[] = [
                    'id' => $studentId,
                    'name' => $studentsInfo[$studentId]['name'],
                    'history' => $history
                ];
            }
        }
        
        usort($studentsWithTransitions, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });
        
        // Статистика по группам
        $groups = Group::orderBy('number')->get();
        $groupStats = [];
        
        foreach ($groups as $group) {
            $currentCount = Student::where('current_group_id', $group->id)->count();
            $groupStats[] = [
                'group' => $group,
                'current_count' => $currentCount,
                'teacher' => $group->teacher ? $group->teacher->full_name : 'Не назначен'
            ];
        }
        
        // Форматирование дат для отображения
        $datesFormatted = array_map(function($date) {
            return date('d.m.Y', strtotime($date));
        }, $keyDates);
        
        $studentsJson = [];
        foreach ($studentsWithTransitions as $s) {
            $studentsJson[$s['id']] = [
                'name' => $s['name'],
                'history' => $s['history']
            ];
        }
        
        return view('statistics', [
            'students_with_transitions' => $studentsWithTransitions,
            'students_json' => json_encode($studentsJson),
            'group_stats' => $groupStats,
            'key_dates' => $datesFormatted,
            'dates_count' => count($keyDates),
        ]);
    }
}
