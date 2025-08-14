@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200 mobile-friendly-container">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-3xl font-bold text-gray-900">
                        <i class="fas fa-book mr-3"></i>
                        {{ $subject->name }}
                    </h2>
                    <div class="flex space-x-3">
                        <a href="{{ route('teacher.subjects.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Subjects
                        </a>
                        <a href="{{ route('teacher.subjects.edit', $subject) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition-colors duration-200">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Subject
                        </a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded text-base">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded text-base">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Subject Information -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                        <h3 class="text-lg font-medium text-blue-900 mb-4">Subject Details</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-start">
                                <span class="text-blue-700 text-base">Subject Code:</span>
                                <span class="font-medium text-blue-900 text-base">{{ $subject->code }}</span>
                            </div>
                            @if($subject->description)
                                <div class="flex justify-between items-start">
                                    <span class="text-blue-700 text-base">Description:</span>
                                    <span class="font-medium text-blue-900 text-base text-right">{{ $subject->description }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between items-start">
                                <span class="text-blue-700 text-base">Department:</span>
                                <span class="font-medium text-blue-900 text-base">{{ $subject->department }}</span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span class="text-blue-700 text-base">Created:</span>
                                <span class="font-medium text-blue-900 text-base">{{ $subject->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 rounded-lg p-6 border border-green-200">
                        <h3 class="text-lg font-medium text-green-900 mb-4">Statistics</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-start">
                                <span class="text-green-700 text-base">Total Schedules:</span>
                                <span class="font-medium text-green-900 text-xl">{{ $subject->schedules->count() }}</span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span class="text-green-700 text-base">Total Sessions:</span>
                                <span class="font-medium text-green-900 text-xl">{{ $subject->attendanceSessions->count() }}</span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span class="text-green-700 text-base">Active Sessions:</span>
                                <span class="font-medium text-green-900 text-xl">{{ $subject->attendanceSessions->where('is_active', true)->count() }}</span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span class="text-green-700 text-base">Total Attendees:</span>
                                <span class="font-medium text-green-900 text-xl">{{ $subject->attendanceSessions->sum(function($session) { return $session->attendances->count(); }) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-purple-50 rounded-lg p-6 border border-purple-200">
                        <h3 class="text-lg font-medium text-purple-900 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <a href="{{ route('teacher.schedules.create', ['subject' => $subject->id]) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 transition-colors duration-200">
                                <i class="fas fa-calendar-plus mr-2"></i>
                                Add Schedule
                            </a>
                            <a href="{{ route('teacher.sessions.create', ['subject' => $subject->id]) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-purple-300 text-sm font-medium rounded-md text-purple-700 bg-white hover:bg-purple-50 transition-colors duration-200">
                                <i class="fas fa-qrcode mr-2"></i>
                                Start Session
                            </a>
                            <a href="{{ route('teacher.reports.attendance', ['subject' => $subject->id]) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-purple-300 text-sm font-medium rounded-md text-purple-700 bg-white hover:bg-purple-50 transition-colors duration-200">
                                <i class="fas fa-chart-bar mr-2"></i>
                                View Reports
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Schedules Section -->
                <div class="bg-white border border-gray-200 rounded-lg mb-8">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-semibold text-gray-900">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                Class Schedules
                            </h3>
                            <a href="{{ route('teacher.schedules.create', ['subject' => $subject->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition-colors duration-200">
                                <i class="fas fa-plus mr-2"></i>
                                Add Schedule
                            </a>
                        </div>
                    </div>
                    
                    @if($subject->schedules->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Day</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Section</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($subject->schedules as $schedule)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $schedule->day }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $schedule->section }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $schedule->room ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('teacher.schedules.edit', $schedule) }}" class="text-indigo-600 hover:text-indigo-900">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('teacher.schedules.destroy', $schedule) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this schedule?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-calendar-times text-4xl mb-4"></i>
                            <p class="text-lg">No schedules set</p>
                            <p class="text-base">Add class schedules to organize your subject</p>
                        </div>
                    @endif
                </div>

                <!-- Recent Sessions Section -->
                <div class="bg-white border border-gray-200 rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-semibold text-gray-900">
                                <i class="fas fa-qrcode mr-2"></i>
                                Recent Attendance Sessions
                            </h3>
                            <a href="{{ route('teacher.sessions.create', ['subject' => $subject->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 transition-colors duration-200">
                                <i class="fas fa-plus mr-2"></i>
                                Start New Session
                            </a>
                        </div>
                    </div>
                    
                    @if($subject->attendanceSessions->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Session Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Section</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attendees</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($subject->attendanceSessions->sortByDesc('created_at')->take(10) as $session)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $session->name ?? 'Untitled Session' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $session->section }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    {{ $session->session_type === 'lab' ? 'bg-blue-100 text-blue-800' : 
                                                       ($session->session_type === 'online' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                                    {{ ucfirst($session->session_type ?? 'lecture') }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($session->is_active)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Active
                                                    </span>
                                                @elseif($session->start_time && !$session->end_time)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        Started
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        Ended
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $session->attendances->count() }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('teacher.sessions.show', $session) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($session->is_active)
                                                    <form method="POST" action="{{ route('teacher.sessions.end', $session) }}" class="inline" onsubmit="return confirm('Are you sure you want to end this session?')">
                                                        @csrf
                                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                                            <i class="fas fa-stop"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-qrcode text-4xl mb-4"></i>
                            <p class="text-lg">No sessions yet</p>
                            <p class="text-base">Start your first attendance session</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection