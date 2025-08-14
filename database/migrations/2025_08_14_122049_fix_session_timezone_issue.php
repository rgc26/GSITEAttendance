<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\AttendanceSession;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix timezone issue for existing sessions
        $sessions = AttendanceSession::whereNotNull('scheduled_start_time')->get();
        
        foreach ($sessions as $session) {
            // If the time is stored in UTC or wrong timezone, convert it to Asia/Manila
            $manilaTime = $session->scheduled_start_time->setTimezone('Asia/Manila');
            
            // Update the session with the correct Manila time
            DB::table('attendance_sessions')
                ->where('id', $session->id)
                ->update([
                    'scheduled_start_time' => $manilaTime->format('Y-m-d H:i:s'),
                    'scheduled_end_time' => $session->scheduled_end_time ? $session->scheduled_end_time->setTimezone('Asia/Manila')->format('Y-m-d H:i:s') : null,
                ]);
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
