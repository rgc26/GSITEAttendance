@extends('layouts.app')

@section('content')
<div class="w-full mx-auto px-4 sm:px-6 lg:px-8 main-container">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg enhanced-card">
        <div class="p-6">
            <h1 class="page-title">
                <i class="fas fa-chalkboard-teacher mr-3 text-indigo-600"></i>
                Teacher Dashboard
            </h1>
            
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
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
                            <i class="fas fa-calendar-alt text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="card-title text-white">Active Sessions</h3>
                            <p class="text-2xl font-bold">{{ $activeSessions }}</p>
                        </div>
                    </div>
                </div>

                <div class="enhanced-card bg-gradient-to-r from-purple-500 to-purple-600 text-white">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-users text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="card-title text-white">Total Students</h3>
                            <p class="text-2xl font-bold">{{ $totalStudents }}</p>
                        </div>
                    </div>
                </div>

                <div class="enhanced-card bg-gradient-to-r from-orange-500 to-orange-600 text-white">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-archive text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="card-title text-white">Archived</h3>
                            <p class="text-2xl font-bold">{{ $archivedSubjects }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mb-8">
                <h2 class="section-title">
                    <i class="fas fa-bolt mr-2 text-indigo-600"></i>
                    Quick Actions
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('teacher.subjects.create') }}" class="enhanced-card hover:shadow-lg transition-all duration-300 text-center p-6 border-2 border-dashed border-indigo-300 hover:border-indigo-500 hover:bg-indigo-50">
                        <i class="fas fa-plus text-4xl text-indigo-500 mb-4"></i>
                        <h3 class="card-title text-indigo-600">Create Subject</h3>
                        <p class="body-text text-gray-600">Add a new subject</p>
                    </a>
                    
                    <a href="{{ route('teacher.subjects') }}" class="enhanced-card hover:shadow-lg transition-all duration-300 text-center p-6 border-2 border-dashed border-green-300 hover:border-green-500 hover:bg-green-50">
                        <i class="fas fa-list text-4xl text-green-500 mb-4"></i>
                        <h3 class="card-title text-green-600">Manage Subjects</h3>
                        <p class="body-text text-gray-600">View all subjects</p>
                    </a>
                    
                    <a href="{{ route('teacher.subjects.archived') }}" class="enhanced-card hover:shadow-lg transition-all duration-300 text-center p-6 border-2 border-dashed border-orange-300 hover:border-orange-500 hover:bg-orange-50">
                        <i class="fas fa-archive text-4xl text-orange-500 mb-4"></i>
                        <h3 class="card-title text-orange-600">Archived Subjects</h3>
                        <p class="body-text text-gray-600">View archived</p>
                    </a>
                    
                    <a href="{{ route('teacher.sessions.create') }}" class="enhanced-card hover:shadow-lg transition-all duration-300 text-center p-6 border-2 border-dashed border-purple-300 hover:border-purple-500 hover:bg-purple-50">
                        <i class="fas fa-calendar-plus text-4xl text-purple-500 mb-4"></i>
                        <h3 class="card-title text-purple-600">Start Session</h3>
                        <p class="body-text text-gray-600">Begin attendance</p>
                    </a>
                </div>
            </div>

            <!-- Active Subjects -->
            @if($subjects->count() > 0)
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="section-title">
                            <i class="fas fa-book-open mr-2 text-indigo-600"></i>
                            Active Subjects
                        </h2>
                        <a href="{{ route('teacher.subjects') }}" class="enhanced-button bg-indigo-500 text-white hover:bg-indigo-600">
                            <i class="fas fa-arrow-right mr-2"></i>
                            View All
                        </a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($subjects->take(6) as $subject)
                            <div class="enhanced-card hover:shadow-lg transition-shadow duration-300">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="card-title text-indigo-600">{{ $subject->name }}</h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ $subject->code }}
                                    </span>
                                </div>
                                <p class="body-text mb-4">{{ $subject->description ?? 'No description available.' }}</p>
                                
                                <!-- Subject Stats -->
                                <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                                    <div class="text-center p-2 bg-gray-50 rounded">
                                        <div class="font-semibold text-gray-700">{{ $subject->schedules->count() }}</div>
                                        <div class="text-gray-500">Schedules</div>
                                    </div>
                                    <div class="text-center p-2 bg-gray-50 rounded">
                                        <div class="font-semibold text-gray-700">{{ $subject->attendanceSessions->count() }}</div>
                                        <div class="text-gray-500">Sessions</div>
                                    </div>
                                </div>
                                
                                <div class="flex space-x-2">
                                    <a href="{{ route('teacher.subjects.show', $subject) }}" class="enhanced-button bg-indigo-500 text-white hover:bg-indigo-600 flex-1">
                                        <i class="fas fa-eye mr-2"></i>
                                        View
                                    </a>
                                    <a href="{{ route('teacher.subjects.edit', $subject) }}" class="enhanced-button bg-gray-500 text-white hover:bg-gray-600 flex-1">
                                        <i class="fas fa-edit mr-2"></i>
                                        Edit
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="text-center py-12 mb-8">
                    <i class="fas fa-book text-6xl text-gray-300 mb-4"></i>
                    <h3 class="card-title text-gray-500">No Subjects Yet</h3>
                    <p class="body-text text-gray-400 mb-6">Get started by creating your first subject.</p>
                    <a href="{{ route('teacher.subjects.create') }}" class="enhanced-button bg-indigo-500 text-white hover:bg-indigo-600">
                        <i class="fas fa-plus mr-2"></i>
                        Create Subject
                    </a>
                </div>
            @endif

            <!-- Recent Sessions -->
            @if($recentSessions->count() > 0)
                <div class="mb-8">
                    <h2 class="section-title">
                        <i class="fas fa-history mr-2 text-indigo-600"></i>
                        Recent Sessions
                    </h2>
                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Session Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentSessions->take(5) as $session)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $session->subject->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $session->name ?? 'No name' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($session->is_active)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    Ended
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $session->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('teacher.sessions.show', $session) }}" class="text-indigo-600 hover:text-indigo-900">
                                                <i class="fas fa-eye mr-1"></i>
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- System Status -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h2 class="section-title">
                    <i class="fas fa-info-circle mr-2 text-indigo-600"></i>
                    System Status
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div class="text-center p-3 bg-white rounded-lg">
                        <i class="fas fa-database text-2xl text-green-500 mb-2"></i>
                        <div class="font-semibold">Database</div>
                        <div class="text-green-600">Connected</div>
                    </div>
                    <div class="text-center p-3 bg-white rounded-lg">
                        <i class="fas fa-server text-2xl text-blue-500 mb-2"></i>
                        <div class="font-semibold">Server</div>
                        <div class="text-blue-600">Online</div>
                    </div>
                    <div class="text-center p-3 bg-white rounded-lg">
                        <i class="fas fa-shield-alt text-2xl text-purple-500 mb-2"></i>
                        <div class="font-semibold">Security</div>
                        <div class="text-purple-600">Protected</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 