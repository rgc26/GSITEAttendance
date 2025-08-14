<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Force update attendance statuses to 'late' for students who checked in after grace period
        // This is a more direct approach to fix the issue
        
        // Get all attendance records
        $attendances = DB::table('attendances')->get();
        
        foreach ($attendances as $attendance) {
            // Get the session for this attendance
            $session = DB::table('attendance_sessions')->where('id', $attendance->attendance_session_id)->first();
            
            if ($session && $session->scheduled_start_time) {
                $checkInTime = Carbon::parse($attendance->check_in_time);
                $sessionStartTime = Carbon::parse($session->scheduled_start_time);
                $gracePeriodEnd = $sessionStartTime->copy()->addMinutes($session->grace_period_minutes ?? 15);
                
                // If check-in time is after grace period, mark as late
                if ($checkInTime > $gracePeriodEnd) {
                    // Check if session has end time and if check-in is within session time
                    if ($session->scheduled_end_time) {
                        $sessionEndTime = Carbon::parse($session->scheduled_end_time);
                        
                        if ($checkInTime <= $sessionEndTime) {
                            // Late but within session time
                            DB::table('attendances')
                                ->where('id', $attendance->id)
                                ->update(['status' => 'late']);
                            
                            echo "Updated attendance {$attendance->id} to LATE\n";
                        }
                    } else {
                        // No end time, mark as late
                        DB::table('attendances')
                            ->where('id', $attendance->id)
                            ->update(['status' => 'late']);
                        
                        echo "Updated attendance {$attendance->id} to LATE\n";
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
