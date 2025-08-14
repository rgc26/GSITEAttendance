@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">
                        <i class="fas fa-book mr-2"></i>
                        My Subjects
                    </h2>
                    <div class="flex space-x-3">
                        <a href="{{ route('teacher.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-home mr-2"></i>
                            Back to Dashboard
                        </a>
                        <a href="{{ route('teacher.subjects.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            <i class="fas fa-plus mr-2"></i>
                            Create Subject
                        </a>
                    </div>
                </div>

                @if(count($subjects) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($subjects as $subject)
                            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $subject->name }}</h3>
                                    <span class="text-sm text-gray-500">{{ $subject->code }}</span>
                                </div>
                                
                                @if($subject->description)
                                    <p class="text-gray-600 mb-4">{{ $subject->description }}</p>
                                @endif
                                
                                <div class="space-y-2 mb-4">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Schedules:</span>
                                        <span class="font-medium">{{ $subject->schedules->count() }}</span>
                                    </div>
                                    
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Total Sessions:</span>
                                        <span class="font-medium">{{ $subject->attendanceSessions->count() }}</span>
                                    </div>
                                    
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Active Sessions:</span>
                                        <span class="font-medium text-green-600">{{ $subject->attendanceSessions->where('is_active', true)->count() }}</span>
                                    </div>
                                </div>
                                
                                <div class="flex space-x-2">
                                    <a href="{{ route('teacher.subjects.show', $subject) }}" class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                        <i class="fas fa-eye mr-1"></i>
                                        View Details
                                    </a>
                                    <a href="{{ route('teacher.subjects.edit', $subject) }}" class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        <i class="fas fa-edit mr-1"></i>
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('teacher.subjects.delete', $subject) }}" class="flex-1" onsubmit="return confirm('Are you sure you want to delete this subject? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                            <i class="fas fa-trash mr-1"></i>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
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