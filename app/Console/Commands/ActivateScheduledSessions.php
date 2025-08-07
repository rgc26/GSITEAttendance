<?php

namespace App\Console\Commands;

use App\Models\AttendanceSession;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ActivateScheduledSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sessions:activate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activate scheduled attendance sessions when their start time arrives';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for scheduled sessions to activate...');

        // Find sessions that should be activated (scheduled start time has passed but not yet activated)
        $sessionsToActivate = AttendanceSession::whereNotNull('scheduled_start_time')
            ->where('scheduled_start_time', '<=', now())
            ->where('is_active', false)
            ->whereNull('start_time')
            ->get();

        if ($sessionsToActivate->isEmpty()) {
            $this->info('No sessions to activate at this time.');
            return 0;
        }

        $activatedCount = 0;

        foreach ($sessionsToActivate as $session) {
            try {
                // Check if there's already an active session for this subject
                $activeSession = AttendanceSession::where('subject_id', $session->subject_id)
                    ->where('is_active', true)
                    ->where('id', '!=', $session->id)
                    ->first();

                if ($activeSession) {
                    $this->warn("Skipping session {$session->id} - there's already an active session for subject {$session->subject_id}");
                    continue;
                }

                // Activate the session
                $session->update([
                    'start_time' => now(),
                    'is_active' => true,
                ]);

                $this->info("Activated session: {$session->name} (ID: {$session->id}) for subject: {$session->subject->name}");
                $activatedCount++;

                Log::info("Scheduled session activated", [
                    'session_id' => $session->id,
                    'subject_id' => $session->subject_id,
                    'subject_name' => $session->subject->name,
                    'scheduled_start_time' => $session->scheduled_start_time,
                    'activated_at' => now(),
                ]);

            } catch (\Exception $e) {
                $this->error("Failed to activate session {$session->id}: " . $e->getMessage());
                Log::error("Failed to activate scheduled session", [
                    'session_id' => $session->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Successfully activated {$activatedCount} session(s).");
        return 0;
    }
}
