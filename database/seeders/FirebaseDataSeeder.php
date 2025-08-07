<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FirebaseUser;
use App\Models\FirebaseSubject;
use App\Models\FirebaseSchedule;
use App\Models\FirebaseAttendanceSession;
use App\Models\FirebaseAttendance;
use Illuminate\Support\Facades\Hash;

class FirebaseDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "🌱 Seeding Firebase with sample data...\n\n";

        // Create sample users
        $this->createSampleUsers();
        
        // Create sample subjects
        $this->createSampleSubjects();
        
        // Create sample schedules
        $this->createSampleSchedules();
        
        // Create sample attendance sessions
        $this->createSampleAttendanceSessions();
        
        // Create sample attendances
        $this->createSampleAttendances();
        
        echo "✅ Sample data seeded successfully!\n";
    }

    private function createSampleUsers()
    {
        echo "👥 Creating sample users...\n";
        
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'password' => Hash::make('password123'),
                'role' => 'teacher',
                'department' => 'Computer Science',
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'password' => Hash::make('password123'),
                'role' => 'teacher',
                'department' => 'Mathematics',
            ],
            [
                'name' => 'Alice Johnson',
                'email' => 'alice.johnson@student.com',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'student_id' => '2024-001',
                'department' => 'Computer Science',
                'year_level' => '2nd Year',
                'section' => 'A',
                'student_type' => 'Regular',
            ],
            [
                'name' => 'Bob Wilson',
                'email' => 'bob.wilson@student.com',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'student_id' => '2024-002',
                'department' => 'Computer Science',
                'year_level' => '2nd Year',
                'section' => 'A',
                'student_type' => 'Regular',
            ],
            [
                'name' => 'Charlie Brown',
                'email' => 'charlie.brown@student.com',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'student_id' => '2024-003',
                'department' => 'Mathematics',
                'year_level' => '1st Year',
                'section' => 'B',
                'student_type' => 'Regular',
            ],
        ];

        foreach ($users as $userData) {
            $user = new FirebaseUser($userData);
            if ($user->save()) {
                echo "  ✓ Created user: {$userData['name']}\n";
            }
        }
        
        echo "  📊 Created " . count($users) . " users\n\n";
    }

    private function createSampleSubjects()
    {
        echo "📚 Creating sample subjects...\n";
        
        $subjects = [
            [
                'name' => 'Web Development',
                'description' => 'Introduction to web development using modern technologies',
                'teacher_id' => '1', // Assuming first teacher
                'code' => 'CS101',
                'archived' => false,
            ],
            [
                'name' => 'Database Management',
                'description' => 'Database design and management principles',
                'teacher_id' => '1',
                'code' => 'CS102',
                'archived' => false,
            ],
            [
                'name' => 'Calculus I',
                'description' => 'Introduction to calculus and mathematical analysis',
                'teacher_id' => '2', // Assuming second teacher
                'code' => 'MATH101',
                'archived' => false,
            ],
        ];

        foreach ($subjects as $subjectData) {
            $subject = new FirebaseSubject($subjectData);
            if ($subject->save()) {
                echo "  ✓ Created subject: {$subjectData['name']}\n";
            }
        }
        
        echo "  📊 Created " . count($subjects) . " subjects\n\n";
    }

    private function createSampleSchedules()
    {
        echo "📅 Creating sample schedules...\n";
        
        $schedules = [
            [
                'subject_id' => '1', // Web Development
                'day' => 'Monday',
                'start_time' => '09:00:00',
                'end_time' => '10:30:00',
                'type' => 'Lecture',
                'room' => 'Room 101',
            ],
            [
                'subject_id' => '1',
                'day' => 'Wednesday',
                'start_time' => '14:00:00',
                'end_time' => '15:30:00',
                'type' => 'Laboratory',
                'room' => 'Computer Lab 1',
            ],
            [
                'subject_id' => '2', // Database Management
                'day' => 'Tuesday',
                'start_time' => '10:00:00',
                'end_time' => '11:30:00',
                'type' => 'Lecture',
                'room' => 'Room 102',
            ],
            [
                'subject_id' => '3', // Calculus I
                'day' => 'Thursday',
                'start_time' => '13:00:00',
                'end_time' => '14:30:00',
                'type' => 'Lecture',
                'room' => 'Room 201',
            ],
        ];

        foreach ($schedules as $scheduleData) {
            $schedule = new FirebaseSchedule($scheduleData);
            if ($schedule->save()) {
                echo "  ✓ Created schedule for subject ID: {$scheduleData['subject_id']}\n";
            }
        }
        
        echo "  📊 Created " . count($schedules) . " schedules\n\n";
    }

    private function createSampleAttendanceSessions()
    {
        echo "⏰ Creating sample attendance sessions...\n";
        
        $sessions = [
            [
                'subject_id' => '1', // Web Development
                'name' => 'Web Development - Week 1',
                'section' => 'A',
                'code' => 'WEB001',
                'start_time' => now()->subDays(2)->setTime(9, 0),
                'scheduled_start_time' => now()->subDays(2)->setTime(9, 0),
                'scheduled_end_time' => now()->subDays(2)->setTime(10, 30),
                'end_time' => now()->subDays(2)->setTime(10, 30),
                'is_active' => false,
                'notes' => 'Introduction to HTML and CSS',
                'grace_period_minutes' => 15,
            ],
            [
                'subject_id' => '1',
                'name' => 'Web Development - Week 2',
                'section' => 'A',
                'code' => 'WEB002',
                'start_time' => now()->setTime(9, 0),
                'scheduled_start_time' => now()->setTime(9, 0),
                'scheduled_end_time' => now()->setTime(10, 30),
                'end_time' => null,
                'is_active' => true,
                'notes' => 'JavaScript fundamentals',
                'grace_period_minutes' => 15,
            ],
            [
                'subject_id' => '2', // Database Management
                'name' => 'Database Management - Week 1',
                'section' => 'A',
                'code' => 'DB001',
                'start_time' => now()->subDays(1)->setTime(10, 0),
                'scheduled_start_time' => now()->subDays(1)->setTime(10, 0),
                'scheduled_end_time' => now()->subDays(1)->setTime(11, 30),
                'end_time' => now()->subDays(1)->setTime(11, 30),
                'is_active' => false,
                'notes' => 'Introduction to SQL',
                'grace_period_minutes' => 15,
            ],
        ];

        foreach ($sessions as $sessionData) {
            $session = new FirebaseAttendanceSession($sessionData);
            if ($session->save()) {
                echo "  ✓ Created session: {$sessionData['name']}\n";
            }
        }
        
        echo "  📊 Created " . count($sessions) . " attendance sessions\n\n";
    }

    private function createSampleAttendances()
    {
        echo "✅ Creating sample attendances...\n";
        
        $attendances = [
            [
                'user_id' => '3', // Alice Johnson
                'attendance_session_id' => '1', // Web Development Week 1
                'subject_id' => '1',
                'check_in_time' => now()->subDays(2)->setTime(9, 5),
                'pc_number' => 'PC001',
                'ip_address' => '192.168.1.100',
                'notes' => 'Present on time',
                'status' => 'present',
            ],
            [
                'user_id' => '4', // Bob Wilson
                'attendance_session_id' => '1',
                'subject_id' => '1',
                'check_in_time' => now()->subDays(2)->setTime(9, 20),
                'pc_number' => 'PC002',
                'ip_address' => '192.168.1.101',
                'notes' => 'Late arrival',
                'status' => 'late',
            ],
            [
                'user_id' => '3', // Alice Johnson
                'attendance_session_id' => '3', // Database Management Week 1
                'subject_id' => '2',
                'check_in_time' => now()->subDays(1)->setTime(10, 2),
                'pc_number' => 'PC001',
                'ip_address' => '192.168.1.100',
                'notes' => 'Present on time',
                'status' => 'present',
            ],
        ];

        foreach ($attendances as $attendanceData) {
            $attendance = new FirebaseAttendance($attendanceData);
            if ($attendance->save()) {
                echo "  ✓ Created attendance for user ID: {$attendanceData['user_id']}\n";
            }
        }
        
        echo "  📊 Created " . count($attendances) . " attendances\n\n";
    }
} 