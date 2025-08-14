<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Attendance;
use App\Models\AttendanceSession;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix existing attendance records that should be marked as late
        $attendances = Attendance::with('attendanceSession')->get();
        
        foreach ($attendances as $attendance) {
            if ($attendance->attendanceSession && $attendance->attendanceSession->scheduled_start_time) {
                $gracePeriodEnd = $attendance->attendanceSession->scheduled_start_time->copy()->addMinutes($attendance->attendanceSession->grace_period_minutes ?? 15);
                
                // If check-in time is after grace period but before session end, mark as late
                if ($attendance->check_in_time > $gracePeriodEnd && 
                    $attendance->attendanceSession->scheduled_end_time && 
                    $attendance->check_in_time <= $attendance->attendanceSession->scheduled_end_time) {
                    
                    // Only update if current status is 'present' (to avoid overriding manually set 'absent' statuses)
                    if ($attendance->status === 'present') {
                        DB::table('attendances')
                            ->where('id', $attendance->id)
                            ->update(['status' => 'late']);
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration fixes data, so no rollback needed
    }
};
