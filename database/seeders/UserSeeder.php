<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample teachers
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

        foreach ($teachers as $teacher) {
            User::create($teacher);
        }

        // Create sample students
        $students = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@student.edu',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'student_id' => '2024001',
                'department' => 'Computer Science',
            ],
            [
                'name' => 'Maria Garcia',
                'email' => 'maria.garcia@student.edu',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'student_id' => '2024002',
                'department' => 'Computer Science',
            ],
            [
                'name' => 'David Lee',
                'email' => 'david.lee@student.edu',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'student_id' => '2024003',
                'department' => 'Mathematics',
            ],
            [
                'name' => 'Lisa Wang',
                'email' => 'lisa.wang@student.edu',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'student_id' => '2024004',
                'department' => 'Physics',
            ],
            [
                'name' => 'Alex Thompson',
                'email' => 'alex.thompson@student.edu',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'student_id' => '2024005',
                'department' => 'Computer Science',
            ],
            [
                'name' => 'Sophie Brown',
                'email' => 'sophie.brown@student.edu',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'student_id' => '2024006',
                'department' => 'Mathematics',
            ],
            [
                'name' => 'James Wilson',
                'email' => 'james.wilson@student.edu',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'student_id' => '2024007',
                'department' => 'Physics',
            ],
            [
                'name' => 'Emma Davis',
                'email' => 'emma.davis@student.edu',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'student_id' => '2024008',
                'department' => 'Computer Science',
            ],
        ];

        foreach ($students as $student) {
            User::create($student);
        }

        $this->command->info('Sample users created successfully!');
        $this->command->info('Teachers:');
        foreach ($teachers as $teacher) {
            $this->command->info("- {$teacher['name']} ({$teacher['email']}) - Password: password123");
        }
        $this->command->info('Students:');
        foreach ($students as $student) {
            $this->command->info("- {$student['name']} ({$student['email']}) - Student ID: {$student['student_id']} - Password: password123");
        }
    }
}
