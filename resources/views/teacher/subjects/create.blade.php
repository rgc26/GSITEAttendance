@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">
                        <i class="fas fa-plus mr-2"></i>
                        Create New Subject
                    </h2>
                    <a href="{{ route('teacher.subjects') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Subjects
                    </a>
                </div>

                <form action="{{ route('teacher.subjects.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Subject Name</label>
                        <input type="text" name="name" id="name" required value="{{ old('name') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700">Subject Code</label>
                        <input type="text" name="code" id="code" required value="{{ old('code') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="e.g., CS101, MATH201">
                        <p class="mt-1 text-sm text-gray-500">A unique code for the subject (e.g., CS101, MATH201)</p>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('description') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Optional description of the subject</p>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('teacher.subjects') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            <i class="fas fa-save mr-2"></i>
                            Create Subject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 