<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'schedule_id',
        'session_type',
        'name',
        'section',
        'code',
        'start_time',
        'scheduled_start_time',
        'scheduled_end_time',
        'end_time',
        'is_active',
        'notes',
        'grace_period_minutes',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'scheduled_start_time' => 'datetime',
        'scheduled_end_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean',
        'grace_period_minutes' => 'integer',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'attendance_session_id');
    }

    public function generateCode()
    {
        return strtoupper(substr(md5(uniqid()), 0, 6));
    }

    /**
     * Check if attendance is on time, late, or absent based on scheduled time
     */
    public function getAttendanceStatus($checkInTime = null)
    {
        if (!$this->scheduled_start_time) {
            return 'present'; // No scheduled time, always present
        }

        $checkInTime = $checkInTime ?: now()->setTimezone('Asia/Manila');
        
        // Calculate grace period end (15 minutes after start time) - use a copy to avoid modifying original
        $gracePeriodEnd = $this->scheduled_start_time->copy()->addMinutes($this->grace_period_minutes);

        if ($checkInTime <= $gracePeriodEnd) {
            return 'present'; // On time (within grace period)
        } elseif ($checkInTime <= $this->scheduled_end_time) {
            return 'late'; // Late but still within session time
        } else {
            return 'absent'; // After session ended
        }
    }

    /**
     * Get the grace period end time
     */
    public function getGracePeriodEndTime()
    {
        if (!$this->scheduled_start_time) {
            return null;
        }
        
        // Grace period is 15 minutes after start time - use a copy to avoid modifying original
        return $this->scheduled_start_time->copy()->addMinutes($this->grace_period_minutes);
    }

    /**
     * Check if the session is currently active (within scheduled time + grace period)
     */
    public function isCurrentlyActive()
    {
        if (!$this->scheduled_start_time || !$this->scheduled_end_time) {
            return $this->is_active;
        }

        $now = now();
        $gracePeriodEnd = $this->scheduled_start_time->copy()->addMinutes($this->grace_period_minutes);
        
        return $this->is_active && 
               $now >= $this->scheduled_start_time && 
               $now <= $this->scheduled_end_time->addMinutes($this->grace_period_minutes);
    }
} 