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
        // Fix the specific Aug 14 session that's showing wrong time
        // The issue is likely that the time was stored in UTC instead of Asia/Manila
        
        // Find sessions created on Aug 14, 2025
        $sessions = DB::table('attendance_sessions')
            ->whereDate('scheduled_start_time', '2025-08-14')
            ->get();
        
        foreach ($sessions as $session) {
            // Convert the stored time to Asia/Manila timezone
            $storedTime = Carbon::parse($session->scheduled_start_time);
            
            // If the time is showing as 18:44 (6:44 PM), it was likely stored as UTC
            // We need to convert it to the correct Manila time
            if ($storedTime->hour >= 18) {
                // This time was likely stored in UTC, convert to Manila time
                $manilaTime = $storedTime->subHours(8); // UTC+8 for Manila
                
                DB::table('attendance_sessions')
                    ->where('id', $session->id)
                    ->update([
                        'scheduled_start_time' => $manilaTime->format('Y-m-d H:i:s'),
                        'scheduled_end_time' => $session->scheduled_end_time ? 
                            Carbon::parse($session->scheduled_end_time)->subHours(8)->format('Y-m-d H:i:s') : null,
                    ]);
            }
        }
        
        // Also fix any attendance records with wrong check-in times
        $attendances = DB::table('attendances')
            ->whereDate('check_in_time', '2025-08-14')
            ->get();
        
        foreach ($attendances as $attendance) {
            $storedTime = Carbon::parse($attendance->check_in_time);
            
            // If check-in time is showing as 18:44 (6:44 PM), convert to Manila time
            if ($storedTime->hour >= 18) {
                $manilaTime = $storedTime->subHours(8); // UTC+8 for Manila
                
                DB::table('attendances')
                    ->where('id', $attendance->id)
                    ->update([
                        'check_in_time' => $manilaTime->format('Y-m-d H:i:s'),
                    ]);
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
