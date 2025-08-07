@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">
                        <i class="fas fa-calendar-plus mr-2"></i>
                        Add Schedule
                    </h2>
                    <a href="{{ route('teacher.subjects.show', $subject) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Subject
                    </a>
                </div>

                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Subject Information</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p><strong>Subject:</strong> {{ $subject->name }} ({{ $subject->code }})</p>
                                <p><strong>Teacher:</strong> {{ Auth::user()->name }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('teacher.schedules.store', $subject) }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="day" class="block text-sm font-medium text-gray-700">Day of Week</label>
                            <select name="day" id="day" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Day</option>
                                <option value="monday" {{ old('day') == 'monday' ? 'selected' : '' }}>Monday</option>
                                <option value="tuesday" {{ old('day') == 'tuesday' ? 'selected' : '' }}>Tuesday</option>
                                <option value="wednesday" {{ old('day') == 'wednesday' ? 'selected' : '' }}>Wednesday</option>
                                <option value="thursday" {{ old('day') == 'thursday' ? 'selected' : '' }}>Thursday</option>
                                <option value="friday" {{ old('day') == 'friday' ? 'selected' : '' }}>Friday</option>
                                <option value="saturday" {{ old('day') == 'saturday' ? 'selected' : '' }}>Saturday</option>
                                <option value="sunday" {{ old('day') == 'sunday' ? 'selected' : '' }}>Sunday</option>
                            </select>
                        </div>

                        <div>
                            <label for="section" class="block text-sm font-medium text-gray-700">Section</label>
                            <input type="text" name="section" id="section" required value="{{ old('section') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="e.g., BSIT 3A, BSCS 2B">
                            <p class="mt-1 text-sm text-gray-500">Target section for this schedule</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700">Session Type</label>
                            <select name="type" id="type" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Type</option>
                                <option value="lecture" {{ old('type') == 'lecture' ? 'selected' : '' }}>Lecture</option>
                                <option value="lab" {{ old('type') == 'lab' ? 'selected' : '' }}>Lab</option>
                                <option value="online" {{ old('type') == 'online' ? 'selected' : '' }}>Online</option>
                            </select>
                        </div>

                        <div>
                            <label for="room" class="block text-sm font-medium text-gray-700">Room/Location (Optional)</label>
                            <input type="text" name="room" id="room" value="{{ old('room') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="e.g., Room 101, Lab A, Online">
                            <p class="mt-1 text-sm text-gray-500">Optional room or location information</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
                            <input type="time" name="start_time" id="start_time" required value="{{ old('start_time') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700">End Time</label>
                            <input type="time" name="end_time" id="end_time" required value="{{ old('end_time') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Schedule Information</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li>This schedule will be used to organize attendance sessions</li>
                                        <li>Students can only mark attendance during scheduled times</li>
                                        <li>You can create multiple schedules for different days/times</li>
                                        <li>Lab sessions typically require PC numbers for attendance</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('teacher.subjects.show', $subject) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            <i class="fas fa-save mr-2"></i>
                            Add Schedule
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 