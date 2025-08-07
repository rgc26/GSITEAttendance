<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'attendance_session_id',
        'subject_id',
        'check_in_time',
        'pc_number',
        'device_type',
        'attached_image',
        'ip_address',
        'notes',
        'status',
    ];

    protected $casts = [
        'check_in_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendanceSession()
    {
        return $this->belongsTo(AttendanceSession::class, 'attendance_session_id');
    }

    public function session()
    {
        return $this->belongsTo(AttendanceSession::class, 'attendance_session_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
} 