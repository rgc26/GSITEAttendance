<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Added this import for Auth::login

// Public routes
Route::get('/', function () {
    return view('welcome');
});







// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:register');
Route::get('/register/student', [AuthController::class, 'showStudentRegister'])->name('register.student');
Route::post('/register/student', [AuthController::class, 'registerStudent'])->middleware('throttle:register');
Route::get('/register/teacher', [AuthController::class, 'showTeacherRegister'])->name('register.teacher');
Route::post('/register/teacher', [AuthController::class, 'registerTeacher'])->middleware('throttle:register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Forgot Password routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email')->middleware('throttle:password');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update')->middleware('throttle:password');

// Email verification routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {
    // Find the user by ID
    $user = \App\Models\User::find($id);
    
    if (!$user) {
        return redirect()->route('login')->with('error', 'Invalid verification link.');
    }
    
    // Check if the hash matches
    if (!hash_equals(sha1($user->getEmailForVerification()), $hash)) {
        return redirect()->route('login')->with('error', 'Invalid verification link.');
    }
    
    // Mark email as verified
    if (!$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
    }
    
    // Log the user in
    Auth::login($user);
    
    // Redirect based on user role
    if ($user->role === 'teacher') {
        return redirect()->route('teacher.dashboard')->with('status', 'Email verified successfully!');
    } else {
        return redirect()->route('student.dashboard')->with('status', 'Email verified successfully!');
    }
})->middleware(['signed'])->name('verification.verify');

// Email verification resend route
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['throttle:6,1'])->name('verification.send');

// Student routes (temporarily without verified middleware)
Route::group([], function () {
    Route::get('/student/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/attendance', [StudentController::class, 'showAttendanceForm'])->name('student.attendance.form');
    Route::post('/attendance/{code}', [StudentController::class, 'markAttendance'])->name('student.attendance.mark');
    
    // Student Profile Management
    Route::get('/student/profile', [StudentController::class, 'profile'])->name('student.profile');
    Route::put('/student/profile', [StudentController::class, 'updateProfile'])->name('student.profile.update');
    Route::get('/student/change-password', [StudentController::class, 'changePassword'])->name('student.change-password');
    Route::put('/student/change-password', [StudentController::class, 'updatePassword'])->name('student.password.update');
});

// Teacher routes (temporarily without verified middleware)
Route::group([], function () {
    Route::get('/teacher/dashboard', [TeacherController::class, 'dashboard'])->name('teacher.dashboard');
    
    // Subjects
    Route::get('/teacher/subjects', function () {
        return redirect()->route('teacher.dashboard');
    })->name('teacher.subjects');
    Route::get('/teacher/subjects/archived', [TeacherController::class, 'archivedSubjects'])->name('teacher.subjects.archived');
    Route::get('/test-archived', function () {
        $subjects = auth()->user()->subjects()->where('archived', true)->get();
        return response()->json([
            'subjects_count' => $subjects->count(),
            'subjects' => $subjects->toArray()
        ]);
    })->name('test.archived');
    
    Route::get('/test-scheduled-sessions', function () {
        $scheduledSessions = \App\Models\AttendanceSession::whereNotNull('scheduled_start_time')
            ->with('subject')
            ->orderBy('scheduled_start_time')
            ->get();
            
        return response()->json([
            'scheduled_sessions' => $scheduledSessions->map(function($session) {
                return [
                    'id' => $session->id,
                    'name' => $session->name,
                    'subject_name' => $session->subject->name,
                    'scheduled_start_time' => $session->scheduled_start_time?->format('Y-m-d H:i:s'),
                    'scheduled_end_time' => $session->scheduled_end_time?->format('Y-m-d H:i:s'),
                    'is_active' => $session->is_active,
                    'start_time' => $session->start_time?->format('Y-m-d H:i:s'),
                    'current_time' => now()->format('Y-m-d H:i:s'),
                    'should_be_active' => $session->scheduled_start_time && $session->scheduled_start_time <= now() && !$session->start_time
                ];
            })
        ]);
    })->name('test.scheduled.sessions');
    Route::get('/teacher/subjects/create', [TeacherController::class, 'createSubject'])->name('teacher.subjects.create');
    Route::post('/teacher/subjects', [TeacherController::class, 'storeSubject'])->name('teacher.subjects.store');
    Route::get('/teacher/subjects/{subject}', [TeacherController::class, 'showSubject'])->name('teacher.subjects.show');
    Route::post('/teacher/subjects/{subject}/archive', [TeacherController::class, 'archiveSubject'])->name('teacher.subjects.archive');
    Route::post('/teacher/subjects/{subject}/unarchive', [TeacherController::class, 'unarchiveSubject'])->name('teacher.subjects.unarchive');
    Route::get('/teacher/subjects/{subject}/edit', [TeacherController::class, 'editSubject'])->name('teacher.subjects.edit');
    Route::put('/teacher/subjects/{subject}', [TeacherController::class, 'updateSubject'])->name('teacher.subjects.update');
    Route::delete('/teacher/subjects/{subject}', [TeacherController::class, 'deleteSubject'])->name('teacher.subjects.delete');
    
    // Schedules
    Route::get('/teacher/subjects/{subject}/schedules/create', [TeacherController::class, 'createSchedule'])->name('teacher.schedules.create');
    Route::post('/teacher/subjects/{subject}/schedules', [TeacherController::class, 'storeSchedule'])->name('teacher.schedules.store');
    Route::get('/teacher/schedules/{schedule}/edit', [TeacherController::class, 'editSchedule'])->name('teacher.schedules.edit');
    Route::put('/teacher/schedules/{schedule}', [TeacherController::class, 'updateSchedule'])->name('teacher.schedules.update');
    Route::delete('/teacher/schedules/{schedule}', [TeacherController::class, 'deleteSchedule'])->name('teacher.schedules.delete');
    
    // Attendance Sessions
    Route::get('/teacher/subjects/{subject}/sessions/create', [TeacherController::class, 'createSession'])->name('teacher.sessions.create');
    Route::post('/teacher/subjects/{subject}/sessions', [TeacherController::class, 'storeSession'])->name('teacher.sessions.store');
    Route::get('/teacher/sessions/{session}', [TeacherController::class, 'showSession'])->name('teacher.sessions.show');
    Route::get('/teacher/sessions/{session}/edit', [TeacherController::class, 'editSession'])->name('teacher.sessions.edit');
    Route::put('/teacher/sessions/{session}', [TeacherController::class, 'updateSession'])->name('teacher.sessions.update');
    Route::post('/teacher/sessions/{session}/end', [TeacherController::class, 'endSession'])->name('teacher.sessions.end');
    Route::post('/teacher/sessions/{session}/mark-absent', [TeacherController::class, 'markStudentAbsent'])->name('teacher.sessions.mark-absent');
    Route::post('/teacher/sessions/{session}/mark-present', [TeacherController::class, 'markStudentPresent'])->name('teacher.sessions.mark-present');
    Route::put('/teacher/sessions/{session}/attendance/{attendance}', [TeacherController::class, 'updateAttendance'])->name('teacher.sessions.update-attendance');
    Route::delete('/teacher/sessions/{session}/attendance/{attendance}', [TeacherController::class, 'deleteAttendance'])->name('teacher.sessions.delete-attendance');
    Route::delete('/teacher/sessions/{session}/users/{user}', [TeacherController::class, 'deleteUser'])->name('teacher.sessions.delete-user');
    Route::delete('/teacher/sessions/{session}', [TeacherController::class, 'deleteSession'])->name('teacher.sessions.delete');
    
    // Reports
    Route::get('/teacher/subjects/{subject}/report', [TeacherController::class, 'attendanceReport'])->name('teacher.reports.attendance');
    
    // Teacher Profile Management
    Route::get('/teacher/profile', [TeacherController::class, 'profile'])->name('teacher.profile');
    Route::put('/teacher/profile', [TeacherController::class, 'updateProfile'])->name('teacher.profile.update');
    Route::get('/teacher/change-password', [TeacherController::class, 'changePassword'])->name('teacher.change-password');
    Route::put('/teacher/change-password', [TeacherController::class, 'updatePassword'])->name('teacher.password.update');
});



// Test route to check CSP headers
Route::get('/test-csp', function () {
    return response()->json([
        'message' => 'CSP Test',
        'headers' => [
            'content-security-policy' => request()->header('content-security-policy'),
            'x-content-type-options' => request()->header('x-content-type-options'),
            'x-frame-options' => request()->header('x-frame-options'),
        ]
    ]);
});

// Manual session activation routes (for testing and manual activation)
Route::get('/admin/activate-sessions', function () {
    try {
        // Find sessions that should be activated
        $sessionsToActivate = \App\Models\AttendanceSession::whereNotNull('scheduled_start_time')
            ->where('scheduled_start_time', '<=', now())
            ->where('is_active', false)
            ->whereNull('start_time')
            ->get();

        $activatedCount = 0;
        $results = [];

        foreach ($sessionsToActivate as $session) {
            // Check if there's already an active session for this subject
            $activeSession = \App\Models\AttendanceSession::where('subject_id', $session->subject_id)
                ->where('is_active', true)
                ->where('id', '!=', $session->id)
                ->first();

            if ($activeSession) {
                $results[] = "Skipped session {$session->id} - already active session for subject {$session->subject_id}";
                continue;
            }

            // Activate the session
            $session->update([
                'start_time' => now(),
                'is_active' => true,
            ]);

            $results[] = "Activated session: {$session->name} (ID: {$session->id})";
            $activatedCount++;
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully activated {$activatedCount} session(s)",
            'activated_count' => $activatedCount,
            'results' => $results,
            'total_sessions_checked' => $sessionsToActivate->count()
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
})->name('admin.activate-sessions');

Route::get('/admin/deactivate-sessions', function () {
    try {
        // Find sessions that should be deactivated
        $sessionsToDeactivate = \App\Models\AttendanceSession::whereNotNull('scheduled_end_time')
            ->where('scheduled_end_time', '<=', now())
            ->where('is_active', true)
            ->get();

        $deactivatedCount = 0;
        $results = [];

        foreach ($sessionsToDeactivate as $session) {
            // Deactivate the session
            $session->update([
                'end_time' => now(),
                'is_active' => false,
            ]);

            $results[] = "Deactivated session: {$session->name} (ID: {$session->id})";
            $deactivatedCount++;
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully deactivated {$deactivatedCount} session(s)",
            'deactivated_count' => $deactivatedCount,
            'results' => $results,
            'total_sessions_checked' => $sessionsToDeactivate->count()
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
})->name('admin.deactivate-sessions');

// Admin dashboard route
Route::get('/admin/sessions', function () {
    return view('admin.sessions');
})->name('admin.sessions');

// Sessions data endpoint
Route::get('/admin/sessions-data', function () {
    try {
        $sessions = \App\Models\AttendanceSession::with('subject')
            ->whereNotNull('scheduled_start_time')
            ->orWhereNotNull('scheduled_end_time')
            ->orderBy('scheduled_start_time', 'desc')
            ->get()
            ->map(function ($session) {
                return [
                    'id' => $session->id,
                    'name' => $session->name,
                    'section' => $session->section,
                    'subject_name' => $session->subject->name ?? 'Unknown Subject',
                    'scheduled_start_time' => $session->scheduled_start_time ? $session->scheduled_start_time->format('Y-m-d H:i:s') : null,
                    'scheduled_end_time' => $session->scheduled_end_time ? $session->scheduled_end_time->format('Y-m-d H:i:s') : null,
                    'is_active' => $session->is_active,
                    'start_time' => $session->start_time ? $session->start_time->format('Y-m-d H:i:s') : null,
                    'end_time' => $session->end_time ? $session->end_time->format('Y-m-d H:i:s') : null,
                ];
            });

        return response()->json([
            'success' => true,
            'sessions' => $sessions
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
})->name('admin.sessions-data');

// Profile picture debug route
Route::get('/debug/profile-pictures', function () {
    $users = \App\Models\User::whereNotNull('profile_picture')->get();
    
    return view('debug.profile-pictures', compact('users'));
})->name('debug.profile-pictures'); 