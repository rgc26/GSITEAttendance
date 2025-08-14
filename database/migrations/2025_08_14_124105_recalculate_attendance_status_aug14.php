<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Attendance;
use App\Models\AttendanceSession;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Recalculate attendance statuses for Aug 14 session based on corrected times
        $sessions = AttendanceSession::whereDate('scheduled_start_time', '2025-08-14')->get();
        
        foreach ($sessions as $session) {
            if (!$session->scheduled_start_time) continue;
            
            // Get all attendances for this session
            $attendances = $session->attendances()->get();
            
            foreach ($attendances as $attendance) {
                $checkInTime = Carbon::parse($attendance->check_in_time);
                $gracePeriodEnd = $session->scheduled_start_time->copy()->addMinutes($session->grace_period_minutes ?? 15);
                
                // Determine correct status based on check-in time vs grace period
                $newStatus = 'present'; // default
                
                if ($checkInTime > $gracePeriodEnd) {
                    if ($session->scheduled_end_time && $checkInTime <= $session->scheduled_end_time) {
                        $newStatus = 'late'; // Late but within session time
                    } else {
                        $newStatus = 'absent'; // After session ended
                    }
                } else {
                    $newStatus = 'present'; // Within grace period
                }
                
                // Only update if status needs to change
                if ($attendance->status !== $newStatus) {
                    DB::table('attendances')
                        ->where('id', $attendance->id)
                        ->update(['status' => $newStatus]);
                    
                    echo "Updated attendance {$attendance->id}: {$attendance->status} -> {$newStatus}\n";
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
