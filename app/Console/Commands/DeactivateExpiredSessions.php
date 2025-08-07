<?php

namespace App\Console\Commands;

use App\Models\AttendanceSession;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeactivateExpiredSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sessions:deactivate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate attendance sessions that have passed their scheduled end time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expired sessions to deactivate...');

        // Find sessions that should be deactivated (scheduled end time has passed and they're still active)
        $sessionsToDeactivate = AttendanceSession::whereNotNull('scheduled_end_time')
            ->where('scheduled_end_time', '<=', now())
            ->where('is_active', true)
            ->get();

        if ($sessionsToDeactivate->isEmpty()) {
            $this->info('No sessions to deactivate at this time.');
            return 0;
        }

        $deactivatedCount = 0;

        foreach ($sessionsToDeactivate as $session) {
            try {
                // Deactivate the session
                $session->update([
                    'end_time' => now(),
                    'is_active' => false,
                ]);

                $this->info("Deactivated session: {$session->name} (ID: {$session->id}) for subject: {$session->subject->name}");
                $deactivatedCount++;

                Log::info("Scheduled session deactivated", [
                    'session_id' => $session->id,
                    'subject_id' => $session->subject_id,
                    'subject_name' => $session->subject->name,
                    'scheduled_end_time' => $session->scheduled_end_time,
                    'deactivated_at' => now(),
                ]);

            } catch (\Exception $e) {
                $this->error("Failed to deactivate session {$session->id}: " . $e->getMessage());
                Log::error("Failed to deactivate scheduled session", [
                    'session_id' => $session->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Successfully deactivated {$deactivatedCount} session(s).");
        return 0;
    }
}
