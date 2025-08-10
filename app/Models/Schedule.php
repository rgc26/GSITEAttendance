<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'day',
        'schedule_date',
        'start_time',
        'end_time',
        'type',
        'room',
        'section',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'schedule_date' => 'date',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
} 