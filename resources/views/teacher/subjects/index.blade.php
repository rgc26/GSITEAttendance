@extends('layouts.app')

@section('content')
<div class="w-full mx-auto px-4 sm:px-6 lg:px-8 main-container">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg enhanced-card">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="page-title">
                    <i class="fas fa-book mr-3 text-indigo-600"></i>
                    My Subjects
                </h1>
                <a href="{{ route('teacher.subjects.create') }}" class="enhanced-button bg-indigo-500 text-white hover:bg-indigo-600">
                    <i class="fas fa-plus mr-2"></i>
                    Create Subject
                </a>
            </div>

            @if($subjects->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($subjects as $subject)
                        <div class="enhanced-card hover:shadow-lg transition-shadow duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="card-title text-indigo-600">{{ $subject->name }}</h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    {{ $subject->code }}
                                </span>
                            </div>
                            
                            <p class="body-text mb-4">{{ $subject->description ?? 'No description available.' }}</p>
                            
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-blue-600">{{ $subject->schedules->count() }}</div>
                                    <div class="small-text">Schedules</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-green-600">{{ $subject->attendanceSessions->count() }}</div>
                                    <div class="small-text">Sessions</div>
                                </div>
                            </div>
                            
                            <div class="flex space-x-2">
                                <a href="{{ route('teacher.subjects.show', $subject) }}" class="enhanced-button bg-indigo-500 text-white hover:bg-indigo-600 flex-1 justify-center">
                                    <i class="fas fa-eye mr-2"></i>
                                    View
                                </a>
                                <a href="{{ route('teacher.subjects.edit', $subject) }}" class="enhanced-button bg-gray-500 text-white hover:bg-gray-600 flex-1 justify-center">
                                    <i class="fas fa-edit mr-2"></i>
                                    Edit
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-book text-6xl text-gray-300 mb-4"></i>
                    <h3 class="card-title text-gray-500">No Subjects Yet</h3>
                    <p class="body-text text-gray-400 mb-6">Create your first subject to get started with attendance management.</p>
                    <a href="{{ route('teacher.subjects.create') }}" class="enhanced-button bg-indigo-500 text-white hover:bg-indigo-600">
                        <i class="fas fa-plus mr-2"></i>
                        Create Your First Subject
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 