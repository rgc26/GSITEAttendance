@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-50 to-blue-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <div class="mx-auto h-24 w-24 bg-indigo-600 rounded-full flex items-center justify-center mb-6">
                <i class="fas fa-calendar-check text-white text-3xl"></i>
            </div>
            <h2 class="text-center text-4xl font-extrabold text-gray-900 mb-4">
                GSITE PROJECT: SmartTrack
            </h2>
            <p class="text-center text-lg text-gray-600 mb-8">
                "Attendance Management System"
            </p>
        </div>
        
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="space-y-4">
                <a href="{{ route('login') }}" 
                   class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Sign In
                </a>
            </div>
            
            <div class="mt-6 text-center">
                <p class="text-xs text-gray-500">
                    Secure • Reliable • Easy to Use
                </p>
            </div>
        </div>
        
        <div class="text-center">
            <div class="grid grid-cols-3 gap-4 text-xs text-gray-500 mb-6">
                <div class="flex flex-col items-center">
                    <i class="fas fa-shield-alt text-indigo-600 mb-1"></i>
                    <span>Secure</span>
                </div>
                <div class="flex flex-col items-center">
                    <i class="fas fa-clock text-indigo-600 mb-1"></i>
                    <span>Real-time</span>
                </div>
                <div class="flex flex-col items-center">
                    <i class="fas fa-chart-bar text-indigo-600 mb-1"></i>
                    <span>Analytics</span>
                </div>
            </div>
            
            <div class="border-t border-gray-200 pt-4">
                <p class="text-sm font-medium text-gray-700">
                    Developed by GSITE DEV TEAM
                </p>
            </div>
        </div>
    </div>
</div>
@endsection 