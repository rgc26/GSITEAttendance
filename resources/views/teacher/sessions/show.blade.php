@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">
                        <i class="fas fa-qrcode mr-2"></i>
                        Attendance Session
                    </h2>
                    <div class="flex space-x-3">
                        <a href="{{ route('teacher.subjects.show', $session->subject) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Subject
                        </a>
                        @if($session->is_active)
                            <a href="{{ route('teacher.sessions.edit', $session) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-edit mr-2"></i>
                                Edit Session
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Session Information -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Session Details</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subject:</span>
                                <span class="font-medium">{{ $session->subject->name }} ({{ $session->subject->code }})</span>
                            </div>
                            @if($session->name)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Session Name:</span>
                                    <span class="font-medium">{{ $session->name }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-gray-600">Target Section:</span>
                                <span class="font-bold text-indigo-600">{{ $session->section }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Session Type:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $session->session_type === 'lab' ? 'bg-blue-100 text-blue-800' : ($session->session_type === 'online' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($session->session_type ?? 'lecture') }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Attendance Code:</span>
                                <span class="font-mono font-bold text-lg">{{ $session->code }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $session->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $session->is_active ? 'Active' : 'Ended' }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Started:</span>
                                <span class="font-medium">{{ $session->start_time->format('M d, Y H:i') }}</span>
                            </div>
                            @if($session->scheduled_start_time)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Scheduled Start:</span>
                                    <span class="font-medium">{{ $session->scheduled_start_time->format('M d, Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Grace Period End:</span>
                                    <span class="font-medium">{{ $session->getGracePeriodEndTime()->format('M d, Y H:i') }}</span>
                                </div>
                            @endif
                            @if($session->scheduled_end_time)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Scheduled End:</span>
                                    <span class="font-medium">{{ $session->scheduled_end_time->format('M d, Y H:i') }}</span>
                                </div>
                            @endif
                            @if($session->end_time)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Ended:</span>
                                    <span class="font-medium">{{ $session->end_time->format('M d, Y H:i') }}</span>
                                </div>
                            @endif
                            @if($session->notes)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Notes:</span>
                                    <span class="font-medium">{{ $session->notes }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-green-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-green-900 mb-4">Section {{ $session->section }} Summary</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-green-700">Total Students:</span>
                                <span class="font-bold text-green-900 text-xl">{{ $totalTargetStudents }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-green-700">Present:</span>
                                <span class="font-medium text-green-600">{{ $presentTargetStudents }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-green-700">Absent:</span>
                                <span class="font-medium text-red-600">{{ $absentTargetStudents }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-green-700">Attendance Rate:</span>
                                <span class="font-medium text-green-900">
                                    @if($totalTargetStudents > 0)
                                        {{ round(($presentTargetStudents / $totalTargetStudents) * 100, 1) }}%
                                    @else
                                        0%
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-blue-900 mb-4">Overall Summary</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-blue-700">Total Attendees:</span>
                                <span class="font-bold text-blue-900 text-xl">{{ $attendances->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-blue-700">Regular Students:</span>
                                <span class="font-medium text-green-600">{{ $regularAttendances->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-blue-700">Irregular Students:</span>
                                <span class="font-medium text-yellow-600">{{ $irregularAttendances->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-blue-700">On Time:</span>
                                <span class="font-medium text-green-600">{{ $attendances->where('status', 'present')->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-blue-700">Late:</span>
                                <span class="font-medium text-yellow-600">{{ $attendances->where('status', 'late')->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search Filters -->
                <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                    <h3 class="text-lg font-medium text-blue-900 mb-4">
                        <i class="fas fa-search mr-2"></i>
                        Search & Filter Attendees
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="studentSearch" class="block text-sm font-medium text-blue-700 mb-2">Search Student Name</label>
                            <input type="text" id="studentSearch" placeholder="Enter student name..." 
                                   class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="statusFilter" class="block text-sm font-medium text-blue-700 mb-2">Filter by Status</label>
                            <select id="statusFilter" class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Status</option>
                                <option value="present">Present</option>
                                <option value="late">Late</option>
                                <option value="absent">Absent</option>
                            </select>
                        </div>
                        <div>
                            <label for="sectionFilter" class="block text-sm font-medium text-blue-700 mb-2">Filter by Section</label>
                            <select id="sectionFilter" class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Sections</option>
                                <option value="regular">Regular ({{ $session->section }})</option>
                                <option value="irregular">Irregular (Other Sections)</option>
                            </select>
                        </div>
                        <div>
                            <label for="studentTypeFilter" class="block text-sm font-medium text-blue-700 mb-2">Filter by Student Type</label>
                            <select id="studentTypeFilter" class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Types</option>
                                <option value="regular">Regular</option>
                                <option value="irregular">Irregular</option>
                                <option value="block">Block</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4 flex space-x-2">
                        <button onclick="clearFilters()" class="px-4 py-2 text-sm font-medium text-blue-700 bg-blue-100 rounded-md hover:bg-blue-200">
                            <i class="fas fa-times mr-2"></i>
                            Clear Filters
                        </button>
                        <button onclick="expandAll()" class="px-4 py-2 text-sm font-medium text-green-700 bg-green-100 rounded-md hover:bg-green-200">
                            <i class="fas fa-expand-alt mr-2"></i>
                            Expand All
                        </button>
                        <button onclick="collapseAll()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                            <i class="fas fa-compress-alt mr-2"></i>
                            Collapse All
                        </button>
                    </div>
                </div>

                <!-- Attendees List with Accordion -->
                <div class="space-y-4">
                    <!-- Regular Students Accordion -->
                    @if($regularAttendances->count() > 0)
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <div class="accordion-header bg-green-50 hover:bg-green-100 cursor-pointer transition-colors duration-200">
                                <div class="p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <i class="fas fa-chevron-down accordion-icon mr-3 text-green-600 transition-transform duration-200"></i>
                                            <h3 class="text-lg font-medium text-green-900">
                                                <i class="fas fa-users text-green-600 mr-2"></i>
                                                Section {{ $session->section }} Students
                                            </h3>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <span class="text-sm text-green-600">
                                                <span class="font-medium">{{ $regularAttendances->count() }}</span> students
                                            </span>
                                            <span class="text-sm text-green-600">
                                                <span class="font-medium">{{ $regularAttendances->where('status', 'present')->count() }}</span> present
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-content bg-white">
                                <div class="p-4">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-green-50">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student ID</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year Level</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Type</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Section</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-in Time</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($regularAttendances as $attendance)
                                                    <tr class="bg-green-50 attendance-row" 
                                                        data-name="{{ strtolower($attendance->user->name) }}"
                                                        data-status="{{ $attendance->status }}"
                                                        data-section="regular"
                                                        data-type="{{ $attendance->user->student_type }}">
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900">{{ $attendance->user->name }}</div>
                                                            <div class="text-sm text-gray-500">{{ $attendance->user->email }}</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                            {{ $attendance->user->student_id ?? 'N/A' }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                            {{ $attendance->user->year_level ?? 'N/A' }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                                @if($attendance->user->student_type == 'regular') bg-blue-100 text-blue-800
                                                                @elseif($attendance->user->student_type == 'irregular') bg-yellow-100 text-yellow-800
                                                                @else bg-purple-100 text-purple-800
                                                                @endif">
                                                                {{ ucfirst($attendance->user->student_type ?? 'N/A') }}
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                {{ $attendance->user->section }}
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                                @if($attendance->status === 'present') bg-green-100 text-green-800
                                                                @elseif($attendance->status === 'late') bg-yellow-100 text-yellow-800
                                                                @else bg-red-100 text-red-800
                                                                @endif">
                                                                {{ ucfirst($attendance->status) }}
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                            {{ $attendance->check_in_time->format('M d, Y H:i:s') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Irregular Students Accordion -->
                    @if($irregularAttendances->count() > 0)
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <div class="accordion-header bg-yellow-50 hover:bg-yellow-100 cursor-pointer transition-colors duration-200">
                                <div class="p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <i class="fas fa-chevron-down accordion-icon mr-3 text-yellow-600 transition-transform duration-200"></i>
                                            <h3 class="text-lg font-medium text-yellow-900">
                                                <i class="fas fa-user-plus text-yellow-600 mr-2"></i>
                                                Irregular Students (Other Sections)
                                            </h3>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <span class="text-sm text-yellow-600">
                                                <span class="font-medium">{{ $irregularAttendances->count() }}</span> students
                                            </span>
                                            <span class="text-sm text-yellow-600">
                                                <span class="font-medium">{{ $irregularAttendances->where('status', 'present')->count() }}</span> present
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-content bg-white">
                                <div class="p-4">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-yellow-50">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student ID</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year Level</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Type</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Their Section</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-in Time</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($irregularAttendances as $attendance)
                                                    <tr class="bg-yellow-50 attendance-row" 
                                                        data-name="{{ strtolower($attendance->user->name) }}"
                                                        data-status="{{ $attendance->status }}"
                                                        data-section="irregular"
                                                        data-type="{{ $attendance->user->student_type }}">
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900">{{ $attendance->user->name }}</div>
                                                            <div class="text-sm text-gray-500">{{ $attendance->user->email }}</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                            {{ $attendance->user->student_id ?? 'N/A' }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                            {{ $attendance->user->year_level ?? 'N/A' }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                                @if($attendance->user->student_type == 'regular') bg-blue-100 text-blue-800
                                                                @elseif($attendance->user->student_type == 'irregular') bg-yellow-100 text-yellow-800
                                                                @else bg-purple-100 text-purple-800
                                                                @endif">
                                                                {{ ucfirst($attendance->user->student_type ?? 'N/A') }}
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                {{ $attendance->user->section }}
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                                @if($attendance->status === 'present') bg-green-100 text-green-800
                                                                @elseif($attendance->status === 'late') bg-yellow-100 text-yellow-800
                                                                @else bg-red-100 text-red-800
                                                                @endif">
                                                                {{ ucfirst($attendance->status) }}
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                            {{ $attendance->check_in_time->format('M d, Y H:i:s') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($attendances->count() == 0)
                        <div class="text-center py-8">
                            <i class="fas fa-users text-4xl text-gray-400 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No Attendees Yet</h3>
                            <p class="text-gray-600">Students can mark their attendance using the code: <span class="font-mono font-bold">{{ $session->code }}</span></p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Accordion functionality
    const accordionHeaders = document.querySelectorAll('.accordion-header');
    
    accordionHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const content = this.nextElementSibling;
            const icon = this.querySelector('.accordion-icon');
            
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            } else {
                content.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            }
        });
    });

    // Search and filter functionality
    const studentSearch = document.getElementById('studentSearch');
    const statusFilter = document.getElementById('statusFilter');
    const sectionFilter = document.getElementById('sectionFilter');
    const studentTypeFilter = document.getElementById('studentTypeFilter');

    function applyFilters() {
        const searchTerm = studentSearch.value.toLowerCase();
        const selectedStatus = statusFilter.value;
        const selectedSection = sectionFilter.value;
        const selectedType = studentTypeFilter.value;

        // Filter attendees
        document.querySelectorAll('.attendance-row').forEach(row => {
            const studentName = row.getAttribute('data-name');
            const status = row.getAttribute('data-status');
            const section = row.getAttribute('data-section');
            const type = row.getAttribute('data-type');
            
            const matchesSearch = !searchTerm || studentName.includes(searchTerm);
            const matchesStatus = !selectedStatus || status === selectedStatus;
            const matchesSection = !selectedSection || section === selectedSection;
            const matchesType = !selectedType || type === selectedType;
            
            row.style.display = (matchesSearch && matchesStatus && matchesSection && matchesType) ? 'table-row' : 'none';
        });

        // Update accordion headers with filtered counts
        updateAccordionCounts();
    }

    function updateAccordionCounts() {
        // Update regular students count
        const regularRows = document.querySelectorAll('.attendance-row[data-section="regular"]');
        const visibleRegularRows = Array.from(regularRows).filter(row => row.style.display !== 'none');
        const presentRegularRows = visibleRegularRows.filter(row => row.getAttribute('data-status') === 'present');
        
        const regularHeader = document.querySelector('.accordion-header:has(.fa-users)');
        if (regularHeader) {
            const countSpans = regularHeader.querySelectorAll('span');
            if (countSpans.length >= 2) {
                countSpans[0].innerHTML = `<span class="font-medium">${visibleRegularRows.length}</span> students`;
                countSpans[1].innerHTML = `<span class="font-medium">${presentRegularRows.length}</span> present`;
            }
        }

        // Update irregular students count
        const irregularRows = document.querySelectorAll('.attendance-row[data-section="irregular"]');
        const visibleIrregularRows = Array.from(irregularRows).filter(row => row.style.display !== 'none');
        const presentIrregularRows = visibleIrregularRows.filter(row => row.getAttribute('data-status') === 'present');
        
        const irregularHeader = document.querySelector('.accordion-header:has(.fa-user-plus)');
        if (irregularHeader) {
            const countSpans = irregularHeader.querySelectorAll('span');
            if (countSpans.length >= 2) {
                countSpans[0].innerHTML = `<span class="font-medium">${visibleIrregularRows.length}</span> students`;
                countSpans[1].innerHTML = `<span class="font-medium">${presentIrregularRows.length}</span> present`;
            }
        }
    }

    studentSearch.addEventListener('input', applyFilters);
    statusFilter.addEventListener('change', applyFilters);
    sectionFilter.addEventListener('change', applyFilters);
    studentTypeFilter.addEventListener('change', applyFilters);

    // Clear filters function
    window.clearFilters = function() {
        studentSearch.value = '';
        statusFilter.value = '';
        sectionFilter.value = '';
        studentTypeFilter.value = '';
        applyFilters();
    };

    // Expand all function
    window.expandAll = function() {
        document.querySelectorAll('.accordion-content').forEach(content => {
            content.classList.remove('hidden');
        });
        document.querySelectorAll('.accordion-icon').forEach(icon => {
            icon.style.transform = 'rotate(180deg)';
        });
    };

    // Collapse all function
    window.collapseAll = function() {
        document.querySelectorAll('.accordion-content').forEach(content => {
            content.classList.add('hidden');
        });
        document.querySelectorAll('.accordion-icon').forEach(icon => {
            icon.style.transform = 'rotate(0deg)';
        });
    };
});
</script> 