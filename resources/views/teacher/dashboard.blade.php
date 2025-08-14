@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200 mobile-friendly-container">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-3xl font-bold text-gray-900">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        Teacher Dashboard
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
                                <div class="text-sm text-blue-600">Total Subjects</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 rounded-lg p-6 border border-green-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-calendar-alt text-2xl text-green-600"></i>
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
                                <i class="fas fa-users text-2xl text-purple-600"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-lg font-semibold text-purple-900">{{ $totalStudents }}</div>
                                <div class="text-sm text-purple-600">Total Students</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-50 rounded-lg p-6 border border-yellow-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-clock text-2xl text-yellow-600"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-lg font-semibold text-yellow-900">{{ $activeSessions }}</div>
                                <div class="text-sm text-yellow-600">Active Sessions</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">
                        <i class="fas fa-history mr-2"></i>
                        Recent Activity
                    </h3>
                    <div class="space-y-4">
                        @forelse($recentSessions as $session)
                            <div class="flex items-center justify-between p-4 bg-white rounded-lg border border-gray-200">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-qrcode text-lg text-blue-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-base font-medium text-gray-900">{{ $session->name ?? 'Untitled Session' }}</div>
                                        <div class="text-sm text-gray-500">{{ $session->subject->name }} - {{ $session->section }}</div>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $session->created_at->diffForHumans() }}
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-4"></i>
                                <p class="text-lg">No recent activity</p>
                                <p class="text-sm">Start creating sessions to see activity here</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <a href="{{ route('teacher.subjects.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white rounded-lg p-6 transition-colors duration-200">
                        <div class="flex items-center">
                            <i class="fas fa-plus text-2xl mr-4"></i>
                            <div>
                                <div class="text-lg font-semibold">Create Subject</div>
                                <div class="text-blue-100 text-sm">Add a new subject to your portfolio</div>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('teacher.sessions.create') }}" class="bg-green-600 hover:bg-green-700 text-white rounded-lg p-6 transition-colors duration-200">
                        <div class="flex items-center">
                            <i class="fas fa-qrcode text-2xl mr-4"></i>
                            <div>
                                <div class="text-lg font-semibold">Start Session</div>
                                <div class="text-green-100 text-sm">Create a new attendance session</div>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('teacher.reports.attendance') }}" class="bg-purple-600 hover:bg-purple-700 text-white rounded-lg p-6 transition-colors duration-200">
                        <div class="flex items-center">
                            <i class="fas fa-chart-bar text-2xl mr-4"></i>
                            <div>
                                <div class="text-lg font-semibold">View Reports</div>
                                <div class="text-purple-100 text-sm">Check attendance statistics</div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 