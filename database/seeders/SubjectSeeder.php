<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get teachers
        $teachers = User::where('role', 'teacher')->get();

        if ($teachers->count() > 0) {
            // Dr. Sarah Johnson - Computer Science subjects
            $sarah = $teachers->where('email', 'sarah.johnson@university.edu')->first();
            if ($sarah) {
                Subject::create([
                    'name' => 'Introduction to Programming',
                    'description' => 'Basic programming concepts using Python',
                    'code' => 'CS101',
                    'teacher_id' => $sarah->id,
                ]);

                Subject::create([
                    'name' => 'Data Structures and Algorithms',
                    'description' => 'Advanced programming concepts and problem solving',
                    'code' => 'CS201',
                    'teacher_id' => $sarah->id,
                ]);
            }

            // Prof. Michael Chen - Mathematics subjects
            $michael = $teachers->where('email', 'michael.chen@university.edu')->first();
            if ($michael) {
                Subject::create([
                    'name' => 'Calculus I',
                    'description' => 'Introduction to differential calculus',
                    'code' => 'MATH101',
                    'teacher_id' => $michael->id,
                ]);

                Subject::create([
                    'name' => 'Linear Algebra',
                    'description' => 'Vectors, matrices, and linear transformations',
                    'code' => 'MATH201',
                    'teacher_id' => $michael->id,
                ]);
            }

            // Dr. Emily Rodriguez - Physics subjects
            $emily = $teachers->where('email', 'emily.rodriguez@university.edu')->first();
            if ($emily) {
                Subject::create([
                    'name' => 'General Physics I',
                    'description' => 'Mechanics and thermodynamics',
                    'code' => 'PHYS101',
                    'teacher_id' => $emily->id,
                ]);

                Subject::create([
                    'name' => 'General Physics II',
                    'description' => 'Electricity, magnetism, and waves',
                    'code' => 'PHYS102',
                    'teacher_id' => $emily->id,
                ]);
            }
        }

        $this->command->info('Sample subjects created successfully!');
    }
}
