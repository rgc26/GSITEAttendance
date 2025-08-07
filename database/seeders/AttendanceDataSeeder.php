<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AttendanceDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first teacher
        $teacher = User::where('role', 'teacher')->first();
        
        if (!$teacher) {
            $teacher = User::create([
                'name' => 'Dr. Sarah Johnson',
                'email' => 'sarah.johnson@university.edu',
                'password' => Hash::make('password123'),
                'role' => 'teacher',
                'department' => 'Computer Science'
            ]);
        }

        // Get or create a subject
        $subject = Subject::where('code', 'BMC301')->first();
        
        if (!$subject) {
            $subject = Subject::create([
                'name' => 'BMC - Business Management',
                'code' => 'BMC301',
                'description' => 'Business Management and Communication',
                'teacher_id' => $teacher->id,
            ]);
        }

        // Create attendance sessions for different sections
        $sessions = [
            [
                'name' => 'Week 1 - Introduction',
                'section' => '301',
                'start_time' => now()->subDays(7),
                'scheduled_start_time' => now()->subDays(7)->setTime(9, 0),
                'scheduled_end_time' => now()->subDays(7)->setTime(10, 30),
                'is_active' => false,
                'grace_period_minutes' => 15,
            ],
            [
                'name' => 'Week 2 - Business Planning',
                'section' => '301',
                'start_time' => now()->subDays(5),
                'scheduled_start_time' => now()->subDays(5)->setTime(9, 0),
                'scheduled_end_time' => now()->subDays(5)->setTime(10, 30),
                'is_active' => false,
                'grace_period_minutes' => 15,
            ],
            [
                'name' => 'Week 3 - Marketing Strategy',
                'section' => '301',
                'start_time' => now()->subDays(3),
                'scheduled_start_time' => now()->subDays(3)->setTime(9, 0),
                'scheduled_end_time' => now()->subDays(3)->setTime(10, 30),
                'is_active' => false,
                'grace_period_minutes' => 15,
            ],
            [
                'name' => 'Week 1 - Introduction',
                'section' => '302',
                'start_time' => now()->subDays(6),
                'scheduled_start_time' => now()->subDays(6)->setTime(14, 0),
                'scheduled_end_time' => now()->subDays(6)->setTime(15, 30),
                'is_active' => false,
                'grace_period_minutes' => 15,
            ],
            [
                'name' => 'Week 2 - Business Planning',
                'section' => '302',
                'start_time' => now()->subDays(4),
                'scheduled_start_time' => now()->subDays(4)->setTime(14, 0),
                'scheduled_end_time' => now()->subDays(4)->setTime(15, 30),
                'is_active' => false,
                'grace_period_minutes' => 15,
            ],
        ];

        foreach ($sessions as $sessionData) {
            $session = AttendanceSession::create([
                'subject_id' => $subject->id,
                'name' => $sessionData['name'],
                'section' => $sessionData['section'],
                'code' => strtoupper(substr(md5(uniqid()), 0, 6)),
                'start_time' => $sessionData['start_time'],
                'scheduled_start_time' => $sessionData['scheduled_start_time'],
                'scheduled_end_time' => $sessionData['scheduled_end_time'],
                'is_active' => $sessionData['is_active'],
                'grace_period_minutes' => $sessionData['grace_period_minutes'],
            ]);

            // Get students from the target section
            $targetSectionStudents = User::where('role', 'student')->where('section', $sessionData['section'])->get();
            
            // Get some irregular students (from other sections)
            $irregularStudents = User::where('role', 'student')->where('section', '!=', $sessionData['section'])->take(2)->get();

            // Create attendances for regular students
            foreach ($targetSectionStudents as $student) {
                $status = rand(1, 10) <= 8 ? 'present' : (rand(1, 10) <= 7 ? 'late' : 'absent');
                $checkInTime = $sessionData['scheduled_start_time'];
                
                if ($status === 'late') {
                    $checkInTime = $sessionData['scheduled_start_time']->addMinutes(rand(16, 30));
                } elseif ($status === 'absent') {
                    continue; // Skip creating attendance for absent students
                }

                Attendance::create([
                    'attendance_session_id' => $session->id,
                    'user_id' => $student->id,
                    'subject_id' => $subject->id,
                    'check_in_time' => $checkInTime,
                    'status' => $status,
                ]);
            }

            // Create attendances for irregular students
            foreach ($irregularStudents as $student) {
                $status = rand(1, 10) <= 6 ? 'present' : 'late';
                $checkInTime = $sessionData['scheduled_start_time'];
                
                if ($status === 'late') {
                    $checkInTime = $sessionData['scheduled_start_time']->addMinutes(rand(16, 25));
                }

                Attendance::create([
                    'attendance_session_id' => $session->id,
                    'user_id' => $student->id,
                    'subject_id' => $subject->id,
                    'check_in_time' => $checkInTime,
                    'status' => $status,
                ]);
            }
        }

        echo "Sample attendance data created successfully!\n";
        echo "Subject: BMC - Business Management (BMC301)\n";
        echo "Sections: 301, 302\n";
        echo "Students: Using existing students\n";
        echo "Sessions: 5 sessions (3 for 301, 2 for 302)\n";
        echo "You can now test the attendance report at: /teacher/subjects/1/report\n";
    }
}
