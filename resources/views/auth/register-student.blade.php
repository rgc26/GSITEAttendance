@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Student Registration
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Create your student account
            </p>
        </div>
        <form class="mt-8 space-y-6" action="{{ route('register.student') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Profile Picture Upload -->
            <div class="text-center">
                <div class="mb-4">
                    <label for="profile_picture" class="block text-sm font-medium text-gray-700 mb-2">Profile Picture (Optional)</label>
                    <div class="flex justify-center">
                        <div class="relative">
                            <img id="preview" src="https://via.placeholder.com/150x150?text=Upload+Photo" alt="Profile Preview" class="w-32 h-32 rounded-full border-4 border-gray-200 object-cover">
                            <label for="profile_picture" class="absolute bottom-0 right-0 bg-blue-500 text-white rounded-full p-2 cursor-pointer hover:bg-blue-600">
                                <i class="fas fa-camera text-sm"></i>
                            </label>
                        </div>
                    </div>
                    <input type="file" id="profile_picture" name="profile_picture" accept="image/*" class="hidden" onchange="previewImage(this)">
                    <p class="text-xs text-gray-500 mt-2">Click the camera icon to upload a profile picture (JPEG, PNG, JPG, GIF up to 2MB)</p>
                </div>
            </div>

            <div class="rounded-md shadow-sm -space-y-px">
                <div class="grid grid-cols-3 gap-0">
                    <div>
                        <label for="last_name" class="sr-only">Last Name <span class="text-red-500">*</span></label>
                        <input id="last_name" name="last_name" type="text" required maxlength="255" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm border-r-0" placeholder="Last Name" value="{{ old('last_name') }}" title="Last name is required and cannot exceed 255 characters">
                    </div>
                    <div>
                        <label for="first_name" class="sr-only">First Name <span class="text-red-500">*</span></label>
                        <input id="first_name" name="first_name" type="text" required maxlength="255" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm border-r-0" placeholder="First Name" value="{{ old('first_name') }}" title="First name is required and cannot exceed 255 characters">
                    </div>
                    <div>
                        <label for="middle_initial" class="sr-only">Middle Initial</label>
                        <input id="middle_initial" name="middle_initial" type="text" maxlength="10" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="M.I. (Optional)" value="{{ old('middle_initial') }}" title="Middle initial cannot exceed 10 characters">
                    </div>
                </div>
                <div>
                    <label for="email" class="sr-only">Email address <span class="text-red-500">*</span></label>
                    <input id="email" name="email" type="email" autocomplete="email" required maxlength="255" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Email address" value="{{ old('email') }}" title="Email is required and must be a valid email address">
                </div>
                <div>
                    <label for="student_id" class="sr-only">Student ID <span class="text-red-500">*</span></label>
                    <input id="student_id" name="student_id" type="text" required maxlength="255" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Student ID" value="{{ old('student_id') }}" title="Student ID is required and cannot exceed 255 characters">
                </div>
                <div class="grid grid-cols-3 gap-0">
                    <div>
                        <label for="year_level" class="sr-only">Year Level <span class="text-red-500">*</span></label>
                        <select id="year_level" name="year_level" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm border-r-0" title="Please select a valid year level">
                            <option value="">Select Year Level</option>
                            <option value="1st Year" {{ old('year_level') == '1st Year' ? 'selected' : '' }}>1st Year</option>
                            <option value="2nd Year" {{ old('year_level') == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                            <option value="3rd Year" {{ old('year_level') == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                            <option value="4th Year" {{ old('year_level') == '4th Year' ? 'selected' : '' }}>4th Year</option>
                        </select>
                    </div>
                    <div>
                        <label for="section" class="sr-only">Section <span class="text-red-500">*</span></label>
                        <input id="section" name="section" type="text" required maxlength="3" pattern="[0-9]{3}" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm border-r-0" placeholder="Section (e.g., 301, 302, 303)" value="{{ old('section') }}" title="Section must be exactly 3 digits (e.g., 301, 302, 303)" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 3)">
                        <p class="text-xs text-gray-500 mt-1">Section must be exactly 3 digits (e.g., 301, 302, 303)</p>
                    </div>
                    <div>
                        <label for="student_type" class="sr-only">Student Type <span class="text-red-500">*</span></label>
                        <select id="student_type" name="student_type" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" title="Please select a valid student type">
                            <option value="">Select Type</option>
                            <option value="regular" {{ old('student_type') == 'regular' ? 'selected' : '' }}>Regular</option>
                            <option value="irregular" {{ old('student_type') == 'irregular' ? 'selected' : '' }}>Irregular</option>
                            <option value="block" {{ old('student_type') == 'block' ? 'selected' : '' }}>Block</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label for="password" class="sr-only">Password <span class="text-red-500">*</span></label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required minlength="8" maxlength="128" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Password" title="Password must contain at least 8 characters, including uppercase, lowercase, number, and special character">
                </div>
                <div>
                    <label for="password_confirmation" class="sr-only">Confirm Password <span class="text-red-500">*</span></label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required minlength="8" maxlength="128" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Confirm Password" title="Password confirmation is required">
                </div>
            </div>

            <!-- Password Requirements Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="text-sm font-medium text-blue-900 mb-3">
                    <i class="fas fa-info-circle mr-2"></i>
                    Password Requirements
                </h4>
                <div class="grid grid-cols-2 gap-2 text-xs text-blue-700">
                    <div class="flex items-center">
                        <span id="length-check" class="mr-2">❌</span>
                        <span>At least 8 characters</span>
                    </div>
                    <div class="flex items-center">
                        <span id="uppercase-check" class="mr-2">❌</span>
                        <span>1 uppercase letter (A-Z)</span>
                    </div>
                    <div class="flex items-center">
                        <span id="lowercase-check" class="mr-2">❌</span>
                        <span>1 lowercase letter (a-z)</span>
                    </div>
                    <div class="flex items-center">
                        <span id="number-check" class="mr-2">❌</span>
                        <span>1 number (0-9)</span>
                    </div>
                    <div class="flex items-center">
                        <span id="special-check" class="mr-2">❌</span>
                        <span>1 special character (@$!%*?&)</span>
                    </div>
                    <div class="flex items-center">
                        <span id="match-check" class="mr-2">❌</span>
                        <span>Passwords match</span>
                    </div>
                </div>
            </div>

            <div class="flex justify-center">
                <div class="h-captcha" data-sitekey="{{ config('services.hcaptcha.site_key') }}"></div>
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

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Register
                </button>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                        Sign in here
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Password validation
document.addEventListener('DOMContentLoaded', function() {
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('password_confirmation');
    
    function validatePassword() {
        const value = password.value;
        const confirmValue = confirmPassword.value;
        
        // Length check
        document.getElementById('length-check').textContent = value.length >= 8 ? '✅' : '❌';
        
        // Uppercase check
        document.getElementById('uppercase-check').textContent = /[A-Z]/.test(value) ? '✅' : '❌';
        
        // Lowercase check
        document.getElementById('lowercase-check').textContent = /[a-z]/.test(value) ? '✅' : '❌';
        
        // Number check
        document.getElementById('number-check').textContent = /\d/.test(value) ? '✅' : '❌';
        
        // Special character check
        document.getElementById('special-check').textContent = /[@$!%*?&]/.test(value) ? '✅' : '❌';
        
        // Match check
        document.getElementById('match-check').textContent = value === confirmValue && value.length > 0 ? '✅' : '❌';
    }
    
    password.addEventListener('input', validatePassword);
    confirmPassword.addEventListener('input', validatePassword);
    
    // Section field validation - only allow numbers
    const sectionField = document.getElementById('section');
    sectionField.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 3);
    });
});
</script>
@endsection

@push('scripts')
<script src="https://js.hcaptcha.com/1/api.js" async defer></script>
@endpush 