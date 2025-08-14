<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendance;
use App\Models\AttendanceSession;
use Carbon\Carbon;

class CheckAttendanceStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:check-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check attendance statuses for debugging';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking attendance statuses...');
        
        // Check Aug 14 sessions
        $sessions = AttendanceSession::whereDate('scheduled_start_time', '2025-08-14')->get();
        
        foreach ($sessions as $session) {
            $this->info("\nSession: {$session->name}");
            $this->info("Scheduled Start: {$session->scheduled_start_time}");
            $this->info("Grace Period: {$session->grace_period_minutes} minutes");
            
            $gracePeriodEnd = $session->scheduled_start_time->copy()->addMinutes($session->grace_period_minutes);
            $this->info("Grace Period End: {$gracePeriodEnd}");
            
            $attendances = $session->attendances()->with('user')->get();
            
            foreach ($attendances as $attendance) {
                $checkInTime = Carbon::parse($attendance->check_in_time);
                $status = $attendance->status;
                
                // Calculate what the status should be
                $expectedStatus = 'present';
                if ($checkInTime > $gracePeriodEnd) {
                    if ($session->scheduled_end_time && $checkInTime <= $session->scheduled_end_time) {
                        $expectedStatus = 'late';
                    } else {
                        $expectedStatus = 'absent';
                    }
                }
                
                $this->info("  {$attendance->user->name}:");
                $this->info("    Check-in: {$checkInTime}");
                $this->info("    Current Status: {$status}");
                $this->info("    Expected Status: {$expectedStatus}");
                $this->info("    Status Match: " . ($status === $expectedStatus ? 'YES' : 'NO'));
            }
        }
        
        return 0;
    }
}
