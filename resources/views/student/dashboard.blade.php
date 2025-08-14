@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200 mobile-friendly-container">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-3xl font-bold text-gray-900">
                        <i class="fas fa-user-graduate mr-3"></i>
                        Student Dashboard
                    </h2>
                    <div class="text-sm text-gray-500">
                        Welcome back, {{ Auth::user()->name }}!
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-book text-2xl text-blue-600"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-lg font-semibold text-blue-900">{{ $subjects->count() }}</div>
                                <div class="text-sm text-blue-600">Enrolled Subjects</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 rounded-lg p-6 border border-green-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-2xl text-green-600"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-lg font-semibold text-green-900">{{ $totalSessions }}</div>
                                <div class="text-sm text-green-600">Total Sessions</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-purple-50 rounded-lg p-6 border border-purple-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-calendar-check text-2xl text-purple-600"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-lg font-semibold text-purple-900">{{ $attendedSessions }}</div>
                                <div class="text-sm text-purple-600">Attended Sessions</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-50 rounded-lg p-6 border border-yellow-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-percentage text-2xl text-yellow-600"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-lg font-semibold text-yellow-900">{{ $attendanceRate }}%</div>
                                <div class="text-sm text-yellow-600">Attendance Rate</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Subjects Overview -->
                <div class="bg-gray-50 rounded-lg p-6 mb-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">
                        <i class="fas fa-book-open mr-2"></i>
                        Your Subjects
                    </h3>
                    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                        @forelse($subjects as $subject)
                            <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ $subject->name }}</h4>
                                        <p class="text-base font-mono text-indigo-600 mb-2">{{ $subject->code }}</p>
                                        <p class="text-sm text-gray-500">Section {{ Auth::user()->section }}</p>
                                    </div>
                                    <div class="ml-4">
                                        @php
                                            $subjectAttendance = $subject->attendances->where('user_id', Auth::id())->count();
                                            $subjectSessions = $subject->attendanceSessions->where('section', Auth::user()->section)->count();
                                            $subjectRate = $subjectSessions > 0 ? round(($subjectAttendance / $subjectSessions) * 100, 1) : 0;
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                            {{ $subjectRate >= 80 ? 'bg-green-100 text-green-800' : 
                                               ($subjectRate >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ $subjectRate }}%
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 mb-4 text-center">
                                    <div class="bg-blue-50 rounded-lg p-3">
                                        <div class="text-lg font-bold text-blue-600">{{ $subjectSessions }}</div>
                                        <div class="text-xs text-blue-600">Total Sessions</div>
                                    </div>
                                    <div class="bg-green-50 rounded-lg p-3">
                                        <div class="text-lg font-bold text-green-600">{{ $subjectAttendance }}</div>
                                        <div class="text-xs text-green-600">Attended</div>
                                    </div>
                                </div>

                                <div class="text-sm text-gray-600">
                                    <div class="flex justify-between items-center">
                                        <span>Attendance Rate:</span>
                                        <span class="font-medium">{{ $subjectRate }}%</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-8 text-gray-500">
                                <i class="fas fa-book text-4xl mb-4"></i>
                                <p class="text-lg">No subjects enrolled</p>
                                <p class="text-sm">Contact your teacher to get enrolled in subjects</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Attendance -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">
                        <i class="fas fa-history mr-2"></i>
                        Recent Attendance
                    </h3>
                    <div class="space-y-4">
                        @forelse($recentAttendances as $attendance)
                            <div class="flex items-center justify-between p-4 bg-white rounded-lg border border-gray-200">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-qrcode text-lg text-blue-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-base font-medium text-gray-900">{{ $attendance->attendanceSession->subject->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $attendance->attendanceSession->name ?? 'Untitled Session' }}</div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                        {{ $attendance->status === 'present' ? 'bg-green-100 text-green-800' : 
                                           ($attendance->status === 'late' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($attendance->status) }}
                                    </span>
                                    <div class="text-sm text-gray-500">
                                        {{ $attendance->check_in_time->format('M d, Y g:i A') }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-4"></i>
                                <p class="text-lg">No recent attendance</p>
                                <p class="text-sm">Attend sessions to see your attendance history</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <a href="{{ route('student.profile') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg p-6 transition-colors duration-200">
                        <div class="flex items-center">
                            <i class="fas fa-user text-2xl mr-4"></i>
                            <div>
                                <div class="text-lg font-semibold">Update Profile</div>
                                <div class="text-indigo-100 text-sm">Keep your information current</div>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('student.change-password') }}" class="bg-green-600 hover:bg-green-700 text-white rounded-lg p-6 transition-colors duration-200">
                        <div class="flex items-center">
                            <i class="fas fa-key text-2xl mr-4"></i>
                            <div>
                                <div class="text-lg font-semibold">Change Password</div>
                                <div class="text-green-100 text-sm">Update your account security</div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 