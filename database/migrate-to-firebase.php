<?php

/**
 * Migration Script: MySQL to Firestore
 * 
 * This script helps migrate your existing MySQL data to Firestore.
 * Run this script after setting up your Firebase configuration.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use App\Models\User;
use App\Models\Subject;
use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\Schedule;

use App\Models\FirebaseUser;
use App\Models\FirebaseSubject;
use App\Models\FirebaseAttendance;
use App\Models\FirebaseAttendanceSession;
use App\Models\FirebaseSchedule;

use Illuminate\Support\Facades\DB;

class MySQLToFirestoreMigration
{
    protected $firebaseService;
    protected $migrationLog = [];

    public function __construct()
    {
        $this->firebaseService = app(\App\Services\FirebaseService::class);
    }

    public function migrate()
    {
        echo "🚀 Starting MySQL to Firestore migration...\n\n";

        try {
            // Check database connection
            $this->checkDatabaseConnection();
            
            // Migrate Users
            $this->migrateUsers();
            
            // Migrate Subjects
            $this->migrateSubjects();
            
            // Migrate Schedules
            $this->migrateSchedules();
            
            // Migrate Attendance Sessions
            $this->migrateAttendanceSessions();
            
            // Migrate Attendances
            $this->migrateAttendances();
            
            // Save migration log
            $this->saveMigrationLog();
            
            echo "✅ Migration completed successfully!\n";
            echo "📊 Migration Summary:\n";
            foreach ($this->migrationLog as $table => $count) {
                echo "   - {$table}: {$count} records\n";
            }
            
        } catch (Exception $e) {
            echo "❌ Migration failed: " . $e->getMessage() . "\n";
            echo "🔧 Error details: " . $e->getTraceAsString() . "\n";
        }
    }

    private function checkDatabaseConnection()
    {
        echo "🔍 Checking database connection...\n";
        
        try {
            DB::connection()->getPdo();
            echo "   ✅ Database connection successful\n";
        } catch (Exception $e) {
            echo "   ⚠️  Database connection failed: " . $e->getMessage() . "\n";
            echo "   ℹ️  This might be normal if you're migrating from a backup or CSV file\n";
            
            // Ask user if they want to continue with manual data entry
            echo "\n🤔 Would you like to:\n";
            echo "   1. Start with empty Firestore collections (recommended for new setup)\n";
            echo "   2. Import data from CSV files\n";
            echo "   3. Exit and fix database connection\n";
            
            $choice = readline("Enter your choice (1-3): ");
            
            if ($choice == '1') {
                echo "✅ Proceeding with empty collections...\n";
                return;
            } elseif ($choice == '2') {
                $this->importFromCSV();
                return;
            } else {
                throw new Exception("Migration cancelled by user");
            }
        }
    }

    private function migrateUsers()
    {
        echo "📝 Migrating users...\n";
        
        try {
            $users = User::all();
            $count = 0;
            
            foreach ($users as $user) {
                $firebaseUser = new FirebaseUser([
                    'name' => $user->name,
                    'email' => $user->email,
                    'password' => $user->password, // Note: You might want to re-hash passwords
                    'role' => $user->role,
                    'student_id' => $user->student_id,
                    'department' => $user->department,
                    'year_level' => $user->year_level,
                    'section' => $user->section,
                    'profile_picture' => $user->profile_picture,
                    'student_type' => $user->student_type,
                    'email_verified_at' => $user->email_verified_at,
                    'remember_token' => $user->remember_token,
                ]);
                
                if ($firebaseUser->save()) {
                    $count++;
                    echo "  ✓ Migrated user: {$user->name}\n";
                }
            }
            
            $this->migrationLog['users'] = $count;
            echo "  📊 Migrated {$count} users\n\n";
            
        } catch (Exception $e) {
            echo "  ⚠️  Could not migrate users: " . $e->getMessage() . "\n";
            $this->migrationLog['users'] = 0;
        }
    }

    private function migrateSubjects()
    {
        echo "📚 Migrating subjects...\n";
        
        try {
            $subjects = Subject::all();
            $count = 0;
            
            foreach ($subjects as $subject) {
                $firebaseSubject = new FirebaseSubject([
                    'name' => $subject->name,
                    'description' => $subject->description,
                    'teacher_id' => $subject->teacher_id,
                    'code' => $subject->code,
                    'archived' => $subject->archived,
                ]);
                
                if ($firebaseSubject->save()) {
                    $count++;
                    echo "  ✓ Migrated subject: {$subject->name}\n";
                }
            }
            
            $this->migrationLog['subjects'] = $count;
            echo "  📊 Migrated {$count} subjects\n\n";
            
        } catch (Exception $e) {
            echo "  ⚠️  Could not migrate subjects: " . $e->getMessage() . "\n";
            $this->migrationLog['subjects'] = 0;
        }
    }

    private function migrateSchedules()
    {
        echo "📅 Migrating schedules...\n";
        
        try {
            $schedules = Schedule::all();
            $count = 0;
            
            foreach ($schedules as $schedule) {
                $firebaseSchedule = new FirebaseSchedule([
                    'subject_id' => $schedule->subject_id,
                    'day' => $schedule->day,
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                    'type' => $schedule->type,
                    'room' => $schedule->room,
                ]);
                
                if ($firebaseSchedule->save()) {
                    $count++;
                    echo "  ✓ Migrated schedule for subject ID: {$schedule->subject_id}\n";
                }
            }
            
            $this->migrationLog['schedules'] = $count;
            echo "  📊 Migrated {$count} schedules\n\n";
            
        } catch (Exception $e) {
            echo "  ⚠️  Could not migrate schedules: " . $e->getMessage() . "\n";
            $this->migrationLog['schedules'] = 0;
        }
    }

    private function migrateAttendanceSessions()
    {
        echo "⏰ Migrating attendance sessions...\n";
        
        try {
            $sessions = AttendanceSession::all();
            $count = 0;
            
            foreach ($sessions as $session) {
                $firebaseSession = new FirebaseAttendanceSession([
                    'subject_id' => $session->subject_id,
                    'name' => $session->name,
                    'section' => $session->section,
                    'code' => $session->code,
                    'start_time' => $session->start_time,
                    'scheduled_start_time' => $session->scheduled_start_time,
                    'scheduled_end_time' => $session->scheduled_end_time,
                    'end_time' => $session->end_time,
                    'is_active' => $session->is_active,
                    'notes' => $session->notes,
                    'grace_period_minutes' => $session->grace_period_minutes,
                ]);
                
                if ($firebaseSession->save()) {
                    $count++;
                    echo "  ✓ Migrated session: {$session->name}\n";
                }
            }
            
            $this->migrationLog['attendance_sessions'] = $count;
            echo "  📊 Migrated {$count} attendance sessions\n\n";
            
        } catch (Exception $e) {
            echo "  ⚠️  Could not migrate attendance sessions: " . $e->getMessage() . "\n";
            $this->migrationLog['attendance_sessions'] = 0;
        }
    }

    private function migrateAttendances()
    {
        echo "✅ Migrating attendances...\n";
        
        try {
            $attendances = Attendance::all();
            $count = 0;
            
            foreach ($attendances as $attendance) {
                $firebaseAttendance = new FirebaseAttendance([
                    'user_id' => $attendance->user_id,
                    'attendance_session_id' => $attendance->attendance_session_id,
                    'subject_id' => $attendance->subject_id,
                    'check_in_time' => $attendance->check_in_time,
                    'pc_number' => $attendance->pc_number,
                    'ip_address' => $attendance->ip_address,
                    'notes' => $attendance->notes,
                    'status' => $attendance->status,
                ]);
                
                if ($firebaseAttendance->save()) {
                    $count++;
                    echo "  ✓ Migrated attendance for user ID: {$attendance->user_id}\n";
                }
            }
            
            $this->migrationLog['attendances'] = $count;
            echo "  📊 Migrated {$count} attendances\n\n";
            
        } catch (Exception $e) {
            echo "  ⚠️  Could not migrate attendances: " . $e->getMessage() . "\n";
            $this->migrationLog['attendances'] = 0;
        }
    }

    private function importFromCSV()
    {
        echo "📁 CSV Import Mode\n";
        echo "Please place your CSV files in the database/csv/ directory:\n";
        echo "  - users.csv\n";
        echo "  - subjects.csv\n";
        echo "  - schedules.csv\n";
        echo "  - attendance_sessions.csv\n";
        echo "  - attendances.csv\n\n";
        
        // Create CSV directory if it doesn't exist
        $csvDir = __DIR__ . '/csv';
        if (!is_dir($csvDir)) {
            mkdir($csvDir, 0755, true);
        }
        
        echo "📂 CSV directory created: {$csvDir}\n";
        echo "Please add your CSV files and run the migration again.\n";
    }

    private function saveMigrationLog()
    {
        $logFile = __DIR__ . '/migration_log.json';
        $logData = [
            'timestamp' => now()->toISOString(),
            'summary' => $this->migrationLog,
            'total_records' => array_sum($this->migrationLog)
        ];
        
        file_put_contents($logFile, json_encode($logData, JSON_PRETTY_PRINT));
        echo "📝 Migration log saved to: {$logFile}\n";
    }
}

// Run migration if this file is executed directly
if (php_sapi_name() === 'cli') {
    // Bootstrap Laravel
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    $migration = new MySQLToFirestoreMigration();
    $migration->migrate();
} 