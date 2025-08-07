@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">
                        <i class="fas fa-user-graduate mr-2"></i>
                        Student Profile
                    </h2>
                    <a href="{{ route('student.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Dashboard
                    </a>
                </div>

                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Profile Picture Section -->
                    <div class="lg:col-span-1">
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Profile Picture</h3>
                            <div class="text-center">
                                @if($user->profile_picture)
                                    <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" class="w-32 h-32 rounded-full border-4 border-white shadow-lg object-cover mx-auto mb-4">
                                @else
                                    <div class="w-32 h-32 rounded-full bg-blue-500 flex items-center justify-center border-4 border-white shadow-lg mx-auto mb-4">
                                        <i class="fas fa-user text-white text-4xl"></i>
                                    </div>
                                @endif
                                <p class="text-sm text-gray-600">Profile pictures are updated in the form below</p>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Information Section -->
                    <div class="lg:col-span-2">
                        <div class="bg-white border border-gray-200 rounded-lg">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900">Profile Information</h3>
                                <p class="text-sm text-gray-600 mt-1">Update your student information</p>
                            </div>
                            
                            <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                                @csrf
                                @method('PUT')

                                <!-- Profile Picture Upload -->
                                <div>
                                    <label for="profile_picture" class="block text-sm font-medium text-gray-700 mb-2">Update Profile Picture (Optional)</label>
                                    <input type="file" id="profile_picture" name="profile_picture" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    <p class="mt-1 text-sm text-gray-500">JPEG, PNG, JPG, GIF up to 2MB</p>
                                </div>

                                <!-- Read-only Information -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                        <input type="text" value="{{ $user->name }}" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-500" readonly>
                                        <p class="mt-1 text-xs text-gray-500">Name cannot be changed</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                        <input type="email" value="{{ $user->email }}" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-500" readonly>
                                        <p class="mt-1 text-xs text-gray-500">Email cannot be changed</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Student ID</label>
                                        <input type="text" value="{{ $user->student_id }}" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-500" readonly>
                                        <p class="mt-1 text-xs text-gray-500">Student ID cannot be changed</p>
                                    </div>
                                </div>

                                <!-- Editable Information -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label for="year_level" class="block text-sm font-medium text-gray-700 mb-2">Year Level</label>
                                        <select id="year_level" name="year_level" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                            <option value="">Select Year Level</option>
                                            <option value="1st Year" {{ $user->year_level == '1st Year' ? 'selected' : '' }}>1st Year</option>
                                            <option value="2nd Year" {{ $user->year_level == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                            <option value="3rd Year" {{ $user->year_level == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                            <option value="4th Year" {{ $user->year_level == '4th Year' ? 'selected' : '' }}>4th Year</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="section" class="block text-sm font-medium text-gray-700 mb-2">Section</label>
                                        <input type="text" id="section" name="section" value="{{ $user->section }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g., 301, 302, A1, B2">
                                    </div>
                                    <div>
                                        <label for="student_type" class="block text-sm font-medium text-gray-700 mb-2">Student Type</label>
                                        <select id="student_type" name="student_type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                            <option value="">Select Type</option>
                                            <option value="regular" {{ $user->student_type == 'regular' ? 'selected' : '' }}>Regular</option>
                                            <option value="irregular" {{ $user->student_type == 'irregular' ? 'selected' : '' }}>Irregular</option>
                                            <option value="block" {{ $user->student_type == 'block' ? 'selected' : '' }}>Block</option>
                                        </select>
                                    </div>
                                </div>

                                @if ($errors->any())
                                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                                        <ul class="list-disc list-inside">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="flex justify-end space-x-3">
                                    <a href="{{ route('student.change-password') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        <i class="fas fa-key mr-2"></i>
                                        Change Password
                                    </a>
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                        <i class="fas fa-save mr-2"></i>
                                        Update Profile
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 