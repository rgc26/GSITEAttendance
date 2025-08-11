<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('role:teacher');
    }

    public function dashboard()
    {
        $teacher = Auth::user();
        $subjects = $teacher->subjects()->where('archived', false)->with('schedules')->get();
        $activeSessions = AttendanceSession::whereHas('subject', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })->where('is_active', true)->get();

        return view('teacher.dashboard', compact('subjects', 'activeSessions'));
    }

    public function subjects()
    {
        $subjects = auth()->user()->subjects()->where('archived', false)->get();
        return view('teacher.subjects.index', compact('subjects'));
    }

    public function archivedSubjects()
    {
        $subjects = auth()->user()->subjects()->where('archived', true)->get();
        return view('teacher.subjects.archived', compact('subjects'));
    }

    public function createSubject()
    {
        return view('teacher.subjects.create');
    }

    public function storeSubject(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code' => 'required|string|max:10|unique:subjects',
        ]);

        Subject::create([
            'name' => $request->name,
            'description' => $request->description,
            'code' => strtoupper($request->code),
            'teacher_id' => Auth::id(),
        ]);

        return redirect()->route('teacher.subjects')->with('success', 'Subject created successfully!');
    }

    public function showSubject(Subject $subject)
    {
        $this->authorize('view', $subject);
        
        $sessions = $subject->attendanceSessions()->with('attendances.user')->latest()->get();
        
        return view('teacher.subjects.show', compact('subject', 'sessions'));
    }

    public function createSchedule(Subject $subject)
    {
        $this->authorize('view', $subject);
        return view('teacher.schedules.create', compact('subject'));
    }

    public function storeSchedule(Request $request, Subject $subject)
    {
        $this->authorize('view', $subject);

        $request->validate([
            'schedule_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'type' => 'required|in:lecture,lab,online',
            'room' => 'nullable|string|max:255',
            'section' => 'required|string|max:50',
        ]);

        // Get the day of the week from the selected date
        $scheduleDate = \Carbon\Carbon::parse($request->schedule_date);
        $dayOfWeek = strtolower($scheduleDate->format('l')); // 'l' gives full day name

        Schedule::create([
            'subject_id' => $subject->id,
            'schedule_date' => $request->schedule_date,
            'day' => $dayOfWeek,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'type' => $request->type,
            'room' => $request->room,
            'section' => $request->section,
        ]);

        return redirect()->route('teacher.subjects.show', $subject)->with('success', 'Schedule created successfully for ' . $scheduleDate->format('l, F j, Y') . '!');
    }

    public function editSchedule(Schedule $schedule)
    {
        $this->authorize('view', $schedule->subject);
        return view('teacher.schedules.edit', compact('schedule'));
    }

    public function updateSchedule(Request $request, Schedule $schedule)
    {
        $this->authorize('view', $schedule->subject);

        $request->validate([
            'day' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'type' => 'required|in:lecture,lab,online',
            'room' => 'nullable|string|max:255',
            'section' => 'required|string|max:50',
        ]);

        $schedule->update([
            'day' => $request->day,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'type' => $request->type,
            'room' => $request->room,
            'section' => $request->section,
        ]);

        return redirect()->route('teacher.subjects.show', $schedule->subject)->with('success', 'Schedule updated successfully!');
    }

    public function deleteSchedule(Schedule $schedule)
    {
        $this->authorize('view', $schedule->subject);
        
        $subject = $schedule->subject;
        $schedule->delete();

        return redirect()->route('teacher.subjects.show', $subject)->with('success', 'Schedule deleted successfully!');
    }

    public function createSession(Subject $subject)
    {
        $this->authorize('view', $subject);
        return view('teacher.sessions.create', compact('subject'));
    }

    public function storeSession(Request $request, Subject $subject)
    {
        $this->authorize('view', $subject);

        $request->validate([
            'name' => 'nullable|string|max:255',
            'section' => 'required|string|max:50',
            'session_type' => 'required|in:lecture,lab,online',
            'scheduled_start_time' => 'nullable|date',
            'scheduled_end_time' => 'nullable|date|after:scheduled_start_time',
            'grace_period_minutes' => 'nullable|integer|min:0|max:60',
            'notes' => 'nullable|string',
        ]);

        // Determine if this is a scheduled session
        $isScheduled = $request->scheduled_start_time && $request->scheduled_end_time;
        
        $session = AttendanceSession::create([
            'subject_id' => $subject->id,
            'name' => $request->name,
            'section' => $request->section,
            'session_type' => $request->session_type,
            'code' => strtoupper(substr(md5(uniqid()), 0, 6)),
            'start_time' => $isScheduled ? null : now(), // Only set start_time if not scheduled
            'scheduled_start_time' => $request->scheduled_start_time ? now()->parse($request->scheduled_start_time) : null,
            'scheduled_end_time' => $request->scheduled_end_time ? now()->parse($request->scheduled_end_time) : null,
            'grace_period_minutes' => $request->grace_period_minutes ?? 15,
            'is_active' => !$isScheduled, // Only active immediately if not scheduled
            'notes' => $request->notes,
        ]);

        $message = $isScheduled 
            ? 'Scheduled attendance session created! It will activate automatically at the scheduled time.'
            : 'Attendance session created!';
            
        return redirect()->route('teacher.sessions.show', $session)->with('success', $message);
    }

    public function showSession(AttendanceSession $session)
    {
        $this->authorize('view', $session->subject);
        
        // Get all attendances for this session
        $attendances = $session->attendances()->with('user')->get();
        
        // Get all students from the target section (kept for section stats display)
        $targetSectionStudents = User::where('role', 'student')
            ->where('section', $session->section)
            ->get();

        // Calculate section-based summary (kept for reference)
        $totalTargetStudents = $targetSectionStudents->count();
        $presentTargetStudents = $attendances->where('user.section', $session->section)->count();
        $absentTargetStudents = $totalTargetStudents - $presentTargetStudents;

        // Group attendances by student type (requested categorization)
        $regularAttendances = $attendances->where('user.student_type', 'regular');
        $irregularAttendances = $attendances->where('user.student_type', 'irregular');
        $blockAttendances = $attendances->where('user.student_type', 'block');
        
        // Get all students who should attend this session (based on section and student type)
        $allStudents = User::where('role', 'student')
            ->where(function($query) use ($session) {
                $query->where('section', $session->section)
                      ->orWhere('student_type', 'irregular')
                      ->orWhere('student_type', 'block');
            })
            ->get();
        
        // Only create absent records if this is the first time viewing the session
        // Check if any absent records already exist for this session
        $existingAbsentRecords = $attendances->where('status', 'absent')->count();
        
        if ($existingAbsentRecords == 0) {
            // Create absent records for students who didn't attend
            foreach ($allStudents as $student) {
                $existingAttendance = $attendances->where('user_id', $student->id)->first();
                
                if (!$existingAttendance) {
                    // Create absent record
                    \App\Models\Attendance::create([
                        'user_id' => $student->id,
                        'attendance_session_id' => $session->id,
                        'subject_id' => $session->subject_id,
                        'check_in_time' => now(),
                        'ip_address' => request()->ip(),
                        'status' => 'absent',
                    ]);
                }
            }
            
            // Refresh attendances after creating absent records
            $attendances = $session->attendances()->with('user')->get();
        }
        
        // Group attendances by student type
        $regularAttendances = $attendances->where('user.student_type', 'regular');
        $irregularAttendances = $attendances->where('user.student_type', 'irregular');
        $blockAttendances = $attendances->where('user.student_type', 'block');
        
        $irregularCount = $irregularAttendances->count();
        
        return view('teacher.sessions.show', compact(
            'session', 
            'attendances', 
            'targetSectionStudents',
            'regularAttendances',
            'irregularAttendances',
            'blockAttendances',
            'totalTargetStudents',
            'presentTargetStudents',
            'absentTargetStudents',
            'irregularCount'
        ));
    }

    public function endSession(AttendanceSession $session)
    {
        $this->authorize('view', $session->subject);
        
        $session->update([
            'end_time' => now(),
            'is_active' => false,
        ]);

        return redirect()->route('teacher.subjects.show', $session->subject)->with('success', 'Attendance session ended!');
    }

    public function editSession(AttendanceSession $session)
    {
        $this->authorize('view', $session->subject);
        return view('teacher.sessions.edit', compact('session'));
    }

    public function updateSession(Request $request, AttendanceSession $session)
    {
        $this->authorize('view', $session->subject);

        $request->validate([
            'name' => 'nullable|string|max:255',
            'section' => 'required|string|max:50',
            'session_type' => 'required|in:lecture,lab,online',
            'scheduled_start_time' => 'nullable|date',
            'scheduled_end_time' => 'nullable|date|after:scheduled_start_time',
            'grace_period_minutes' => 'nullable|integer|min:0|max:60',
            'notes' => 'nullable|string',
        ]);

        $session->update([
            'name' => $request->name,
            'section' => $request->section,
            'session_type' => $request->session_type,
            'scheduled_start_time' => $request->scheduled_start_time ? now()->parse($request->scheduled_start_time) : null,
            'scheduled_end_time' => $request->scheduled_end_time ? now()->parse($request->scheduled_end_time) : null,
            'grace_period_minutes' => $request->grace_period_minutes ?? 15,
            'notes' => $request->notes,
        ]);

        return redirect()->route('teacher.sessions.show', $session)->with('success', 'Session updated successfully!');
    }

    public function deleteSession(AttendanceSession $session)
    {
        // Check if the session belongs to the authenticated teacher
        if ($session->subject->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete all attendances for this session first
        $session->attendances()->delete();
        
        // Delete the session
        $session->delete();

        return redirect()->back()->with('success', 'Session deleted successfully.');
    }

    public function attendanceReport(Subject $subject)
    {
        $this->authorize('view', $subject);
        
        // Get all sessions for this subject
        $sessions = $subject->attendanceSessions()->with('attendances.user')->get();
        
        // Group sessions by section
        $sessionsBySection = $sessions->groupBy('section');
        
        // Calculate detailed report for each section
        $sectionReports = [];
        
        foreach ($sessionsBySection as $section => $sectionSessions) {
            // Get ALL students from this section (including those who never attended)
            $sectionStudents = User::where('role', 'student')
                ->where('section', $section)
                ->with(['attendances' => function($query) use ($subject) {
                    $query->whereHas('session', function($q) use ($subject) {
                        $q->where('subject_id', $subject->id);
                    })->with('session');
                }])
                ->get();
            
            // Get irregular students who attended this section's sessions
            $irregularStudents = User::where('role', 'student')
                ->where('section', '!=', $section)
                ->whereHas('attendances', function($query) use ($sectionSessions) {
                    $query->whereIn('attendance_session_id', $sectionSessions->pluck('id'));
                })
                ->with(['attendances' => function($query) use ($sectionSessions) {
                    $query->whereIn('attendance_session_id', $sectionSessions->pluck('id'))->with('session');
                }])
                ->get();
            
            $sectionReport = [];
            
            // Process ALL regular students (from target section) - including those who never attended
            foreach ($sectionStudents as $student) {
                $studentAttendances = $student->attendances->filter(function($attendance) use ($sectionSessions) {
                    return $sectionSessions->contains('id', $attendance->attendance_session_id);
                });
                
                $totalSessions = $sectionSessions->count();
                $presentSessions = $studentAttendances->where('status', 'present')->count();
                $lateSessions = $studentAttendances->where('status', 'late')->count();
                $absentSessions = $totalSessions - $presentSessions - $lateSessions;
                $attendanceRate = $totalSessions > 0 ? round((($presentSessions + $lateSessions) / $totalSessions) * 100, 1) : 0;
                
                $sectionReport['regular_students'][] = [
                    'student' => $student,
                    'total_sessions' => $totalSessions,
                    'present_sessions' => $presentSessions,
                    'late_sessions' => $lateSessions,
                    'absent_sessions' => $absentSessions,
                    'attendance_rate' => $attendanceRate,
                    'attendances' => $studentAttendances,
                ];
            }
            
            // Process irregular students who attended this section's sessions
            $irregularReport = [];
            foreach ($irregularStudents as $student) {
                $studentAttendances = $student->attendances->filter(function($attendance) use ($sectionSessions) {
                    return $sectionSessions->contains('id', $attendance->attendance_session_id);
                });
                
                if ($studentAttendances->count() > 0) {
                    $presentSessions = $studentAttendances->where('status', 'present')->count();
                    $lateSessions = $studentAttendances->where('status', 'late')->count();
                    
                    $irregularReport[] = [
                        'student' => $student,
                        'present_sessions' => $presentSessions,
                        'late_sessions' => $lateSessions,
                        'total_attended' => $studentAttendances->count(),
                        'attendances' => $studentAttendances,
                    ];
                }
            }
            
            $sectionReports[$section] = [
                'section' => $section,
                'total_sessions' => $sectionSessions->count(),
                'sessions' => $sectionSessions,
                'regular_students' => $sectionReport['regular_students'] ?? [],
                'irregular_students' => $irregularReport,
                'total_regular_students' => count($sectionReport['regular_students'] ?? []),
                'total_irregular_students' => count($irregularReport),
            ];
        }
        
        return view('teacher.reports.attendance', compact('subject', 'sectionReports'));
    }

    public function profile()
    {
        $teacher = Auth::user();
        return view('teacher.profile', compact('teacher'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'department' => 'required|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'department' => $request->department,
        ];

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture) {
                \Storage::disk('public')->delete($user->profile_picture);
            }
            $file = $request->file('profile_picture');
            $filename = time() . '_' . $file->getClientOriginalName();
            $data['profile_picture'] = $file->storeAs('profile_pictures', $filename, 'public');
        }

        $user->update($data);

        return redirect()->route('teacher.dashboard')->with('success', 'Profile updated successfully!');
    }

    public function changePassword()
    {
        return view('teacher.change-password');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Send email verification notification
        $user->sendEmailVerificationNotification();

        return redirect()->route('teacher.dashboard')->with('success', 'Password changed successfully! Please check your email for verification.');
    }

    public function archiveSubject(Subject $subject)
    {
        // Check if the subject belongs to the authenticated teacher
        if ($subject->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $subject->update(['archived' => true]);

        return redirect()->back()->with('success', 'Subject archived successfully.');
    }

    public function unarchiveSubject(Subject $subject)
    {
        // Check if the subject belongs to the authenticated teacher
        if ($subject->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $subject->update(['archived' => false]);

        return redirect()->back()->with('success', 'Subject unarchived successfully.');
    }

    public function editSubject(Subject $subject)
    {
        $this->authorize('view', $subject);
        return view('teacher.subjects.edit', compact('subject'));
    }

    public function updateSubject(Request $request, Subject $subject)
    {
        $this->authorize('update', $subject);
        
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'code' => 'required|string|max:10|unique:subjects,code,' . $subject->id,
            ]);
            
            $subject->update([
                'name' => $request->name,
                'description' => $request->description,
                'code' => strtoupper($request->code),
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Subject updated successfully!'
                ]);
            }
            
            return redirect()->route('teacher.subjects.show', $subject)->with('success', 'Subject updated successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong while updating the subject'
                ], 500);
            }
            throw $e;
        }
    }

    public function deleteSubject(Subject $subject)
    {
        $this->authorize('delete', $subject);
        
        // Delete all related data
        $subject->schedules()->delete();
        $subject->attendanceSessions()->delete();
        $subject->delete();
        
        return redirect()->route('teacher.subjects')->with('success', 'Subject deleted successfully.');
    }
} 