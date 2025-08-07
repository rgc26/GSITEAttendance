@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Mark Attendance
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Subject: {{ $session->subject->name }}
            </p>
            <p class="mt-1 text-center text-sm text-gray-500">
                Code: {{ $session->code }}
            </p>
            <p class="mt-1 text-center text-sm text-gray-500">
                Session Type: <span class="font-medium text-indigo-600">{{ ucfirst($session->session_type ?? 'lecture') }}</span>
            </p>
        </div>
        
        <form class="mt-8 space-y-6" action="{{ route('student.attendance.mark', $session->code) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <!-- Attendance Code (always required) -->
                <div>
                    <label for="attendance_code" class="block text-sm font-medium text-gray-700">Attendance Code</label>
                    <input id="attendance_code" name="attendance_code" type="text" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                           placeholder="Enter the attendance code provided by your teacher">
                </div>

                @php
                    $sessionType = $session->session_type ?? 'lecture';
                @endphp

                <!-- Lab Session Requirements -->
                @if($sessionType === 'lab')
                    <div>
                        <label for="pc_number" class="block text-sm font-medium text-gray-700">PC Number</label>
                        <input id="pc_number" name="pc_number" type="text" required 
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                               placeholder="Enter PC number (e.g., PC01, PC02)">
                    </div>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-desktop text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Lab Session Requirements</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li>Make sure you're in the correct lab/room</li>
                                        <li>Enter the PC number you're using</li>
                                        <li>You can only mark attendance once per session</li>
                                        <li>Attendance will be recorded with your current time</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Online Session Requirements -->
                @if($sessionType === 'online')
                    <div>
                        <label for="device_type" class="block text-sm font-medium text-gray-700">Device Type</label>
                        <select id="device_type" name="device_type" required 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Select your device type</option>
                            <option value="mobile">Mobile Phone</option>
                            <option value="desktop">Desktop Computer</option>
                            <option value="laptop">Laptop</option>
                        </select>
                    </div>
                    
                    <div class="bg-green-50 border border-green-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-wifi text-green-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">Online Session Requirements</h3>
                                <div class="mt-2 text-sm text-green-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li>Select the device type you're using</li>
                                        <li>Make sure you have a stable internet connection</li>
                                        <li>You can only mark attendance once per session</li>
                                        <li>Attendance will be recorded with your current time</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Lecture Session Requirements -->
                @if($sessionType === 'lecture')
                    <div>
                        <label for="attached_image" class="block text-sm font-medium text-gray-700">Attached Image</label>
                        <input id="attached_image" name="attached_image" type="file" accept="image/*" required 
                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <p class="mt-1 text-sm text-gray-500">Upload an image as instructed by your teacher</p>
                    </div>
                    
                    <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-image text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Lecture Session Requirements</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li>Upload an image as instructed by your teacher</li>
                                        <li>Make sure the image is clear and relevant</li>
                                        <li>You can only mark attendance once per session</li>
                                        <li>Attendance will be recorded with your current time</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <i class="fas fa-check mr-2"></i>
                    Mark Attendance
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 