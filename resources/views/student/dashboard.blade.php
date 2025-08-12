@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">
                        <i class="fas fa-user-graduate mr-2"></i>
                        Student Dashboard
                    </h2>
                    <a href="{{ route('student.profile') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-user-edit mr-2"></i>
                        Edit Profile
                    </a>
                </div>
                
                <!-- Student Profile Section -->
                <div class="mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6">
                    <div class="flex items-center space-x-6">
                        <div class="flex-shrink-0">
                            @if(Auth::user()->profile_picture)
                                <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Profile Picture" class="w-20 h-20 rounded-full border-4 border-white shadow-lg object-cover">
                            @else
                                <div class="w-20 h-20 rounded-full bg-blue-500 flex items-center justify-center border-4 border-white shadow-lg">
                                    <i class="fas fa-user text-white text-2xl"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Welcome, {{ Auth::user()->name }}!</h3>
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600">Student ID:</span>
                                    <span class="font-medium text-gray-900 block">{{ Auth::user()->student_id }}</span>
                                </div>
                                @if(Auth::user()->year_level)
                                <div>
                                    <span class="text-gray-600">Year Level:</span>
                                    <span class="font-medium text-gray-900 block">{{ Auth::user()->year_level }}</span>
                                </div>
                                @endif
                                @if(Auth::user()->section)
                                <div>
                                    <span class="text-gray-600">Section:</span>
                                    <span class="font-medium text-gray-900 block">{{ Auth::user()->section }}</span>
                                </div>
                                @endif
                                @if(Auth::user()->student_type)
                                <div>
                                    <span class="text-gray-600">Student Type:</span>
                                    <span class="font-medium text-gray-900 block capitalize">{{ Auth::user()->student_type }}</span>
                                </div>
                                @endif
                                <div>
                                    <span class="text-gray-600">Email:</span>
                                    <span class="font-medium text-gray-900 block">{{ Auth::user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Attendance Summary</h3>
                    <p class="text-gray-600">Here's your attendance summary across all subjects.</p>
                </div>

                <!-- Form Requirements Summary -->
                <div class="mb-6 bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-indigo-900 mb-3">
                        <i class="fas fa-info-circle mr-2"></i>
                        Form Requirements & Restrictions
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-indigo-700">
                        <div>
                            <h4 class="font-medium mb-2">Profile Updates:</h4>
                            <ul class="space-y-1">
                                <li>• Section: Exactly 3 digits only (301, 302, 303, 101, 102)</li>
                                <li>• Year Level: Must select from dropdown</li>
                                <li>• Student Type: Must select from dropdown</li>
                                <li>• Profile Picture: Max 2MB (JPEG, PNG, JPG, GIF)</li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-medium mb-2">Password Changes:</h4>
                            <ul class="space-y-1">
                                <li>• Minimum 8 characters</li>
                                <li>• At least 1 uppercase letter (A-Z)</li>
                                <li>• At least 1 lowercase letter (a-z)</li>
                                <li>• At least 1 number (0-9)</li>
                                <li>• At least 1 special character (@$!%*?&)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Attendance Code Input Form -->
                <div class="mb-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-blue-900 mb-4">
                        <i class="fas fa-qrcode mr-2"></i>
                        Mark Attendance
                    </h3>
                    <p class="text-blue-700 mb-4">Enter the attendance code provided by your teacher to mark your attendance.</p>
                    
                    <form action="{{ route('student.attendance.form') }}" method="GET" class="flex gap-4">
                        <div class="flex-1">
                            <label for="attendance_code" class="block text-sm font-medium text-blue-900 mb-2">Attendance Code</label>
                            <input 
                                type="text" 
                                id="attendance_code" 
                                name="code" 
                                placeholder="Enter 6-digit code (e.g., 47187B)" 
                                class="w-full px-4 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required
                                maxlength="6"
                                pattern="[A-Za-z0-9]{6}"
                                title="Please enter a 6-character attendance code"
                            >
                        </div>
                        <div class="flex items-end">
                            <button 
                                type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            >
                                <i class="fas fa-check mr-2"></i>
                                Mark Attendance
                            </button>
                        </div>
                    </form>
                    
                    @if(session('error'))
                        <div class="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @if(session('success'))
                        <div class="mt-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif
                </div>

                @if(count($attendanceSummary) > 0)
                    <!-- Overall Attendance Summary -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-chart-pie mr-2"></i>
                            Overall Attendance Summary
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($attendanceSummary as $summary)
                                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-lg font-semibold text-gray-900">{{ $summary['subject']->name }}</h4>
                                        <span class="text-sm text-gray-500">{{ $summary['subject']->code }}</span>
                                    </div>
                                    
                                    <div class="space-y-3">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Total Sessions:</span>
                                            <span class="font-medium">{{ $summary['total_sessions'] }}</span>
                                        </div>
                                        
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Attended:</span>
                                            <span class="font-medium text-green-600">{{ $summary['attended_sessions'] }}</span>
                                        </div>
                                        
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Attendance Rate:</span>
                                            <span class="font-medium {{ $summary['percentage'] >= 80 ? 'text-green-600' : ($summary['percentage'] >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                                {{ $summary['percentage'] }}%
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $summary['percentage'] }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Session Type Breakdowns -->
                    <div class="space-y-8">
                        <!-- LAB Sessions -->
                        @if(count($sessionTypeBreakdown['lab']) > 0)
                            <div class="bg-orange-50 border border-orange-200 rounded-lg p-6">
                                <h3 class="text-lg font-medium text-orange-900 mb-4">
                                    <i class="fas fa-desktop mr-2"></i>
                                    Lab Sessions
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($sessionTypeBreakdown['lab'] as $labData)
                                        <div class="bg-white border border-orange-200 rounded-lg p-4">
                                            <div class="flex items-center justify-between mb-3">
                                                <h4 class="font-semibold text-gray-900">{{ $labData['subject']->name }}</h4>
                                                <span class="text-sm text-orange-600 font-medium">{{ $labData['count'] }} sessions</span>
                                            </div>
                                            <div class="space-y-2 text-sm">
                                                @foreach($labData['sessions'] as $session)
                                                    <div class="flex items-center justify-between p-2 bg-orange-50 rounded">
                                                        <span class="text-gray-700">{{ $session->attendanceSession->name ?? 'Lab Session' }}</span>
                                                        <div class="flex items-center space-x-2">
                                                            <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded">
                                                                PC{{ $session->pc_number ?? 'N/A' }}
                                                            </span>
                                                            <span class="text-xs px-2 py-1 {{ $session->status === 'present' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} rounded">
                                                                {{ ucfirst($session->status) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- ONLINE Sessions -->
                        @if(count($sessionTypeBreakdown['online']) > 0)
                            <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                                <h3 class="text-lg font-medium text-green-900 mb-4">
                                    <i class="fas fa-wifi mr-2"></i>
                                    Online Sessions
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($sessionTypeBreakdown['online'] as $onlineData)
                                        <div class="bg-white border border-green-200 rounded-lg p-4">
                                            <div class="flex items-center justify-between mb-3">
                                                <h4 class="font-semibold text-gray-900">{{ $onlineData['subject']->name }}</h4>
                                                <span class="text-sm text-green-600 font-medium">{{ $onlineData['count'] }} sessions</span>
                                            </div>
                                            <div class="space-y-2 text-sm">
                                                @foreach($onlineData['sessions'] as $session)
                                                    <div class="flex items-center justify-between p-2 bg-green-50 rounded">
                                                        <span class="text-gray-700">{{ $session->attendanceSession->name ?? 'Online Session' }}</span>
                                                        <div class="flex items-center space-x-2">
                                                            <span class="text-xs px-2 py-1 
                                                                @if($session->device_type === 'mobile') bg-green-100 text-green-800
                                                                @elseif($session->device_type === 'desktop') bg-blue-100 text-blue-800
                                                                @elseif($session->device_type === 'laptop') bg-purple-100 text-purple-800
                                                                @else bg-gray-100 text-gray-800
                                                                @endif rounded">
                                                                {{ ucfirst($session->device_type ?? 'N/A') }}
                                                            </span>
                                                            <span class="text-xs px-2 py-1 {{ $session->status === 'present' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} rounded">
                                                                {{ ucfirst($session->status) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- LECTURE Sessions -->
                        @if(count($sessionTypeBreakdown['lecture']) > 0)
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                                <h3 class="text-lg font-medium text-yellow-900 mb-4">
                                    <i class="fas fa-image mr-2"></i>
                                    Lecture Sessions
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($sessionTypeBreakdown['lecture'] as $lectureData)
                                        <div class="bg-white border border-yellow-200 rounded-lg p-4">
                                            <div class="flex items-center justify-between mb-3">
                                                <h4 class="font-semibold text-gray-900">{{ $lectureData['subject']->name }}</h4>
                                                <span class="text-sm text-yellow-600 font-medium">{{ $lectureData['count'] }} sessions</span>
                                            </div>
                                            <div class="space-y-2 text-sm">
                                                @foreach($lectureData['sessions'] as $session)
                                                    <div class="flex items-center justify-between p-2 bg-yellow-50 rounded">
                                                        <span class="text-gray-700">{{ $session->attendanceSession->name ?? 'Lecture Session' }}</span>
                                                        <div class="flex items-center space-x-2">
                                                            @if($session->attached_image)
                                                                <a href="{{ asset('storage/' . $session->attached_image) }}" 
                                                                   target="_blank" 
                                                                   class="text-xs px-2 py-1 bg-indigo-100 text-indigo-800 rounded hover:bg-indigo-200">
                                                                    <i class="fas fa-image mr-1"></i>
                                                                    View Image
                                                                </a>
                                                            @else
                                                                <span class="text-xs px-2 py-1 bg-gray-100 text-gray-800 rounded">
                                                                    No Image
                                                                </span>
                                                            @endif
                                                            <span class="text-xs px-2 py-1 {{ $session->status === 'present' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} rounded">
                                                                {{ ucfirst($session->status) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-clipboard-list text-4xl text-gray-400 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Attendance Records</h3>
                        <p class="text-gray-600">You haven't attended any sessions yet. Check with your teachers for attendance codes.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 