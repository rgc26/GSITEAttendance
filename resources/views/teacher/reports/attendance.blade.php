@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Attendance Report - {{ $subject->name }}
                    </h2>
                    <div class="flex space-x-3">
                        <a href="{{ route('teacher.subjects.show', $subject) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Subject
                        </a>
                        <button onclick="window.print()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                            <i class="fas fa-print mr-2"></i>
                            Print Report
                        </button>
                    </div>
                </div>

                <!-- Subject Information -->
                <div class="mb-8 p-6 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Subject Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <span class="text-gray-600">Subject:</span>
                            <span class="font-medium">{{ $subject->name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Code:</span>
                            <span class="font-medium">{{ $subject->code }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Teacher:</span>
                            <span class="font-medium">{{ $subject->teacher->name }}</span>
                        </div>
                    </div>
                </div>

                <!-- Search Filters -->
                <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                    <h3 class="text-lg font-medium text-blue-900 mb-4">
                        <i class="fas fa-search mr-2"></i>
                        Search & Filter
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="sectionFilter" class="block text-sm font-medium text-blue-700 mb-2">Filter by Section</label>
                            <select id="sectionFilter" class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Sections</option>
                                @foreach($sectionReports as $section => $report)
                                    <option value="section-{{ $section }}">Section {{ $section }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="studentSearch" class="block text-sm font-medium text-blue-700 mb-2">Search Student Name</label>
                            <input type="text" id="studentSearch" placeholder="Enter student name..." 
                                   class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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

                <!-- Section Reports Accordion -->
                @if(count($sectionReports) > 0)
                    <div id="sectionAccordion" class="space-y-4">
                        @foreach($sectionReports as $section => $report)
                            <div class="section-accordion border border-gray-200 rounded-lg overflow-hidden" data-section="{{ $section }}">
                                <!-- Accordion Header -->
                                <div class="accordion-header bg-indigo-50 hover:bg-indigo-100 cursor-pointer transition-colors duration-200">
                                    <div class="p-6">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <i class="fas fa-chevron-down accordion-icon mr-3 text-indigo-600 transition-transform duration-200"></i>
                                                <h3 class="text-xl font-bold text-indigo-900">
                                                    <i class="fas fa-users mr-2"></i>
                                                    Section {{ $section }} - Attendance Summary
                                                </h3>
                                            </div>
                                            <div class="flex items-center space-x-4">
                                                <div class="text-sm text-indigo-600">
                                                    <span class="font-medium">{{ $report['total_regular_students'] }}</span> Regular
                                                    <span class="mx-2">•</span>
                                                    <span class="font-medium">{{ $report['total_irregular_students'] }}</span> Irregular
                                                </div>
                                                <div class="text-sm text-indigo-600">
                                                    <span class="font-medium">{{ $report['total_sessions'] }}</span> Sessions
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Summary Cards -->
                                        <div class="mt-4 grid grid-cols-1 md:grid-cols-4 gap-4">
                                            <div class="bg-white rounded-lg p-4 border border-indigo-200">
                                                <div class="text-2xl font-bold text-indigo-600">{{ $report['total_sessions'] }}</div>
                                                <div class="text-sm text-gray-600">Total Sessions</div>
                                            </div>
                                            <div class="bg-white rounded-lg p-4 border border-green-200">
                                                <div class="text-2xl font-bold text-green-600">{{ $report['total_regular_students'] }}</div>
                                                <div class="text-sm text-gray-600">Regular Students</div>
                                            </div>
                                            <div class="bg-white rounded-lg p-4 border border-yellow-200">
                                                <div class="text-2xl font-bold text-yellow-600">{{ $report['total_irregular_students'] }}</div>
                                                <div class="text-sm text-gray-600">Irregular Students</div>
                                            </div>
                                            <div class="bg-white rounded-lg p-4 border border-blue-200">
                                                <div class="text-2xl font-bold text-blue-600">{{ $report['total_regular_students'] + $report['total_irregular_students'] }}</div>
                                                <div class="text-sm text-gray-600">Total Attendees</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Accordion Content -->
                                <div class="accordion-content hidden bg-white">
                                    <div class="p-6 space-y-6">
                                        <!-- Regular Students Table -->
                                        @if(count($report['regular_students']) > 0)
                                            <div class="student-table" data-type="regular">
                                                <h4 class="text-lg font-medium text-gray-900 mb-4">
                                                    <i class="fas fa-user-check text-green-600 mr-2"></i>
                                                    Regular Students (Section {{ $section }})
                                                </h4>
                                                <div class="overflow-x-auto">
                                                    <table class="min-w-full divide-y divide-gray-200">
                                                        <thead class="bg-green-50">
                                                            <tr>
                                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Details</th>
                                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student ID</th>
                                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year Level</th>
                                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Type</th>
                                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Present</th>
                                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Late</th>
                                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Absent</th>
                                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Sessions</th>
                                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attendance Rate</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="bg-white divide-y divide-gray-200">
                                                            @foreach($report['regular_students'] as $studentReport)
                                                                <tr class="bg-green-50 student-row" 
                                                                    data-name="{{ strtolower($studentReport['student']->name) }}"
                                                                    data-type="{{ $studentReport['student']->student_type }}">
                                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                                        <div class="text-sm font-medium text-gray-900">{{ $studentReport['student']->name }}</div>
                                                                        <div class="text-sm text-gray-500">{{ $studentReport['student']->email }}</div>
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                        {{ $studentReport['student']->student_id ?? 'N/A' }}
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                        {{ $studentReport['student']->year_level ?? 'N/A' }}
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                                            @if($studentReport['student']->student_type == 'regular') bg-blue-100 text-blue-800
                                                                            @elseif($studentReport['student']->student_type == 'irregular') bg-yellow-100 text-yellow-800
                                                                            @else bg-purple-100 text-purple-800
                                                                            @endif">
                                                                            {{ ucfirst($studentReport['student']->student_type ?? 'N/A') }}
                                                                        </span>
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                            {{ $studentReport['present_sessions'] }}
                                                                        </span>
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                            {{ $studentReport['late_sessions'] }}
                                                                        </span>
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                                            {{ $studentReport['absent_sessions'] }}
                                                                        </span>
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                        {{ $studentReport['total_sessions'] }}
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                                            @if($studentReport['attendance_rate'] >= 90) bg-green-100 text-green-800
                                                                            @elseif($studentReport['attendance_rate'] >= 75) bg-yellow-100 text-yellow-800
                                                                            @else bg-red-100 text-red-800
                                                                            @endif">
                                                                            {{ $studentReport['attendance_rate'] }}%
                                                                        </span>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Irregular Students Table -->
                                        @if(count($report['irregular_students']) > 0)
                                            <div class="student-table" data-type="irregular">
                                                <h4 class="text-lg font-medium text-gray-900 mb-4">
                                                    <i class="fas fa-user-plus text-yellow-600 mr-2"></i>
                                                    Irregular Students (Other Sections)
                                                </h4>
                                                <div class="overflow-x-auto">
                                                    <table class="min-w-full divide-y divide-gray-200">
                                                        <thead class="bg-yellow-50">
                                                            <tr>
                                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Details</th>
                                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student ID</th>
                                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year Level</th>
                                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Type</th>
                                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Their Section</th>
                                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Present</th>
                                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Late</th>
                                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Attended</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="bg-white divide-y divide-gray-200">
                                                            @foreach($report['irregular_students'] as $studentReport)
                                                                <tr class="bg-yellow-50 student-row" 
                                                                    data-name="{{ strtolower($studentReport['student']->name) }}"
                                                                    data-type="{{ $studentReport['student']->student_type }}">
                                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                                        <div class="text-sm font-medium text-gray-900">{{ $studentReport['student']->name }}</div>
                                                                        <div class="text-sm text-gray-500">{{ $studentReport['student']->email }}</div>
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                        {{ $studentReport['student']->student_id ?? 'N/A' }}
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                        {{ $studentReport['student']->year_level ?? 'N/A' }}
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                                            @if($studentReport['student']->student_type == 'regular') bg-blue-100 text-blue-800
                                                                            @elseif($studentReport['student']->student_type == 'irregular') bg-yellow-100 text-yellow-800
                                                                            @else bg-purple-100 text-purple-800
                                                                            @endif">
                                                                            {{ ucfirst($studentReport['student']->student_type ?? 'N/A') }}
                                                                        </span>
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                            {{ $studentReport['student']->section }}
                                                                        </span>
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                            {{ $studentReport['present_sessions'] }}
                                                                        </span>
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                            {{ $studentReport['late_sessions'] }}
                                                                        </span>
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                        {{ $studentReport['total_attended'] }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Session Details -->
                                        <div>
                                            <h4 class="text-lg font-medium text-gray-900 mb-4">
                                                <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                                                Session Details
                                            </h4>
                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                                @foreach($report['sessions'] as $session)
                                                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                                                        <div class="flex items-center justify-between mb-2">
                                                            <h5 class="font-medium text-gray-900">
                                                                @if($session->name)
                                                                    {{ $session->name }}
                                                                @else
                                                                    Session #{{ $session->id }}
                                                                @endif
                                                            </h5>
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $session->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                                {{ $session->is_active ? 'Active' : 'Ended' }}
                                                            </span>
                                                        </div>
                                                        <div class="space-y-1 text-sm text-gray-600">
                                                            <div>Code: <span class="font-mono font-bold">{{ $session->code }}</span></div>
                                                            <div>Date: {{ $session->start_time->format('M d, Y') }}</div>
                                                            <div>Time: {{ $session->start_time->format('H:i') }}</div>
                                                            <div>Attendees: <span class="font-medium text-green-600">{{ $session->attendances->count() }}</span></div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-chart-bar text-4xl text-gray-400 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Attendance Data</h3>
                        <p class="text-gray-600">No attendance sessions have been conducted for this subject yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print {
        display: none !important;
    }
    .bg-green-50, .bg-yellow-50, .bg-indigo-50, .bg-blue-50 {
        background-color: white !important;
        border: 1px solid #e5e7eb !important;
    }
    .accordion-content {
        display: block !important;
    }
}
</style>

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
    const sectionFilter = document.getElementById('sectionFilter');
    const studentSearch = document.getElementById('studentSearch');
    const studentTypeFilter = document.getElementById('studentTypeFilter');

    function applyFilters() {
        const selectedSection = sectionFilter.value;
        const searchTerm = studentSearch.value.toLowerCase();
        const selectedType = studentTypeFilter.value;

        // Filter sections
        document.querySelectorAll('.section-accordion').forEach(section => {
            const sectionId = section.getAttribute('data-section');
            const shouldShowSection = !selectedSection || selectedSection === `section-${sectionId}`;
            section.style.display = shouldShowSection ? 'block' : 'none';
        });

        // Filter students within visible sections
        document.querySelectorAll('.student-row').forEach(row => {
            const studentName = row.getAttribute('data-name');
            const studentType = row.getAttribute('data-type');
            
            const matchesSearch = !searchTerm || studentName.includes(searchTerm);
            const matchesType = !selectedType || studentType === selectedType;
            
            row.style.display = (matchesSearch && matchesType) ? 'table-row' : 'none';
        });
    }

    sectionFilter.addEventListener('change', applyFilters);
    studentSearch.addEventListener('input', applyFilters);
    studentTypeFilter.addEventListener('change', applyFilters);

    // Clear filters function
    window.clearFilters = function() {
        sectionFilter.value = '';
        studentSearch.value = '';
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
@endsection 