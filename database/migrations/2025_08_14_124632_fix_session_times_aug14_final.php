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
        // Fix the session scheduled times to ensure they are in Asia/Manila timezone
        // The issue is that session times are still showing as 18:40 instead of 10:40 AM
        
        $sessions = DB::table('attendance_sessions')
            ->whereDate('scheduled_start_time', '2025-08-14')
            ->get();
        
        foreach ($sessions as $session) {
            $storedTime = Carbon::parse($session->scheduled_start_time);
            
            // If the time is showing as 18:40 (6:40 PM), it needs to be converted to Manila time
            if ($storedTime->hour >= 18) {
                // Convert from UTC to Manila time (UTC+8)
                $manilaTime = $storedTime->subHours(8);
                
                DB::table('attendance_sessions')
                    ->where('id', $session->id)
                    ->update([
                        'scheduled_start_time' => $manilaTime->format('Y-m-d H:i:s'),
                    ]);
                
                echo "Fixed session {$session->id} time: {$storedTime} -> {$manilaTime}\n";
            }
            
            // Also fix scheduled_end_time if it exists
            if ($session->scheduled_end_time) {
                $storedEndTime = Carbon::parse($session->scheduled_end_time);
                
                if ($storedEndTime->hour >= 18) {
                    $manilaEndTime = $storedEndTime->subHours(8);
                    
                    DB::table('attendance_sessions')
                        ->where('id', $session->id)
                        ->update([
                            'scheduled_end_time' => $manilaEndTime->format('Y-m-d H:i:s'),
                        ]);
                    
                    echo "Fixed session {$session->id} end time: {$storedEndTime} -> {$manilaEndTime}\n";
                }
            }
        }
        
        // Also fix any other sessions that might have wrong timezone
        $allSessions = DB::table('attendance_sessions')->get();
        
        foreach ($allSessions as $session) {
            if ($session->scheduled_start_time) {
                $storedTime = Carbon::parse($session->scheduled_start_time);
                
                // If time is in the evening (likely UTC), convert to Manila time
                if ($storedTime->hour >= 18) {
                    $manilaTime = $storedTime->subHours(8);
                    
                    DB::table('attendance_sessions')
                        ->where('id', $session->id)
                        ->update([
                            'scheduled_start_time' => $manilaTime->format('Y-m-d H:i:s'),
                        ]);
                    
                    echo "Fixed general session {$session->id} time: {$storedTime} -> {$manilaTime}\n";
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
