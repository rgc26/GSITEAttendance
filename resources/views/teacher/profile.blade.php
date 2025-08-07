@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Teacher Profile</h2>
                    <p class="text-gray-600">Manage your account information</p>
                </div>
                <a href="{{ route('teacher.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Dashboard
                </a>
            </div>
        </div>
        
        <div class="p-6">
            <form action="{{ route('teacher.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Profile Picture Section -->
                    <div class="md:col-span-2">
                        <div class="flex items-center space-x-6">
                            <div class="flex-shrink-0">
                                @if($teacher->profile_picture)
                                    <img id="profile-preview" src="{{ asset('storage/' . $teacher->profile_picture) }}" 
                                         alt="Profile Picture" class="w-24 h-24 rounded-full object-cover border-4 border-gray-200">
                                @else
                                    <div id="profile-preview" class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-user text-gray-400 text-2xl"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <label for="profile_picture" class="block text-sm font-medium text-gray-700 mb-2">
                                    Profile Picture
                                </label>
                                <input type="file" id="profile_picture" name="profile_picture" 
                                       accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                <p class="mt-1 text-sm text-gray-500">Upload a profile picture (JPEG, PNG, JPG, GIF up to 2MB)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $teacher->name) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $teacher->email) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Department -->
                    <div>
                        <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                        <input type="text" id="department" name="department" value="{{ old('department', $teacher->department) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('department')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role (Read-only) -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                        <input type="text" id="role" value="{{ ucfirst($teacher->role) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-500" readonly>
                    </div>
                </div>

                <div class="mt-6 flex justify-between items-center">
                    <a href="{{ route('teacher.change-password') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">
                        <i class="fas fa-key mr-1"></i>Change Password
                    </a>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <i class="fas fa-save mr-2"></i>Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('profile_picture').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('profile-preview');
            if (preview.tagName === 'IMG') {
                preview.src = e.target.result;
            } else {
                preview.innerHTML = `<img src="${e.target.result}" alt="Profile Picture" class="w-24 h-24 rounded-full object-cover border-4 border-gray-200">`;
            }
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection 