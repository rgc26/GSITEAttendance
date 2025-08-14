@extends('layouts.app')

@section('content')
<div class="w-full mx-auto px-4 sm:px-6 lg:px-8 main-container">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg enhanced-card">
        <div class="p-6">
            <h1 class="page-title">
                <i class="fas fa-user-graduate mr-3 text-blue-600"></i>
                Student Dashboard
            </h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div class="enhanced-card bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-book text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="card-title text-white">Total Subjects</h3>
                            <p class="text-2xl font-bold">{{ $subjects->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="enhanced-card bg-gradient-to-r from-green-500 to-green-600 text-white">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="card-title text-white">Attendance Rate</h3>
                            <p class="text-2xl font-bold">{{ $overallAttendanceRate }}%</p>
                        </div>
                    </div>
                </div>

                <div class="enhanced-card bg-gradient-to-r from-purple-500 to-purple-600 text-white">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-calendar-check text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="card-title text-white">Total Sessions</h3>
                            <p class="text-2xl font-bold">{{ $totalSessions }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($subjects->count() > 0)
                <div class="mb-8">
                    <h2 class="section-title">
                        <i class="fas fa-book-open mr-2 text-blue-600"></i>
                        Your Subjects
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($subjects as $subject)
                            <div class="enhanced-card hover:shadow-lg transition-shadow duration-300">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="card-title text-blue-600">{{ $subject->name }}</h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $subject->code }}
                                    </span>
                                </div>
                                <p class="body-text mb-4">{{ $subject->description ?? 'No description available.' }}</p>
                                
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-blue-600">{{ $subject->totalSessions }}</div>
                                        <div class="small-text">Total Sessions</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-green-600">{{ $subject->attendedSessions }}</div>
                                        <div class="small-text">Attended</div>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="small-text">Attendance Rate</span>
                                        <span class="font-semibold text-blue-600">{{ $subject->attendanceRate }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $subject->attendanceRate }}%"></div>
                                    </div>
                                </div>
                                
                                <a href="{{ route('student.attendance-form') }}" class="enhanced-button bg-blue-500 text-white hover:bg-blue-600 w-full justify-center">
                                    <i class="fas fa-qrcode mr-2"></i>
                                    Mark Attendance
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-book text-6xl text-gray-300 mb-4"></i>
                    <h3 class="card-title text-gray-500">No Subjects Enrolled</h3>
                    <p class="body-text text-gray-400 mb-6">You are not enrolled in any subjects yet.</p>
                    <p class="small-text text-gray-500">Please contact your teacher or administrator to get enrolled.</p>
                </div>
            @endif

            @if($recentAttendances->count() > 0)
                <div>
                    <h2 class="section-title">
                        <i class="fas fa-history mr-2 text-blue-600"></i>
                        Recent Attendance
                    </h2>
                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Session</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentAttendances as $attendance)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $attendance->attendanceSession->subject->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $attendance->attendanceSession->name ?? 'No name' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($attendance->status === 'present')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Present
                                                </span>
                                            @elseif($attendance->status === 'late')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Late
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Absent
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $attendance->check_in_time->format('M d, Y g:i A') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 