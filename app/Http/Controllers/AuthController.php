<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use App\Rules\HCaptcha;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function showStudentRegister()
    {
        return view('auth.register-student');
    }

    public function showTeacherRegister()
    {
        return view('auth.register-teacher');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'h-captcha-response' => ['required', new HCaptcha],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['status' => __($status)])
                    : back()->withErrors(['email' => __($status)]);
    }

    public function showResetPassword($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'h-captcha-response' => ['required', new HCaptcha],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withErrors(['email' => [__($status)]]);
    }

    public function login(Request $request)
    {
        // Sanitize input
        $email = filter_var($request->input('email'), FILTER_SANITIZE_EMAIL);
        $password = $request->input('password');

        $validator = Validator::make([
            'email' => $email,
            'password' => $password,
            'h-captcha-response' => $request->input('h-captcha-response')
        ], [
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:1',
            'h-captcha-response' => ['required', new HCaptcha],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Rate limiting check
        $key = 'login_' . $request->ip() . '_' . $email;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ])->withInput();
        }

        $credentials = [
            'email' => $email,
            'password' => $password
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            RateLimiter::clear($key);

            $user = Auth::user();
            
            // Log successful login
            Log::info('User logged in successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            if ($user && $user->role === 'student') {
                return redirect()->route('student.dashboard');
            } elseif ($user && $user->role === 'teacher') {
                return redirect()->route('teacher.dashboard');
            } else {
                // Fallback for users without role
                return redirect()->route('student.dashboard');
            }
        }

        // Increment failed attempts
        RateLimiter::hit($key, 900); // 15 minutes

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:student,teacher',
            'student_id' => 'required_if:role,student|nullable|string|max:255',
            'department' => 'required_if:role,teacher|nullable|string|max:255',
            'h-captcha-response' => ['required', new HCaptcha],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'student_id' => $request->student_id,
            'department' => $request->department,
        ]);

        // Send email verification (only once)
        event(new Registered($user));

        Auth::login($user);
        return redirect()->route('student.dashboard')->with('status', 'Registration successful! Please check your email to verify your account.');
    }

    public function registerStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:10',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'student_id' => 'required|string|max:255',
            'year_level' => 'required|string|max:50',
            'section' => 'required|string|max:50',
            'student_type' => 'required|in:regular,irregular,block',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'h-captcha-response' => ['required', new HCaptcha],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $profilePicturePath = null;
        if ($request->hasFile('profile_picture')) {
            try {
                $file = $request->file('profile_picture');
                $filename = time() . '_' . $file->getClientOriginalName();
                $profilePicturePath = $file->storeAs('profile_pictures', $filename, 'public');
            } catch (\Exception $e) {
                \Log::error('Profile picture upload failed: ' . $e->getMessage());
                // Continue without profile picture if upload fails
                $profilePicturePath = null;
            }
        }

        $name = $request->last_name . ', ' . $request->first_name;
        if ($request->middle_initial) {
            $name .= ', ' . $request->middle_initial . '.';
        }

        $user = User::create([
            'name' => $name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
            'student_id' => $request->student_id,
            'department' => null,
            'year_level' => $request->year_level,
            'section' => $request->section,
            'student_type' => $request->student_type,
            'profile_picture' => $profilePicturePath,
        ]);

        try {
            // Send email verification (only once)
            event(new Registered($user));
        } catch (\Exception $e) {
            \Log::error('Email verification event failed: ' . $e->getMessage());
            // Continue without email verification if it fails
        }

        Auth::login($user);
        return redirect()->route('student.dashboard')->with('status', 'Registration successful! Please check your email to verify your account.');
    }

    public function registerTeacher(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'department' => 'required|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'h-captcha-response' => ['required', new HCaptcha],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $profilePicturePath = null;
        if ($request->hasFile('profile_picture')) {
            try {
                $file = $request->file('profile_picture');
                $filename = time() . '_' . $file->getClientOriginalName();
                $profilePicturePath = $file->storeAs('profile_pictures', $filename, 'public');
            } catch (\Exception $e) {
                \Log::error('Profile picture upload failed: ' . $e->getMessage());
                // Continue without profile picture if upload fails
                $profilePicturePath = null;
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
            'student_id' => null,
            'department' => $request->department,
            'profile_picture' => $profilePicturePath,
        ]);

        try {
            // Send email verification (only once)
            event(new Registered($user));
        } catch (\Exception $e) {
            \Log::error('Email verification event failed: ' . $e->getMessage());
            // Continue without email verification if it fails
        }

        Auth::login($user);
        return redirect()->route('teacher.dashboard')->with('status', 'Registration successful! Please check your email to verify your account.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('status', 'You have been successfully logged out.');
    }
} 