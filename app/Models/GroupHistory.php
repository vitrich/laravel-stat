<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupHistory extends Model
{
    use HasFactory;

    protected $table = 'group_histories';

    protected $fillable = [
        'student_id',
        'group_id',
        'transfer_date',
        'reason',
    ];

    protected $casts = [
        'transfer_date' => 'date',
    ];

    /**
     * Студент
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Группа
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
