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
        echo "Starting comprehensive time fix...\n";
        
        // Fix ALL session time fields
        $sessions = DB::table('attendance_sessions')->get();
        
        foreach ($sessions as $session) {
            $updates = [];
            
            // Fix scheduled_start_time
            if ($session->scheduled_start_time) {
                $storedTime = Carbon::parse($session->scheduled_start_time);
                if ($storedTime->hour >= 18) {
                    $manilaTime = $storedTime->subHours(8);
                    $updates['scheduled_start_time'] = $manilaTime->format('Y-m-d H:i:s');
                    echo "Fixed session {$session->id} scheduled_start_time: {$storedTime} -> {$manilaTime}\n";
                }
            }
            
            // Fix scheduled_end_time
            if ($session->scheduled_end_time) {
                $storedTime = Carbon::parse($session->scheduled_end_time);
                if ($storedTime->hour >= 18) {
                    $manilaTime = $storedTime->subHours(8);
                    $updates['scheduled_end_time'] = $manilaTime->format('Y-m-d H:i:s');
                    echo "Fixed session {$session->id} scheduled_end_time: {$storedTime} -> {$manilaTime}\n";
                }
            }
            
            // Fix start_time (when session actually started)
            if ($session->start_time) {
                $storedTime = Carbon::parse($session->start_time);
                if ($storedTime->hour >= 18) {
                    $manilaTime = $storedTime->subHours(8);
                    $updates['start_time'] = $manilaTime->format('Y-m-d H:i:s');
                    echo "Fixed session {$session->id} start_time: {$storedTime} -> {$manilaTime}\n";
                }
            }
            
            // Fix end_time (when session actually ended)
            if ($session->end_time) {
                $storedTime = Carbon::parse($session->end_time);
                if ($storedTime->hour >= 18) {
                    $manilaTime = $storedTime->subHours(8);
                    $updates['end_time'] = $manilaTime->format('Y-m-d H:i:s');
                    echo "Fixed session {$session->id} end_time: {$storedTime} -> {$manilaTime}\n";
                }
            }
            
            // Update the session if we have changes
            if (!empty($updates)) {
                DB::table('attendance_sessions')
                    ->where('id', $session->id)
                    ->update($updates);
            }
        }
        
        // Fix ALL attendance check_in_time fields
        echo "\nFixing attendance check-in times...\n";
        $attendances = DB::table('attendances')->get();
        
        foreach ($attendances as $attendance) {
            if ($attendance->check_in_time) {
                $storedTime = Carbon::parse($attendance->check_in_time);
                if ($storedTime->hour >= 18) {
                    $manilaTime = $storedTime->subHours(8);
                    DB::table('attendances')
                        ->where('id', $attendance->id)
                        ->update(['check_in_time' => $manilaTime->format('Y-m-d H:i:s')]);
                    echo "Fixed attendance {$attendance->id} check_in_time: {$storedTime} -> {$manilaTime}\n";
                }
            }
        }
        
        // Also fix any other time fields that might exist
        echo "\nChecking for other time fields...\n";
        
        // Check if there are any other time columns we missed
        $columns = DB::select("PRAGMA table_info(attendance_sessions)");
        foreach ($columns as $column) {
            if (str_contains(strtolower($column->name), 'time') || str_contains(strtolower($column->name), 'date')) {
                echo "Found time-related column: {$column->name}\n";
            }
        }
        
        echo "\nComprehensive time fix completed!\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration fixes data, so no rollback needed
        echo "This migration fixes data and cannot be rolled back.\n";
    }
};
