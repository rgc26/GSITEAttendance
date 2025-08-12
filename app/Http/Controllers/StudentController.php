<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('role:student');
    }

    public function dashboard()
    {
        $user = Auth::user();
        
        // Get all subjects where student has attendance records
        $subjects = Subject::whereHas('attendances', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['attendances' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->get();

        $attendanceSummary = [];
        $sessionTypeBreakdown = [
            'lab' => [],
            'online' => [],
            'lecture' => []
        ];
        
        foreach ($subjects as $subject) {
            $totalSessions = $subject->attendanceSessions()->count();
            $attendedSessions = $subject->attendances()->where('user_id', $user->id)->count();
            $attendancePercentage = $totalSessions > 0 ? round(($attendedSessions / $totalSessions) * 100, 2) : 0;
            
            // Get session type breakdown for this subject
            $subjectAttendances = $subject->attendances()
                ->where('user_id', $user->id)
                ->with('attendanceSession')
                ->get();
            
            $labSessions = $subjectAttendances->filter(function($attendance) {
                return $attendance->attendanceSession && $attendance->attendanceSession->session_type === 'lab';
            });
            $onlineSessions = $subjectAttendances->filter(function($attendance) {
                return $attendance->attendanceSession && $attendance->attendanceSession->session_type === 'online';
            });
            $lectureSessions = $subjectAttendances->filter(function($attendance) {
                return $attendance->attendanceSession && $attendance->attendanceSession->session_type === 'lecture';
            });
            
            $attendanceSummary[] = [
                'subject' => $subject,
                'total_sessions' => $totalSessions,
                'attended_sessions' => $attendedSessions,
                'percentage' => $attendancePercentage,
                'lab_sessions' => $labSessions->count(),
                'online_sessions' => $onlineSessions->count(),
                'lecture_sessions' => $lectureSessions->count(),
            ];
            
            // Add to session type breakdowns
            if ($labSessions->count() > 0) {
                $sessionTypeBreakdown['lab'][] = [
                    'subject' => $subject,
                    'sessions' => $labSessions,
                    'count' => $labSessions->count()
                ];
            }
            
            if ($onlineSessions->count() > 0) {
                $sessionTypeBreakdown['online'][] = [
                    'subject' => $subject,
                    'sessions' => $onlineSessions,
                    'count' => $onlineSessions->count()
                ];
            }
            
            if ($lectureSessions->count() > 0) {
                $sessionTypeBreakdown['lecture'][] = [
                    'subject' => $subject,
                    'sessions' => $lectureSessions,
                    'count' => $lectureSessions->count()
                ];
            }
        }

        return view('student.dashboard', compact('attendanceSummary', 'sessionTypeBreakdown'));
    }

    public function showAttendanceForm(Request $request)
    {
        $code = $request->get('code');
        
        if (!$code) {
            return redirect()->route('student.dashboard')->with('error', 'Please enter an attendance code.');
        }

        $session = AttendanceSession::where('code', strtoupper($code))
            ->where('is_active', true)
            ->first();

        if (!$session) {
            return redirect()->route('student.dashboard')->with('error', 'Invalid or expired attendance code.');
        }

        // Check if session is currently active (within scheduled time + grace period)
        if (!$session->isCurrentlyActive()) {
            return redirect()->route('student.dashboard')->with('error', 'This attendance session is not currently active.');
        }

        // Check if student already attended this session
        $existingAttendance = Attendance::where('user_id', Auth::id())
            ->where('attendance_session_id', $session->id)
            ->first();

        if ($existingAttendance) {
            return redirect()->back()->with('error', 'You have already marked your attendance for this session.');
        }

        return view('student.attendance-form', compact('session'));
    }

    public function markAttendance(Request $request, $code)
    {
        $session = AttendanceSession::where('code', $code)
            ->where('is_active', true)
            ->first();

        if (!$session) {
            return redirect()->back()->with('error', 'Invalid or expired attendance code.');
        }

        // Check if session is currently active
        if (!$session->isCurrentlyActive()) {
            return redirect()->back()->with('error', 'This attendance session is not currently active.');
        }

        // Check if student already attended this session
        $existingAttendance = Attendance::where('user_id', Auth::id())
            ->where('attendance_session_id', $session->id)
            ->first();

        if ($existingAttendance) {
            return redirect()->back()->with('error', 'You have already marked your attendance for this session.');
        }

        // Get session type
        $sessionType = $session->session_type ?? 'lecture';
        
        // Validate based on session type
        $attendanceData = [
            'user_id' => Auth::id(),
            'attendance_session_id' => $session->id,
            'subject_id' => $session->subject_id,
            'check_in_time' => now()->setTimezone('Asia/Manila'),
            'ip_address' => $request->ip(),
            'status' => $session->getAttendanceStatus(now()->setTimezone('Asia/Manila')),
        ];

        switch ($sessionType) {
            case 'lab':
                $request->validate([
                    'pc_number' => 'required|string|max:50',
                ]);
                $attendanceData['pc_number'] = $request->pc_number;
                break;

            case 'online':
                $request->validate([
                    'device_type' => 'required|in:mobile,desktop,laptop',
                ]);
                $attendanceData['device_type'] = $request->device_type;
                break;

            case 'lecture':
                $request->validate([
                    'attached_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                ]);
                
                // Handle image upload
                if ($request->hasFile('attached_image')) {
                    $file = $request->file('attached_image');
                    $filename = time() . '_' . Auth::id() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('attendance_images', $filename, 'public');
                    $attendanceData['attached_image'] = $path;
                }
                break;

            default:
                return redirect()->back()->with('error', 'Invalid session type.');
        }

        // Create attendance record
        Attendance::create($attendanceData);

        $message = $attendanceData['status'] === 'late' 
            ? 'Attendance marked successfully! (Marked as late)'
            : 'Attendance marked successfully!';

        return redirect()->route('student.dashboard')->with('success', $message);
    }

    public function profile()
    {
        $user = Auth::user();
        return view('student.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'year_level' => 'required|string|in:1st Year,2nd Year,3rd Year,4th Year',
            'section' => 'required|string|regex:/^[0-9]{3}$/|max:3',
            'student_type' => 'required|string|in:regular,irregular,block',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'year_level.required' => 'Year level is required.',
            'year_level.in' => 'Please select a valid year level.',
            'section.required' => 'Section is required.',
            'section.regex' => 'Section must be exactly 3 digits (e.g., 301, 302, 303).',
            'section.max' => 'Section cannot exceed 3 characters.',
            'student_type.required' => 'Student type is required.',
            'student_type.in' => 'Please select a valid student type.',
            'profile_picture.image' => 'Profile picture must be an image file.',
            'profile_picture.mimes' => 'Profile picture must be JPEG, PNG, JPG, or GIF.',
            'profile_picture.max' => 'Profile picture cannot exceed 2MB.',
        ]);

        $data = [
            'year_level' => $request->year_level,
            'section' => $request->section,
            'student_type' => $request->student_type,
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

        return redirect()->route('student.profile')->with('success', 'Profile updated successfully!');
    }

    public function changePassword()
    {
        return view('student.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string|min:1|max:128',
            'password' => 'required|string|min:8|max:128|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/|confirmed',
        ], [
            'current_password.required' => 'Current password is required.',
            'current_password.min' => 'Current password must be at least 1 character.',
            'current_password.max' => 'Current password cannot exceed 128 characters.',
            'password.required' => 'New password is required.',
            'password.min' => 'New password must be at least 8 characters.',
            'password.max' => 'New password cannot exceed 128 characters.',
            'password.regex' => 'New password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&).',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Send email verification for password change
        $user->sendEmailVerificationNotification();

        return redirect()->route('student.profile')->with('success', 'Password changed successfully! Please check your email for verification.');
    }
} 