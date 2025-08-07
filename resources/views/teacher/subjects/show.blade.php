@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">
                        <i class="fas fa-book mr-2"></i>
                        {{ $subject->name }}
                    </h2>
                    <div class="flex space-x-3">
                        <a href="{{ route('teacher.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-home mr-2"></i>
                            Back to Dashboard
                        </a>
                        <button onclick="openEditModal()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Subject
                        </button>
                        <a href="{{ route('teacher.reports.attendance', $subject) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Attendance Report
                        </a>
                    </div>
                </div>

                <!-- Subject Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Subject Details</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Name:</span>
                                <span class="font-medium">{{ $subject->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Code:</span>
                                <span class="font-medium">{{ $subject->code }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Teacher:</span>
                                <span class="font-medium">{{ $subject->teacher->name }}</span>
                            </div>
                            @if($subject->description)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Description:</span>
                                    <span class="font-medium">{{ $subject->description }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-blue-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-blue-900 mb-4">Statistics</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-blue-700">Total Schedules:</span>
                                <span class="font-bold text-blue-900">{{ $schedules->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-blue-700">Total Sessions:</span>
                                <span class="font-bold text-blue-900">{{ $sessions->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-blue-700">Active Sessions:</span>
                                <span class="font-bold text-green-600">{{ $sessions->where('is_active', true)->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mb-8">
                    <div class="flex space-x-4">
                        <a href="{{ route('teacher.schedules.create', $subject) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            <i class="fas fa-calendar-plus mr-2"></i>
                            Add Schedule
                        </a>
                        <a href="{{ route('teacher.sessions.create', $subject) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                            <i class="fas fa-qrcode mr-2"></i>
                            Start Session
                        </a>
                    </div>
                </div>

                <!-- Schedules -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Schedules</h3>
                    @if($schedules->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Day</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Section</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($schedules as $schedule)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ ucfirst($schedule->day) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <span class="font-medium text-indigo-600">{{ $schedule->section ?? 'N/A' }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $schedule->type === 'lab' ? 'bg-blue-100 text-blue-800' : ($schedule->type === 'online' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                                    {{ ucfirst($schedule->type) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $schedule->room ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('teacher.schedules.edit', $schedule) }}" class="inline-flex items-center px-3 py-1 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                                        <i class="fas fa-edit mr-1"></i>
                                                        Edit
                                                    </a>
                                                    <button onclick="generateAttendanceCode('{{ $schedule->id }}', '{{ $schedule->section }}', '{{ $schedule->day }}', '{{ $schedule->start_time->format('H:i') }}')" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                                        <i class="fas fa-qrcode mr-1"></i>
                                                        Generate Code
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-calendar text-4xl text-gray-400 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No Schedules Yet</h3>
                            <p class="text-gray-600">Add schedules to organize your subject sessions.</p>
                        </div>
                    @endif
                </div>

                <!-- Search Filters for Sessions -->
                <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                    <h3 class="text-lg font-medium text-blue-900 mb-4">
                        <i class="fas fa-search mr-2"></i>
                        Search & Filter Sessions
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="sessionSearch" class="block text-sm font-medium text-blue-700 mb-2">Search Session Name</label>
                            <input type="text" id="sessionSearch" placeholder="Enter session name..." 
                                   class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="statusFilter" class="block text-sm font-medium text-blue-700 mb-2">Filter by Status</label>
                            <select id="statusFilter" class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="ended">Ended</option>
                            </select>
                        </div>
                        <div>
                            <label for="sectionFilter" class="block text-sm font-medium text-blue-700 mb-2">Filter by Section</label>
                            <select id="sectionFilter" class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Sections</option>
                                @foreach($sessions->pluck('section')->unique() as $section)
                                    <option value="{{ $section }}">{{ $section }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="dateFilter" class="block text-sm font-medium text-blue-700 mb-2">Filter by Date</label>
                            <select id="dateFilter" class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Dates</option>
                                <option value="today">Today</option>
                                <option value="yesterday">Yesterday</option>
                                <option value="this_week">This Week</option>
                                <option value="last_week">Last Week</option>
                                <option value="this_month">This Month</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4 flex space-x-2">
                        <button onclick="clearSessionFilters()" class="px-4 py-2 text-sm font-medium text-blue-700 bg-blue-100 rounded-md hover:bg-blue-200">
                            <i class="fas fa-times mr-2"></i>
                            Clear Filters
                        </button>
                        <button onclick="expandAllSessions()" class="px-4 py-2 text-sm font-medium text-green-700 bg-green-100 rounded-md hover:bg-green-200">
                            <i class="fas fa-expand-alt mr-2"></i>
                            Expand All
                        </button>
                        <button onclick="collapseAllSessions()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                            <i class="fas fa-compress-alt mr-2"></i>
                            Collapse All
                        </button>
                    </div>
                </div>

                <!-- Sessions with Accordion -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Attendance Sessions</h3>
                    @if($sessions->count() > 0)
                        <div class="space-y-4">
                            <!-- Active Sessions Accordion -->

                            @if($sessions->where('is_active', true)->count() > 0)
                                <div class="border border-gray-200 rounded-lg overflow-hidden">
                                    <div class="accordion-header bg-green-50 hover:bg-green-100 cursor-pointer transition-colors duration-200">
                                        <div class="p-4">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <i class="fas fa-chevron-down accordion-icon mr-3 text-green-600 transition-transform duration-200"></i>
                                                    <h4 class="text-lg font-medium text-green-900">
                                                        <i class="fas fa-play-circle text-green-600 mr-2"></i>
                                                        Active Sessions
                                                    </h4>
                                                </div>
                                                <div class="flex items-center space-x-4">
                                                    <span class="text-sm text-green-600">
                                                        <span class="font-medium">{{ $sessions->where('is_active', true)->count() }}</span> sessions
                                                    </span>
                                                    <span class="text-sm text-green-600">
                                                        <span class="font-medium">{{ $sessions->where('is_active', true)->sum(function($s) { return $s->attendances->count(); }) }}</span> total attendees
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-content bg-white">
                                        <div class="p-4">
                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                                @foreach($sessions->where('is_active', true) as $session)
    
                                                    <div class="session-card bg-white border border-gray-200 rounded-lg p-6 shadow-sm" 
                                                         data-name="{{ strtolower($session->name ?? 'session #' . $session->id) }}"
                                                         data-status="active"
                                                         data-section="{{ $session->section }}"
                                                         data-date="{{ $session->start_time->format('Y-m-d') }}">
                                                        <div class="flex items-center justify-between mb-4">
                                                            <h5 class="text-lg font-semibold text-gray-900">
                                                                @if($session->name)
                                                                    {{ $session->name }}
                                                                @else
                                                                    Session #{{ $session->id }}
                                                                @endif
                                                            </h5>
                                                            <div class="flex items-center space-x-2">
                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                    Active
                                                                </span>
                                                                <button onclick="showDeleteConfirmation('{{ $session->name ?? 'Session #' . $session->id }}', {{ $session->id }})" class="text-red-500 hover:text-red-700 transition-colors duration-200">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="space-y-2 mb-4">
                                                            <div class="flex justify-between">
                                                                <span class="text-gray-600">Section:</span>
                                                                <span class="font-bold text-indigo-600">{{ $session->section }}</span>
                                                            </div>
                                                            
                                                            <div class="flex justify-between">
                                                                <span class="text-gray-600">Code:</span>
                                                                <span class="font-mono font-bold">{{ $session->code }}</span>
                                                            </div>
                                                            
                                                            <div class="flex justify-between">
                                                                <span class="text-gray-600">Started:</span>
                                                                <span class="font-medium">{{ $session->start_time->format('M d, Y H:i') }}</span>
                                                            </div>
                                                            
                                                            @if($session->scheduled_start_time)
                                                                <div class="flex justify-between">
                                                                    <span class="text-gray-600">Scheduled:</span>
                                                                    <span class="font-medium">{{ $session->scheduled_start_time->format('M d, Y H:i') }}</span>
                                                                </div>
                                                            @endif
                                                            
                                                            <div class="flex justify-between">
                                                                <span class="text-gray-600">Attendees:</span>
                                                                <span class="font-medium text-green-600">{{ $session->attendances->count() }}</span>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="flex space-x-2">
                                                            <a href="{{ route('teacher.sessions.show', $session) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                                                <i class="fas fa-eye mr-2"></i>
                                                                View
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Ended Sessions Accordion -->
                            @if($sessions->where('is_active', false)->count() > 0)
                                <div class="border border-gray-200 rounded-lg overflow-hidden">
                                    <div class="accordion-header bg-gray-50 hover:bg-gray-100 cursor-pointer transition-colors duration-200">
                                        <div class="p-4">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <i class="fas fa-chevron-down accordion-icon mr-3 text-gray-600 transition-transform duration-200"></i>
                                                    <h4 class="text-lg font-medium text-gray-900">
                                                        <i class="fas fa-stop-circle text-gray-600 mr-2"></i>
                                                        Ended Sessions
                                                    </h4>
                                                </div>
                                                <div class="flex items-center space-x-4">
                                                    <span class="text-sm text-gray-600">
                                                        <span class="font-medium">{{ $sessions->where('is_active', false)->count() }}</span> sessions
                                                    </span>
                                                    <span class="text-sm text-gray-600">
                                                        <span class="font-medium">{{ $sessions->where('is_active', false)->sum(function($s) { return $s->attendances->count(); }) }}</span> total attendees
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-content bg-white">
                                        <div class="p-4">
                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                                @foreach($sessions->where('is_active', false) as $session)
                                                    <div class="session-card bg-white border border-gray-200 rounded-lg p-6 shadow-sm" 
                                                         data-name="{{ strtolower($session->name ?? 'session #' . $session->id) }}"
                                                         data-status="ended"
                                                         data-section="{{ $session->section }}"
                                                         data-date="{{ $session->start_time->format('Y-m-d') }}">
                                                        <div class="flex items-center justify-between mb-4">
                                                            <h5 class="text-lg font-semibold text-gray-900">
                                                                @if($session->name)
                                                                    {{ $session->name }}
                                                                @else
                                                                    Session #{{ $session->id }}
                                                                @endif
                                                            </h5>
                                                            <div class="flex items-center space-x-2">
                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                                    Ended
                                                                </span>
                                                                <button onclick="showDeleteConfirmation('{{ $session->name ?? 'Session #' . $session->id }}', {{ $session->id }})" class="text-red-500 hover:text-red-700 transition-colors duration-200">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="space-y-2 mb-4">
                                                            <div class="flex justify-between">
                                                                <span class="text-gray-600">Section:</span>
                                                                <span class="font-bold text-indigo-600">{{ $session->section }}</span>
                                                            </div>
                                                            
                                                            <div class="flex justify-between">
                                                                <span class="text-gray-600">Code:</span>
                                                                <span class="font-mono font-bold">{{ $session->code }}</span>
                                                            </div>
                                                            
                                                            <div class="flex justify-between">
                                                                <span class="text-gray-600">Started:</span>
                                                                <span class="font-medium">{{ $session->start_time->format('M d, Y H:i') }}</span>
                                                            </div>
                                                            
                                                            @if($session->scheduled_start_time)
                                                                <div class="flex justify-between">
                                                                    <span class="text-gray-600">Scheduled:</span>
                                                                    <span class="font-medium">{{ $session->scheduled_start_time->format('M d, Y H:i') }}</span>
                                                                </div>
                                                            @endif
                                                            
                                                            @if($session->end_time)
                                                                <div class="flex justify-between">
                                                                    <span class="text-gray-600">Ended:</span>
                                                                    <span class="font-medium">{{ $session->end_time->format('M d, Y H:i') }}</span>
                                                                </div>
                                                            @endif
                                                            
                                                            <div class="flex justify-between">
                                                                <span class="text-gray-600">Attendees:</span>
                                                                <span class="font-medium text-green-600">{{ $session->attendances->count() }}</span>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="flex space-x-2">
                                                            <a href="{{ route('teacher.sessions.show', $session) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                                                <i class="fas fa-eye mr-2"></i>
                                                                View
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-qrcode text-4xl text-gray-400 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No Sessions Yet</h3>
                            <p class="text-gray-600">Start an attendance session to begin tracking student attendance.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                    Delete Session
                </h3>
                <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-3">
                    Are you sure you want to delete this session? This action cannot be undone.
                </p>
                <p class="text-sm text-gray-600 mb-3">
                    To confirm deletion, please type the session name: <strong id="sessionNameToDelete"></strong>
                </p>
                <input type="text" id="confirmSessionName" placeholder="Type the session name to confirm" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
            </div>
            <div class="flex space-x-3">
                <button onclick="confirmDeleteSession()" id="confirmDeleteBtn" disabled
                        class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 disabled:bg-gray-400 disabled:cursor-not-allowed">
                    <i class="fas fa-trash mr-2"></i>
                    Delete Session
                </button>
                <button onclick="closeDeleteModal()" 
                        class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-times mr-2"></i>
                    Cancel
                </button>
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
    const sessionSearch = document.getElementById('sessionSearch');
    const statusFilter = document.getElementById('statusFilter');
    const sectionFilter = document.getElementById('sectionFilter');
    const dateFilter = document.getElementById('dateFilter');

    function applySessionFilters() {
        const searchTerm = sessionSearch.value.toLowerCase();
        const selectedStatus = statusFilter.value;
        const selectedSection = sectionFilter.value;
        const selectedDate = dateFilter.value;

        // Get current date for date filtering
        const today = new Date();
        const todayStr = today.toISOString().split('T')[0];
        const yesterday = new Date(today);
        yesterday.setDate(yesterday.getDate() - 1);
        const yesterdayStr = yesterday.toISOString().split('T')[0];

        // Filter session cards
        document.querySelectorAll('.session-card').forEach(card => {
            const sessionName = card.getAttribute('data-name');
            const status = card.getAttribute('data-status');
            const section = card.getAttribute('data-section');
            const date = card.getAttribute('data-date');
            
            const matchesSearch = !searchTerm || sessionName.includes(searchTerm);
            const matchesStatus = !selectedStatus || status === selectedStatus;
            const matchesSection = !selectedSection || section === selectedSection;
            
            let matchesDate = true;
            if (selectedDate) {
                switch(selectedDate) {
                    case 'today':
                        matchesDate = date === todayStr;
                        break;
                    case 'yesterday':
                        matchesDate = date === yesterdayStr;
                        break;
                    case 'this_week':
                        const weekAgo = new Date(today);
                        weekAgo.setDate(weekAgo.getDate() - 7);
                        matchesDate = date >= weekAgo.toISOString().split('T')[0];
                        break;
                    case 'last_week':
                        const twoWeeksAgo = new Date(today);
                        twoWeeksAgo.setDate(twoWeeksAgo.getDate() - 14);
                        const oneWeekAgo = new Date(today);
                        oneWeekAgo.setDate(oneWeekAgo.getDate() - 7);
                        matchesDate = date >= twoWeeksAgo.toISOString().split('T')[0] && date < oneWeekAgo.toISOString().split('T')[0];
                        break;
                    case 'this_month':
                        const monthAgo = new Date(today.getFullYear(), today.getMonth(), 1);
                        matchesDate = date >= monthAgo.toISOString().split('T')[0];
                        break;
                }
            }
            
            card.style.display = (matchesSearch && matchesStatus && matchesSection && matchesDate) ? 'block' : 'none';
        });

        // Update accordion headers with filtered counts
        updateSessionAccordionCounts();
    }

    function updateSessionAccordionCounts() {
        // Update active sessions count
        const activeCards = document.querySelectorAll('.session-card[data-status="active"]');
        const visibleActiveCards = Array.from(activeCards).filter(card => card.style.display !== 'none');
        const totalActiveAttendees = visibleActiveCards.reduce((sum, card) => {
            const attendeeText = card.querySelector('.text-green-600').textContent;
            return sum + parseInt(attendeeText) || 0;
        }, 0);
        
        const activeHeader = document.querySelector('.accordion-header:has(.fa-play-circle)');
        if (activeHeader) {
            const countSpans = activeHeader.querySelectorAll('span');
            if (countSpans.length >= 2) {
                countSpans[0].innerHTML = `<span class="font-medium">${visibleActiveCards.length}</span> sessions`;
                countSpans[1].innerHTML = `<span class="font-medium">${totalActiveAttendees}</span> total attendees`;
            }
        }

        // Update ended sessions count
        const endedCards = document.querySelectorAll('.session-card[data-status="ended"]');
        const visibleEndedCards = Array.from(endedCards).filter(card => card.style.display !== 'none');
        const totalEndedAttendees = visibleEndedCards.reduce((sum, card) => {
            const attendeeText = card.querySelector('.text-green-600').textContent;
            return sum + parseInt(attendeeText) || 0;
        }, 0);
        
        const endedHeader = document.querySelector('.accordion-header:has(.fa-stop-circle)');
        if (endedHeader) {
            const countSpans = endedHeader.querySelectorAll('span');
            if (countSpans.length >= 2) {
                countSpans[0].innerHTML = `<span class="font-medium">${visibleEndedCards.length}</span> sessions`;
                countSpans[1].innerHTML = `<span class="font-medium">${totalEndedAttendees}</span> total attendees`;
            }
        }
    }

    sessionSearch.addEventListener('input', applySessionFilters);
    statusFilter.addEventListener('change', applySessionFilters);
    sectionFilter.addEventListener('change', applySessionFilters);
    dateFilter.addEventListener('change', applySessionFilters);

    // Clear filters function
    window.clearSessionFilters = function() {
        sessionSearch.value = '';
        statusFilter.value = '';
        sectionFilter.value = '';
        dateFilter.value = '';
        applySessionFilters();
    };

    // Expand all function
    window.expandAllSessions = function() {
        document.querySelectorAll('.accordion-content').forEach(content => {
            content.classList.remove('hidden');
        });
        document.querySelectorAll('.accordion-icon').forEach(icon => {
            icon.style.transform = 'rotate(180deg)';
        });
    };

    // Collapse all function
    window.collapseAllSessions = function() {
        document.querySelectorAll('.accordion-content').forEach(content => {
            content.classList.add('hidden');
        });
        document.querySelectorAll('.accordion-icon').forEach(icon => {
            icon.style.transform = 'rotate(0deg)';
        });
    };

    // Generate attendance code from schedule
    window.generateAttendanceCode = function(scheduleId, section, day, time) {
        // Generate a unique code
        const code = Math.random().toString(36).substring(2, 8).toUpperCase();
        
        // Show the code in a modal or alert
        Swal.fire({
            title: 'Attendance Code Generated!',
            html: `
                <div class="text-center">
                    <div class="mb-4">
                        <h3 class="text-2xl font-bold text-indigo-600 mb-2">${code}</h3>
                        <p class="text-gray-600">Share this code with your students</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg text-left">
                        <p><strong>Section:</strong> ${section}</p>
                        <p><strong>Day:</strong> ${day}</p>
                        <p><strong>Time:</strong> ${time}</p>
                    </div>
                </div>
            `,
            icon: 'success',
            confirmButtonText: 'Copy Code',
            showCancelButton: true,
            cancelButtonText: 'Close',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Copy code to clipboard
                navigator.clipboard.writeText(code).then(() => {
                    Swal.fire('Copied!', 'Code copied to clipboard', 'success');
                }).catch(() => {
                    // Fallback for older browsers
                    const textArea = document.createElement('textarea');
                    textArea.value = code;
                    document.body.appendChild(textArea);
                    textArea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textArea);
                    Swal.fire('Copied!', 'Code copied to clipboard', 'success');
                });
            }
        });
    };

    // Edit Subject Modal Functions
    window.openEditModal = function() {
        // Populate the modal with current subject data
        document.getElementById('edit-subject-name').value = '{{ $subject->name }}';
        document.getElementById('edit-subject-code').value = '{{ $subject->code }}';
        document.getElementById('edit-subject-description').value = '{{ $subject->description ?? "" }}';
        
        // Show the modal
        document.getElementById('editSubjectModal').classList.remove('hidden');
    };

    window.closeEditModal = function() {
        document.getElementById('editSubjectModal').classList.add('hidden');
    };

    // Close modal when clicking outside
    document.getElementById('editSubjectModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });

    // Handle form submission
    document.getElementById('editSubjectForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('{{ route("teacher.subjects.update", $subject) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Success!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: data.message || 'Something went wrong',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'Something went wrong while updating the subject',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
    });

    // Delete confirmation modal functions
    let sessionToDelete = null;

    window.showDeleteConfirmation = function(sessionName, sessionId) {
        console.log('Opening delete confirmation for session:', sessionName, sessionId);
        sessionToDelete = sessionId;
        document.getElementById('sessionNameToDelete').textContent = sessionName;
        document.getElementById('confirmSessionName').value = '';
        document.getElementById('confirmDeleteBtn').disabled = true;
        document.getElementById('deleteModal').classList.remove('hidden');
    };

    window.closeDeleteModal = function() {
        document.getElementById('deleteModal').classList.add('hidden');
        sessionToDelete = null;
    };

    window.confirmDeleteSession = function() {
        console.log('Confirming delete for session:', sessionToDelete);
        if (sessionToDelete) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("teacher.sessions.delete", ":id") }}'.replace(':id', sessionToDelete);
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            console.log('Submitting delete form for session:', sessionToDelete);
            form.submit();
        } else {
            console.error('No session to delete');
        }
    };

    // Add event listener for the confirmation input
    document.getElementById('confirmSessionName').addEventListener('input', function() {
        const sessionName = document.getElementById('sessionNameToDelete').textContent;
        const inputValue = this.value;
        const confirmBtn = document.getElementById('confirmDeleteBtn');
        
        console.log('Input validation:', { sessionName, inputValue, matches: inputValue === sessionName });
        
        if (inputValue === sessionName) {
            confirmBtn.disabled = false;
            console.log('Delete button enabled');
        } else {
            confirmBtn.disabled = true;
            console.log('Delete button disabled');
        }
    });

    // Close modal when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
});
</script> 

<!-- Edit Subject Modal -->
<div id="editSubjectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Subject
                </h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="editSubjectForm" class="space-y-4">
                <div>
                    <label for="edit-subject-name" class="block text-sm font-medium text-gray-700 mb-1">Subject Name</label>
                    <input type="text" id="edit-subject-name" name="name" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                
                <div>
                    <label for="edit-subject-code" class="block text-sm font-medium text-gray-700 mb-1">Subject Code</label>
                    <input type="text" id="edit-subject-code" name="code" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                
                <div>
                    <label for="edit-subject-description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="edit-subject-description" name="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                </div>
                
                <div class="flex space-x-3 pt-4">
                    <button type="submit" class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        <i class="fas fa-save mr-2"></i>
                        Save Changes
                    </button>
                    <button type="button" onclick="closeEditModal()" class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-times mr-2"></i>
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>