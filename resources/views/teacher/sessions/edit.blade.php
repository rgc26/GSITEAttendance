@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Attendance Session
                    </h2>
                </div>

                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Session Information</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p><strong>Subject:</strong> {{ $session->subject->name }} ({{ $session->subject->code }})</p>
                                <p><strong>Code:</strong> {{ $session->code }}</p>
                                <p><strong>Status:</strong> 
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $session->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $session->is_active ? 'Active' : 'Ended' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('teacher.sessions.update', $session) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Session Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $session->name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="e.g., Week 1 - Introduction, Lab Session 1, etc.">
                        <p class="mt-1 text-sm text-gray-500">Give your session a descriptive name for easy identification</p>
                    </div>

                    <div>
                        <label for="section" class="block text-sm font-medium text-gray-700">Target Section *</label>
                        <input type="text" name="section" id="section" value="{{ old('section', $session->section) }}" required 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                               placeholder="e.g., 301, 302, 303, A1, B2, etc.">
                        <p class="mt-1 text-sm text-gray-500">Enter the target section for this attendance session. The system will find all students registered with this section.</p>
                    </div>

                    <div>
                        <label for="session_type" class="block text-sm font-medium text-gray-700">Session Type *</label>
                        <select name="session_type" id="session_type" required 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Select session type</option>
                            <option value="lecture" {{ old('session_type', $session->session_type) == 'lecture' ? 'selected' : '' }}>Lecture</option>
                            <option value="lab" {{ old('session_type', $session->session_type) == 'lab' ? 'selected' : '' }}>Lab</option>
                            <option value="online" {{ old('session_type', $session->session_type) == 'online' ? 'selected' : '' }}>Online</option>
                        </select>
                        <p class="mt-1 text-sm text-gray-500">Select the type of session. This will determine what students need to provide when marking attendance.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="scheduled_start_time" class="block text-sm font-medium text-gray-700">Scheduled Start Time</label>
                            <input type="datetime-local" name="scheduled_start_time" id="scheduled_start_time" 
                                value="{{ old('scheduled_start_time', $session->scheduled_start_time ? $session->scheduled_start_time->format('Y-m-d\TH:i') : '') }}" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="mt-1 text-sm text-gray-500">When the session should start (for time-based attendance)</p>
                        </div>

                        <div>
                            <label for="scheduled_end_time" class="block text-sm font-medium text-gray-700">Scheduled End Time</label>
                            <input type="datetime-local" name="scheduled_end_time" id="scheduled_end_time" 
                                value="{{ old('scheduled_end_time', $session->scheduled_end_time ? $session->scheduled_end_time->format('Y-m-d\TH:i') : '') }}" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="mt-1 text-sm text-gray-500">When the session should end</p>
                        </div>
                    </div>

                    <div>
                        <label for="grace_period_minutes" class="block text-sm font-medium text-gray-700">Grace Period (Minutes)</label>
                        <input type="number" name="grace_period_minutes" id="grace_period_minutes" 
                            value="{{ old('grace_period_minutes', $session->grace_period_minutes) }}" 
                            min="0" max="60" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <p class="mt-1 text-sm text-gray-500">Students can mark attendance as 'late' within this time after the scheduled start</p>
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Session Notes</label>
                        <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="e.g., Lab session, Online lecture, etc.">{{ old('notes', $session->notes) }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Optional notes about this attendance session</p>
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Important Information</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li>You can edit session details even after ending the session</li>
                                        <li>Changes to scheduled times will affect attendance status calculations</li>
                                        <li>The attendance code cannot be changed</li>
                                        <li>Existing attendance records will be updated with new status calculations</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <i class="fas fa-save mr-2"></i>
                            Update Session
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 