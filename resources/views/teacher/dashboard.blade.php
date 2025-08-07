@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-chalkboard-teacher mr-2"></i>
                    Teacher Dashboard
                </h2>
                
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Welcome, {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-600">Manage your subjects and attendance sessions.</p>
                </div>

                <!-- Teacher Profile Section -->
                <div class="mb-8 bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            @if(Auth::user()->profile_picture)
                                <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" 
                                     alt="Profile Picture" class="w-16 h-16 rounded-full object-cover border-2 border-gray-200">
                            @else
                                <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-user text-gray-400 text-xl"></i>
                                </div>
                            @endif
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900">{{ Auth::user()->name }}</h4>
                                <p class="text-gray-600">{{ Auth::user()->department }}</p>
                                <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        <a href="{{ route('teacher.profile') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Profile
                        </a>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mb-8">
                    <div class="flex space-x-4">
                        <a href="{{ route('teacher.subjects.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            <i class="fas fa-plus mr-2"></i>
                            Create Subject
                        </a>
                        <a href="{{ route('teacher.subjects') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-list mr-2"></i>
                            View All Subjects
                        </a>
                        <a href="{{ route('teacher.subjects.archived') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-archive mr-2"></i>
                            Archived Subjects
                        </a>
                    </div>
                </div>

                <!-- Active Sessions -->
                @if(count($activeSessions) > 0)
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Active Attendance Sessions</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($activeSessions as $session)
                                <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-lg font-semibold text-green-900">{{ $session->subject->name }}</h4>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-green-700">Code:</span>
                                            <span class="font-mono font-bold text-green-900">{{ $session->code }}</span>
                                        </div>
                                        
                                        <div class="flex justify-between">
                                            <span class="text-green-700">Started:</span>
                                            <span class="text-green-900">{{ $session->start_time->format('M d, Y H:i') }}</span>
                                        </div>
                                        
                                        <div class="flex justify-between">
                                            <span class="text-green-700">Attendees:</span>
                                            <span class="font-medium text-green-900">{{ $session->attendances->count() }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4 flex space-x-2">
                                        <a href="{{ route('teacher.sessions.show', $session) }}" class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200">
                                            <i class="fas fa-eye mr-1"></i>
                                            View
                                        </a>
                                        <form method="POST" action="{{ route('teacher.sessions.end', $session) }}" class="flex-1">
                                            @csrf
                                            <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200">
                                                <i class="fas fa-stop mr-1"></i>
                                                End
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Subjects -->
                @if(count($subjects) > 0)
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Your Subjects</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($subjects as $subject)
                                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-lg font-semibold text-gray-900">{{ $subject->name }}</h4>
                                        <span class="text-sm text-gray-500">{{ $subject->code }}</span>
                                    </div>
                                    
                                    <div class="space-y-2 mb-4">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Schedules:</span>
                                            <span class="font-medium">{{ $subject->schedules->count() }}</span>
                                        </div>
                                        
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Total Sessions:</span>
                                            <span class="font-medium">{{ $subject->attendanceSessions->count() }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex space-x-2">
                                        <a href="{{ route('teacher.subjects.show', $subject) }}" class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                            <i class="fas fa-eye mr-1"></i>
                                            View
                                        </a>
                                        <form method="POST" action="{{ route('teacher.subjects.archive', $subject) }}" class="flex-1" onsubmit="return confirmArchive(event)">
                                            @csrf
                                            <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700">
                                                <i class="fas fa-archive mr-1"></i>
                                                Archive
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-book text-4xl text-gray-400 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Subjects Yet</h3>
                        <p class="text-gray-600">Create your first subject to start managing attendance.</p>
                        <div class="mt-4">
                            <a href="{{ route('teacher.subjects.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                <i class="fas fa-plus mr-2"></i>
                                Create Subject
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 