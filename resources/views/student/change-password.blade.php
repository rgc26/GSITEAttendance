@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">
                        <i class="fas fa-key mr-2"></i>
                        Change Password
                    </h2>
                    <a href="{{ route('student.profile') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Profile
                    </a>
                </div>

                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <div class="max-w-md mx-auto">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Important Information</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>After changing your password, you will receive an email verification link. Please check your email and click the verification link to complete the process.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('student.password.update') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password <span class="text-red-500">*</span></label>
                            <input type="password" 
                                   id="current_password" 
                                   name="current_password" 
                                   required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                   placeholder="Enter your current password"
                                   minlength="1"
                                   maxlength="128">
                            <p class="mt-1 text-xs text-gray-500">Enter your current password to verify your identity</p>
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password <span class="text-red-500">*</span></label>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                   placeholder="Enter your new password"
                                   minlength="8"
                                   maxlength="128"
                                   pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                                   title="Password must contain at least 8 characters, including uppercase, lowercase, number, and special character">
                            <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <h4 class="text-sm font-medium text-blue-800 mb-2">Password Requirements:</h4>
                                <ul class="text-xs text-blue-700 space-y-1">
                                    <li class="flex items-center">
                                        <span id="length-check" class="w-4 h-4 mr-2 rounded-full border-2 border-gray-300"></span>
                                        Minimum 8 characters
                                    </li>
                                    <li class="flex items-center">
                                        <span id="uppercase-check" class="w-4 h-4 mr-2 rounded-full border-2 border-gray-300"></span>
                                        At least 1 uppercase letter (A-Z)
                                    </li>
                                    <li class="flex items-center">
                                        <span id="lowercase-check" class="w-4 h-4 mr-2 rounded-full border-2 border-gray-300"></span>
                                        At least 1 lowercase letter (a-z)
                                    </li>
                                    <li class="flex items-center">
                                        <span id="number-check" class="w-4 h-4 mr-2 rounded-full border-2 border-gray-300"></span>
                                        At least 1 number (0-9)
                                    </li>
                                    <li class="flex items-center">
                                        <span id="special-check" class="w-4 h-4 mr-2 rounded-full border-2 border-gray-300"></span>
                                        At least 1 special character (@$!%*?&)
                                    </li>
                                </ul>
                            </div>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Confirm your new password">
                            @error('password_confirmation')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('student.profile') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                <i class="fas fa-key mr-2"></i>
                                Change Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const lengthCheck = document.getElementById('length-check');
    const uppercaseCheck = document.getElementById('uppercase-check');
    const lowercaseCheck = document.getElementById('lowercase-check');
    const numberCheck = document.getElementById('number-check');
    const specialCheck = document.getElementById('special-check');

    function updatePasswordChecks(password) {
        // Length check (8+ characters)
        if (password.length >= 8) {
            lengthCheck.className = 'w-4 h-4 mr-2 rounded-full bg-green-500 border-2 border-green-500';
            lengthCheck.innerHTML = '✓';
        } else {
            lengthCheck.className = 'w-4 h-4 mr-2 rounded-full border-2 border-gray-300';
            lengthCheck.innerHTML = '';
        }

        // Uppercase check
        if (/[A-Z]/.test(password)) {
            uppercaseCheck.className = 'w-4 h-4 mr-2 rounded-full bg-green-500 border-2 border-green-500';
            uppercaseCheck.innerHTML = '✓';
        } else {
            uppercaseCheck.className = 'w-4 h-4 mr-2 rounded-full border-2 border-gray-300';
            uppercaseCheck.innerHTML = '';
        }

        // Lowercase check
        if (/[a-z]/.test(password)) {
            lowercaseCheck.className = 'w-4 h-4 mr-2 rounded-full bg-green-500 border-2 border-green-500';
            lowercaseCheck.innerHTML = '✓';
        } else {
            lowercaseCheck.className = 'w-4 h-4 mr-2 rounded-full border-2 border-gray-300';
            lowercaseCheck.innerHTML = '';
        }

        // Number check
        if (/\d/.test(password)) {
            numberCheck.className = 'w-4 h-4 mr-2 rounded-full bg-green-500 border-2 border-green-500';
            numberCheck.innerHTML = '✓';
        } else {
            numberCheck.className = 'w-4 h-4 mr-2 rounded-full border-2 border-gray-300';
            numberCheck.innerHTML = '';
        }

        // Special character check
        if (/[@$!%*?&]/.test(password)) {
            specialCheck.className = 'w-4 h-4 mr-2 rounded-full bg-green-500 border-2 border-green-500';
            specialCheck.innerHTML = '✓';
        } else {
            specialCheck.className = 'w-4 h-4 mr-2 rounded-full border-2 border-gray-300';
            specialCheck.innerHTML = '';
        }
    }

    passwordInput.addEventListener('input', function() {
        updatePasswordChecks(this.value);
    });

    // Initial check
    updatePasswordChecks(passwordInput.value);
});
</script>

@endsection 