<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create teachers
        $teachers = [
            [
                'name' => 'Dr. Sarah Johnson',
                'email' => 'sarah.johnson@university.edu',
                'password' => Hash::make('password123'),
                'role' => 'teacher',
                'department' => 'Computer Science',
            ],
            [
                'name' => 'Prof. Michael Chen',
                'email' => 'michael.chen@university.edu',
                'password' => Hash::make('password123'),
                'role' => 'teacher',
                'department' => 'Mathematics',
            ],
            [
                'name' => 'Dr. Emily Rodriguez',
                'email' => 'emily.rodriguez@university.edu',
                'password' => Hash::make('password123'),
                'role' => 'teacher',
                'department' => 'Physics',
            ],
        ];

        foreach ($teachers as $teacherData) {
            User::firstOrCreate(
                ['email' => $teacherData['email']],
                $teacherData
            );
        }

        // Create students for section 301
        $section301Students = [
            [
                'name' => 'Alice Johnson',
                'email' => 'alice.johnson@student.com',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'student_id' => '2024-001',
                'year_level' => '2nd Year',
                'section' => '301',
                'student_type' => 'regular',
            ],
            [
                'name' => 'Bob Wilson',
                'email' => 'bob.wilson@student.com',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'student_id' => '2024-002',
                'year_level' => '2nd Year',
                'section' => '301',
                'student_type' => 'regular',
            ],
            [
                'name' => 'Charlie Brown',
                'email' => 'charlie.brown@student.com',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'student_id' => '2024-003',
                'year_level' => '2nd Year',
                'section' => '301',
                'student_type' => 'regular',
            ],
        ];

        foreach ($section301Students as $studentData) {
            User::firstOrCreate(
                ['email' => $studentData['email']],
                $studentData
            );
        }

        // Create students for section 302
        $section302Students = [
            [
                'name' => 'David Lee',
                'email' => 'david.lee@student.com',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'student_id' => '2024-004',
                'year_level' => '2nd Year',
                'section' => '302',
                'student_type' => 'regular',
            ],
            [
                'name' => 'Eva Martinez',
                'email' => 'eva.martinez@student.com',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'student_id' => '2024-005',
                'year_level' => '2nd Year',
                'section' => '302',
                'student_type' => 'regular',
            ],
            [
                'name' => 'Frank Garcia',
                'email' => 'frank.garcia@student.com',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'student_id' => '2024-006',
                'year_level' => '2nd Year',
                'section' => '302',
                'student_type' => 'regular',
            ],
        ];

        foreach ($section302Students as $studentData) {
            User::firstOrCreate(
                ['email' => $studentData['email']],
                $studentData
            );
        }

        // Create students for section 304 (to test the issue)
        $section304Students = [
            [
                'name' => 'Grace Kim',
                'email' => 'grace.kim@student.com',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'student_id' => '2024-007',
                'year_level' => '2nd Year',
                'section' => '304',
                'student_type' => 'regular',
            ],
            [
                'name' => 'Henry Wang',
                'email' => 'henry.wang@student.com',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'student_id' => '2024-008',
                'year_level' => '2nd Year',
                'section' => '304',
                'student_type' => 'regular',
            ],
        ];

        foreach ($section304Students as $studentData) {
            User::firstOrCreate(
                ['email' => $studentData['email']],
                $studentData
            );
        }

        echo "Users created successfully!\n";
        echo "- Teachers: " . count($teachers) . "\n";
        echo "- Section 301 students: " . count($section301Students) . "\n";
        echo "- Section 302 students: " . count($section302Students) . "\n";
        echo "- Section 304 students: " . count($section304Students) . "\n";
    }
}
