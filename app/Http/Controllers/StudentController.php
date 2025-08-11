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
        foreach ($subjects as $subject) {
            $totalSessions = $subject->attendanceSessions()->count();
            $attendedSessions = $subject->attendances()->where('user_id', $user->id)->count();
            $attendancePercentage = $totalSessions > 0 ? round(($attendedSessions / $totalSessions) * 100, 2) : 0;
            
            $attendanceSummary[] = [
                'subject' => $subject,
                'total_sessions' => $totalSessions,
                'attended_sessions' => $attendedSessions,
                'percentage' => $attendancePercentage,
            ];
        }

        return view('student.dashboard', compact('attendanceSummary'));
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
            return redirect()->route('student.dashboard')->with('error', 'You have already marked your attendance for this session.');
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

        // Validate attendance code
        $request->validate([
            'attendance_code' => 'required|string',
        ]);

        // Verify attendance code matches session code
        if (strtoupper($request->attendance_code) !== $session->code) {
            return redirect()->back()->with('error', 'Invalid attendance code. Please check the code provided by your teacher.');
        }

        // Get session type
        $sessionType = $session->session_type ?? 'lecture';
        
        // Validate based on session type
        $validationRules = [];
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
            'year_level' => 'required|string|max:50',
            'section' => 'required|string|max:50',
            'student_type' => 'required|in:regular,irregular,block',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
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