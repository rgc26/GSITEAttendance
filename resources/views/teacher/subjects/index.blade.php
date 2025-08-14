@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200 mobile-friendly-container">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-3xl font-bold text-gray-900">
                        <i class="fas fa-book mr-3"></i>
                        My Subjects
                    </h2>
                    <a href="{{ route('teacher.subjects.create') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Create Subject
                    </a>
                </div>

                @if(session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @if($subjects->count() > 0)
                    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($subjects as $subject)
                            <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                                <div class="p-6">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex-1">
                                            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $subject->name }}</h3>
                                            <p class="text-lg font-mono text-indigo-600 mb-2">{{ $subject->code }}</p>
                                            @if($subject->description)
                                                <p class="text-gray-600 text-base leading-relaxed">{{ $subject->description }}</p>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 mb-6 text-center">
                                        <div class="bg-blue-50 rounded-lg p-3">
                                            <div class="text-2xl font-bold text-blue-600">{{ $subject->schedules->count() }}</div>
                                            <div class="text-sm text-blue-600">Schedules</div>
                                        </div>
                                        <div class="bg-green-50 rounded-lg p-3">
                                            <div class="text-2xl font-bold text-green-600">{{ $subject->attendanceSessions->count() }}</div>
                                            <div class="text-sm text-green-600">Sessions</div>
                                        </div>
                                    </div>

                                    <div class="flex space-x-3">
                                        <a href="{{ route('teacher.subjects.show', $subject) }}" class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition-colors duration-200">
                                            <i class="fas fa-eye mr-2"></i>
                                            View Details
                                        </a>
                                        <a href="{{ route('teacher.subjects.edit', $subject) }}" class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                                            <i class="fas fa-edit mr-2"></i>
                                            Edit
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="bg-gray-50 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-book text-3xl text-gray-400"></i>
                        </div>
                        <h3 class="text-xl font-medium text-gray-900 mb-2">No Subjects Yet</h3>
                        <p class="text-gray-600 text-base mb-6">Create your first subject to get started with attendance tracking.</p>
                        <a href="{{ route('teacher.subjects.create') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition-colors duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            Create Your First Subject
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 