@extends('layouts.app')

@section('content')
<style>
    .profile-picture {
        transition: transform 0.2s ease-in-out;
    }
    .profile-picture:hover {
        transform: scale(1.1);
    }
    .profile-placeholder {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 600;
    }
    
    /* Prevent horizontal scrolling */
    .attendance-table {
        width: 100%;
        table-layout: auto;
        min-width: 0;
        border-collapse: collapse;
    }
    
    .attendance-table th,
    .attendance-table td {
        word-wrap: break-word;
        overflow-wrap: break-word;
        max-width: 0;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .attendance-table th {
        background-color: #f9fafb;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #6b7280;
        padding: 0.75rem 0.5rem;
        text-align: left;
        vertical-align: top;
    }
    
    .attendance-table td {
        padding: 1rem 0.5rem;
        vertical-align: top;
    }
    
    /* Column-specific styling */
    .attendance-table th:nth-child(1), /* Student */
    .attendance-table td:nth-child(1) {
        min-width: 200px;
    }
    
    .attendance-table th:nth-child(2), /* Student ID */
    .attendance-table td:nth-child(2) {
        min-width: 120px;
    }
    
    .attendance-table th:nth-child(8), /* Check-in Time */
    .attendance-table td:nth-child(8) {
        min-width: 140px;
    }
    
    /* Button styling improvements */
    .action-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.375rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease-in-out;
        cursor: pointer;
        border: none;
        outline: none;
        width: 100%;
        min-height: 32px;
    }
    
    .action-button:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .action-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    /* Status badge improvements */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
        text-align: center;
        white-space: nowrap;
    }
    
    /* Mobile-specific improvements */
    .mobile-friendly-container {
        padding: 0.5rem;
    }
    
    .mobile-friendly-text {
        font-size: 0.875rem;
    }
    
    .mobile-friendly-button {
        padding: 0.5rem 0.75rem;
        font-size: 0.75rem;
        min-height: 2.5rem;
    }
    
    .mobile-friendly-table {
        font-size: 0.75rem;
    }
    
    .mobile-friendly-table th,
    .mobile-friendly-table td {
        padding: 0.5rem 0.25rem;
    }
    
    /* Responsive table adjustments */
    @media (max-width: 1024px) {
        .attendance-table th,
        .attendance-table td {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
    }
    
    @media (max-width: 768px) {
        .attendance-table th,
        .attendance-table td {
            padding-left: 0.25rem;
            padding-right: 0.25rem;
            font-size: 0.75rem;
        }
        
        .attendance-table th:nth-child(1),
        .attendance-table td:nth-child(1) {
            min-width: 150px;
        }
        
        .attendance-table th:nth-child(2),
        .attendance-table td:nth-child(2) {
            min-width: 100px;
        }
        
        .attendance-table th:nth-child(8),
        .attendance-table td:nth-child(8) {
            min-width: 120px;
        }
        
        .action-button {
            padding: 0.25rem 0.5rem;
            font-size: 0.7rem;
            min-height: 28px;
        }
        
        .mobile-friendly-container {
            padding: 0.25rem;
        }
        
        .mobile-friendly-text {
            font-size: 0.75rem;
        }
        
        .mobile-friendly-button {
            padding: 0.375rem 0.5rem;
            font-size: 0.7rem;
            min-height: 2.25rem;
        }
        
        .mobile-friendly-table {
            font-size: 0.7rem;
        }
        
        .mobile-friendly-table th,
        .mobile-friendly-table td {
            padding: 0.375rem 0.125rem;
        }
    }
    
    @media (max-width: 640px) {
        .attendance-table th:nth-child(1),
        .attendance-table td:nth-child(1) {
            min-width: 120px;
        }
        
        .attendance-table th:nth-child(2),
        .attendance-table td:nth-child(2) {
            min-width: 80px;
        }
        
        .attendance-table th:nth-child(8),
        .attendance-table td:nth-child(8) {
            min-width: 100px;
        }
        
        .action-button {
            padding: 0.25rem 0.375rem;
            font-size: 0.65rem;
            min-height: 24px;
        }
    }
</style>
<div class="py-6">
    <div class="max-w-full mx-auto px-2 sm:px-4 lg:px-6 xl:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-4 sm:p-6 lg:p-8 bg-white border-b border-gray-200 mobile-friendly-container">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
                    <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 mobile-friendly-text">
                        <i class="fas fa-qrcode mr-2"></i>
                        Attendance Session
                    </h2>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                        <a href="{{ route('teacher.subjects.show', $session->subject) }}" class="mobile-btn inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Subject
                        </a>
                        @if($session->is_active)
                            <a href="{{ route('teacher.sessions.edit', $session) }}" class="mobile-btn inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-edit mr-2"></i>
                                Edit Session
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Session Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6 lg:gap-8 mb-8">
                    <div class="bg-gray-50 rounded-lg p-4 sm:p-6 lg:p-8 mobile-friendly-container">
                        <h3 class="text-base sm:text-lg lg:text-xl font-medium text-gray-900 mb-4 mobile-friendly-text">Session Details</h3>
                        <div class="space-y-3">
                            <div class="flex flex-col sm:flex-row sm:justify-between">
                                <span class="text-gray-600 mobile-friendly-text">Subject:</span>
                                <span class="font-medium mobile-friendly-text">{{ $session->subject->name }} ({{ $session->subject->code }})</span>
                            </div>
                            @if($session->name)
                                <div class="flex flex-col sm:flex-row sm:justify-between">
                                    <span class="text-gray-600 mobile-friendly-text">Session Name:</span>
                                    <span class="font-medium mobile-friendly-text">{{ $session->name }}</span>
                                </div>
                            @endif
                            <div class="flex flex-col sm:flex-row sm:justify-between">
                                <span class="text-gray-600 mobile-friendly-text">Target Section:</span>
                                <span class="font-bold text-indigo-600 mobile-friendly-text">{{ $session->section }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:justify-between">
                                <span class="text-gray-600 mobile-friendly-text">Session Type:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $session->session_type === 'lab' ? 'bg-blue-100 text-blue-800' : ($session->session_type === 'online' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($session->session_type ?? 'lecture') }}
                                </span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:justify-between">
                                <span class="text-gray-600 mobile-friendly-text">Attendance Code:</span>
                                <span class="font-mono font-bold text-base sm:text-lg">{{ $session->code }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:justify-between">
                                <span class="text-gray-600 mobile-friendly-text">Status:</span>
                                @if($session->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @elseif($session->scheduled_start_time && !$session->start_time)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Scheduled
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Ended
                                    </span>
                                @endif
                            </div>
                            <div class="flex flex-col sm:flex-row sm:justify-between">
                                <span class="text-gray-600 mobile-friendly-text">Started:</span>
                                <span class="font-medium mobile-friendly-text">{{ $session->start_time ? $session->start_time->format('M d, Y H:i') : 'Not started yet' }}</span>
                            </div>
                            @if($session->scheduled_start_time)
                                <div class="flex flex-col sm:flex-row sm:justify-between">
                                    <span class="text-gray-600 mobile-friendly-text">Scheduled Start:</span>
                                    <span class="font-medium mobile-friendly-text">{{ $session->scheduled_start_time->format('M d, Y H:i') }}</span>
                                </div>
                                <div class="flex flex-col sm:flex-row sm:justify-between">
                                    <span class="text-gray-600 mobile-friendly-text">Grace Period End:</span>
                                    <span class="font-medium mobile-friendly-text">{{ $session->getGracePeriodEndTime()->format('M d, Y H:i') }}</span>
                                </div>
                            @endif
                            @if($session->scheduled_end_time)
                                <div class="flex flex-col sm:flex-row sm:justify-between">
                                    <span class="text-gray-600 mobile-friendly-text">Scheduled End:</span>
                                    <span class="font-medium mobile-friendly-text">{{ $session->scheduled_end_time->format('M d, Y H:i') }}</span>
                                </div>
                            @endif
                            @if($session->end_time)
                                <div class="flex flex-col sm:flex-row sm:justify-between">
                                    <span class="text-gray-600 mobile-friendly-text">Ended:</span>
                                    <span class="font-medium mobile-friendly-text">{{ $session->end_time->format('M d, Y H:i') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-green-50 rounded-lg p-4 sm:p-6 lg:p-8">
                        <h3 class="text-base sm:text-lg lg:text-xl font-medium text-green-900 mb-4 mobile-friendly-text">Section {{ $session->section }} Summary</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-green-700 mobile-friendly-text">Total Students:</span>
                                <span class="font-bold text-green-900 text-lg sm:text-xl">{{ $totalTargetStudents }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-green-700 mobile-friendly-text">Present:</span>
                                <span class="font-medium text-green-600 mobile-friendly-text">{{ $presentTargetStudents }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-green-700 mobile-friendly-text">Late:</span>
                                <span class="font-medium text-yellow-600 mobile-friendly-text">{{ $lateTargetStudents }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-green-700 mobile-friendly-text">Absent:</span>
                                <span class="font-medium text-red-600 mobile-friendly-text">{{ $absentTargetStudents }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-green-700 mobile-friendly-text">Not Marked Yet:</span>
                                <span class="font-medium text-gray-600 mobile-friendly-text">{{ $notMarkedYet }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-green-700 mobile-friendly-text">Attendance Rate:</span>
                                <span class="font-medium text-green-900 mobile-friendly-text">
                                    @if($totalTargetStudents > 0)
                                        {{ round((($presentTargetStudents + $lateTargetStudents) / $totalTargetStudents) * 100, 1) }}%
                                    @else
                                        0%
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    @if(config('app.debug'))
                    <div class="mt-4 p-3 bg-gray-100 border border-gray-300 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Debug - Section Summary Calculation:</h4>
                        <div class="text-xs text-gray-600 space-y-1">
                            <div><strong>Total Students in Section {{ $session->section }}:</strong> {{ $totalTargetStudents }}</div>
                            <div><strong>Present Students:</strong> {{ $presentTargetStudents }}</div>
                            <div><strong>Late Students:</strong> {{ $lateTargetStudents }}</div>
                            <div><strong>Absent Students:</strong> {{ $absentTargetStudents }}</div>
                            <div><strong>Not Marked Yet:</strong> {{ $notMarkedYet }}</div>
                            <div><strong>Calculation:</strong> {{ $totalTargetStudents }} - {{ $presentTargetStudents }} - {{ $lateTargetStudents }} - {{ $absentTargetStudents }} = {{ $notMarkedYet }}</div>
                            <div><strong>Attendance Rate:</strong> ({{ $presentTargetStudents }} + {{ $lateTargetStudents }}) / {{ $totalTargetStudents }} × 100 = {{ round((($presentTargetStudents + $lateTargetStudents) / $totalTargetStudents) * 100, 1) }}%</div>
                        </div>
                    </div>
                    @endif

                    <div class="bg-blue-50 rounded-lg p-4 sm:p-6 lg:p-8">
                        <h3 class="text-base sm:text-lg lg:text-xl font-medium text-blue-900 mb-4 mobile-friendly-text">Overall Summary</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-blue-700 mobile-friendly-text">Total Attendees:</span>
                                <span class="font-bold text-blue-900 text-lg sm:text-xl">{{ $attendances->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-blue-700 mobile-friendly-text">Regular Students:</span>
                                <span class="font-medium text-green-600 mobile-friendly-text">{{ $regularAttendances->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-blue-700 mobile-friendly-text">Irregular Students:</span>
                                <span class="font-medium text-yellow-600 mobile-friendly-text">{{ $irregularAttendances->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-blue-700 mobile-friendly-text">Block Students:</span>
                                <span class="font-medium text-purple-600 mobile-friendly-text">{{ $blockAttendances->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-blue-700 mobile-friendly-text">On Time:</span>
                                <span class="font-medium text-green-600 mobile-friendly-text">{{ $attendances->where('status', 'present')->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-blue-700 mobile-friendly-text">Late:</span>
                                <span class="font-medium text-yellow-600 mobile-friendly-text">{{ $attendances->where('status', 'late')->count() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Session Statistics Card -->
                    <div class="bg-purple-50 rounded-lg p-4 sm:p-6 lg:p-8">
                        <h3 class="text-base sm:text-lg lg:text-xl font-medium text-purple-900 mb-4 mobile-friendly-text">Session Statistics</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-purple-700 mobile-friendly-text">Session Duration:</span>
                                <span class="font-medium text-purple-900 mobile-friendly-text">
                                    @if($session->start_time && $session->end_time)
                                        {{ $session->start_time->diffForHumans($session->end_time, true) }}
                                    @elseif($session->start_time)
                                        {{ $session->start_time->diffForHumans(null, true) }}
                                    @else
                                        Not started
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-purple-700 mobile-friendly-text">PC Numbers Used:</span>
                                <span class="font-medium text-purple-900 mobile-friendly-text">
                                    {{ $attendances->whereNotNull('pc_number')->unique('pc_number')->count() }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-purple-700 mobile-friendly-text">Device Types:</span>
                                <span class="font-medium text-purple-900 mobile-friendly-text">
                                    {{ $attendances->whereNotNull('device_type')->unique('device_type')->count() }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-purple-700 mobile-friendly-text">With Images:</span>
                                <span class="font-medium text-purple-900 mobile-friendly-text">
                                    {{ $attendances->whereNotNull('attached_image')->count() }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-purple-700 mobile-friendly-text">Created:</span>
                                <span class="font-medium text-purple-900 mobile-friendly-text">
                                    {{ $session->created_at->format('M d, Y H:i') }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-purple-700 mobile-friendly-text">Last Updated:</span>
                                <span class="font-medium text-purple-900 mobile-friendly-text">
                                    {{ $session->updated_at->format('M d, Y H:i') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Helpful Note for Teachers -->
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-green-500 mr-2"></i>
                        <div class="text-sm text-green-700">
                            <strong>Attendance Management:</strong> 
                            Students who have already marked attendance (even if marked as absent) can be updated using the "Edit" buttons below. 
                            This prevents duplicate records and allows you to correct any attendance status or details.
                            <br><br>
                            <strong>Student Type Display:</strong> 
                            Student types (Regular, Irregular, Block) are always shown based on their current account details, 
                            not the details from when they originally marked attendance. This ensures accurate categorization even after account updates.
                        </div>
                    </div>
                </div>

                <!-- Session-Specific Summary -->
                @if($session->session_type === 'lab')
                    <div class="mt-6 bg-orange-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-orange-900 mb-4">
                            <i class="fas fa-desktop mr-2"></i>
                            Lab Session Summary
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @php
                                // Get all non-absent attendances with PC numbers
                                $labAttendances = $attendances->where('status', '!=', 'absent')->where('pc_number', '!=', null)->where('pc_number', '!=', '');
                                
                                // Group students by PC number, ensuring no duplicate names per PC
                                $pcGroups = [];
                                $studentPcMap = []; // Track which PC each student is assigned to
                                
                                foreach ($labAttendances as $attendance) {
                                    $pcNum = $attendance->pc_number;
                                    $studentName = $attendance->user->name;
                                    $studentId = $attendance->user->id;
                                    
                                    // Extract just the number from PC input (remove "PC", "pc", etc.)
                                    $pcNum = preg_replace('/[^0-9]/', '', $pcNum);
                                    
                                    if ($pcNum && is_numeric($pcNum) && $pcNum >= 1 && $pcNum <= 40) {
                                        // If student already has a PC assigned, skip this record
                                        if (isset($studentPcMap[$studentId])) {
                                            continue;
                                        }
                                        
                                        if (!isset($pcGroups[$pcNum])) {
                                            $pcGroups[$pcNum] = [];
                                        }
                                        
                                        // Add student to this PC group and mark them as assigned
                                        $pcGroups[$pcNum][] = [
                                            'id' => $studentId,
                                            'name' => $studentName
                                        ];
                                        $studentPcMap[$studentId] = $pcNum;
                                    }
                                }
                                
                                // Sort by PC number
                                ksort($pcGroups);
                            @endphp
                            
                            @foreach($pcGroups as $pcNum => $students)
                                <div class="text-center p-3 bg-orange-100 rounded-lg">
                                    <div class="text-lg font-bold text-orange-800">PC{{ $pcNum }}</div>
                                    <div class="text-sm text-orange-600">{{ count($students) }} student(s)</div>
                                    <div class="text-xs text-orange-700 mt-1">
                                        @foreach($students as $student)
                                            <div class="truncate" title="{{ $student['name'] }}">{{ $student['name'] }}</div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 text-sm text-orange-700">
                            <strong>Total PCs Used:</strong> {{ count($pcGroups) }} out of 40 available
                            <br>
                            <strong>Total Unique Students:</strong> {{ count($studentPcMap) }} students
                        </div>
                        
                        @if(config('app.debug'))
                        <div class="mt-4 p-3 bg-gray-100 border border-gray-300 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Debug Information:</h4>
                            <div class="text-xs text-gray-600 space-y-1">
                                <div><strong>Raw PC Numbers:</strong> {{ $labAttendances->pluck('pc_number')->implode(', ') }}</div>
                                <div><strong>Student-PC Mapping:</strong> 
                                    @foreach($studentPcMap as $studentId => $pcNum)
                                        Student ID {{ $studentId }} → PC{{ $pcNum }},
                                    @endforeach
                                </div>
                                <div><strong>Processed PC Groups:</strong> 
                                    @foreach($pcGroups as $pcNum => $students)
                                        PC{{ $pcNum }}: {{ count($students) }} students ({{ implode(', ', array_column($students, 'name')) }})
                                        @if(!$loop->last), @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                @elseif($session->session_type === 'online')
                    <div class="mt-6 bg-green-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-green-900 mb-4">
                            <i class="fas fa-wifi mr-2"></i>
                            Online Session Summary
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="text-center p-4 bg-green-100 rounded-lg">
                                <div class="text-2xl font-bold text-green-800">{{ $attendances->where('device_type', 'mobile')->count() }}</div>
                                <div class="text-sm text-green-600">Mobile Users</div>
                            </div>
                            <div class="text-center p-4 bg-blue-100 rounded-lg">
                                <div class="text-2xl font-bold text-blue-800">{{ $attendances->where('device_type', 'desktop')->count() }}</div>
                                <div class="text-sm text-blue-600">Desktop Users</div>
                            </div>
                            <div class="text-center p-4 bg-purple-100 rounded-lg">
                                <div class="text-2xl font-bold text-purple-800">{{ $attendances->where('device_type', 'laptop')->count() }}</div>
                                <div class="text-sm text-purple-600">Laptop Users</div>
                            </div>
                        </div>
                    </div>
                @elseif($session->session_type === 'lecture')
                    <div class="mt-6 bg-yellow-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-yellow-900 mb-4">
                            <i class="fas fa-image mr-2"></i>
                            Lecture Session Summary
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="text-center p-4 bg-yellow-100 rounded-lg">
                                <div class="text-2xl font-bold text-yellow-800">{{ $attendances->where('attached_image', '!=', null)->count() }}</div>
                                <div class="text-sm text-yellow-600">Images Uploaded</div>
                            </div>
                            <div class="text-center p-4 bg-red-100 rounded-lg">
                                <div class="text-2xl font-bold text-red-800">{{ $attendances->where('attached_image', null)->count() }}</div>
                                <div class="text-sm text-red-600">No Images</div>
                            </div>
                        </div>
                        <div class="mt-4 text-sm text-yellow-700">
                            <strong>Image Upload Rate:</strong> {{ $attendances->count() > 0 ? round(($attendances->where('attached_image', '!=', null)->count() / $attendances->count()) * 100, 1) : 0 }}%
                        </div>
                    </div>
                @endif

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
                                <option value="block">Block Students</option>
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
                    <!-- Block Students Accordion -->
                    @if($blockAttendances->count() > 0)
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <div class="accordion-header bg-purple-50 hover:bg-purple-100 cursor-pointer transition-colors duration-200">
                                <div class="p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <i class="fas fa-chevron-down accordion-icon mr-3 text-purple-600 transition-transform duration-200"></i>
                                            <h3 class="text-lg font-medium text-purple-900">
                                                <i class="fas fa-user-clock text-purple-600 mr-2"></i>
                                                Block Students
                                            </h3>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <span class="text-sm text-purple-600">
                                                <span class="font-medium">{{ $blockAttendances->count() }}</span> students
                                            </span>
                                            <span class="text-sm text-purple-600">
                                                <span class="font-medium">{{ $blockAttendances->where('status', 'present')->count() }}</span> present
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-content bg-white">
                                <div class="p-4">
                                    <div class="w-full">
                                        <table class="w-full divide-y divide-gray-200 attendance-table">
                                            <thead class="bg-purple-50">
                                                <tr>
                                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">Student</th>
                                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Student ID</th>
                                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8">Year Level</th>
                                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8">Student Type</th>
                                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8">Their Section</th>
                                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8">Status</th>
                                                    @if($session->session_type === 'lab')
                                                        <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8">PC Number</th>
                                                    @elseif($session->session_type === 'online')
                                                        <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8">Device Type</th>
                                                    @elseif($session->session_type === 'lecture')
                                                        <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8">Attached Image</th>
                                                    @endif
                                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Check-in Time</th>
                                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($blockAttendances->where('status', '!=', 'absent') as $attendance)
                                                    <tr class="bg-purple-50 attendance-row" 
                                                        data-name="{{ strtolower($attendance->user->name) }}"
                                                        data-status="{{ $attendance->status }}"
                                                        data-section="block"
                                                        data-type="{{ $attendance->user->student_type }}">
                                                        <td class="px-3 py-4">
                                                            <div class="flex items-center">
                                                                <div class="flex-shrink-0 h-10 w-10 mr-3">
                                                                    @if($attendance->user->profile_picture)
                                                                        <img class="h-10 w-10 rounded-full object-cover profile-picture" 
                                                                             src="{{ asset('storage/' . $attendance->user->profile_picture) }}" 
                                                                             alt="{{ $attendance->user->name }}'s profile picture">
                                                                    @else
                                                                        <div class="h-10 w-10 rounded-full profile-placeholder flex items-center justify-center text-xs">
                                                                            {{ strtoupper(substr($attendance->user->name, 0, 1)) }}
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="min-w-0">
                                                                    <div class="text-sm font-medium text-gray-900 truncate">{{ $attendance->user->name }}</div>
                                                                    <div class="text-sm text-gray-500 truncate">{{ $attendance->user->email }}</div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="px-2 py-4 text-sm text-gray-900">
                                                            {{ $attendance->user->student_id ?? 'N/A' }}
                                                        </td>
                                                        <td class="px-2 py-4 text-sm text-gray-900">
                                                            {{ $attendance->user->year_level ?? 'N/A' }}
                                                        </td>
                                                        <td class="px-2 py-4">
                                                            <span class="status-badge 
                                                                @if($attendance->user->student_type == 'regular') bg-blue-100 text-blue-800
                                                                @elseif($attendance->user->student_type == 'irregular') bg-yellow-100 text-yellow-800
                                                                @else bg-purple-100 text-purple-800
                                                                @endif">
                                                                {{ ucfirst($attendance->user->student_type ?? 'N/A') }}
                                                            </span>
                                                        </td>
                                                        <td class="px-2 py-4 text-sm text-gray-900">
                                                            <span class="status-badge bg-purple-100 text-purple-800">
                                                                {{ $attendance->user->section }}
                                                            </span>
                                                        </td>
                                                        <td class="px-2 py-4">
                                                            <span class="status-badge 
                                                                @if($attendance->status === 'present') bg-green-100 text-green-800
                                                                @elseif($attendance->status === 'late') bg-yellow-100 text-yellow-800
                                                                @else bg-red-100 text-red-800
                                                                @endif">
                                                                {{ ucfirst($attendance->status) }}
                                                            </span>
                                                        </td>
                                                        @if($session->session_type === 'lab')
                                                            <td class="px-2 py-4 text-sm text-gray-900">
                                                                <span class="status-badge bg-blue-100 text-blue-800">
                                                                    {{ $attendance->pc_number ?? 'N/A' }}
                                                                </span>
                                                            </td>
                                                        @elseif($session->session_type === 'online')
                                                            <td class="px-2 py-4 text-sm text-gray-900">
                                                                <span class="status-badge 
                                                                    @if($attendance->device_type === 'mobile') bg-green-100 text-green-800
                                                                    @elseif($attendance->device_type === 'desktop') bg-blue-100 text-blue-800
                                                                    @elseif($attendance->device_type === 'laptop') bg-purple-100 text-purple-800
                                                                    @else bg-gray-100 text-gray-800
                                                                    @endif">
                                                                    {{ ucfirst($attendance->device_type ?? 'N/A') }}
                                                                </span>
                                                            </td>
                                                        @elseif($session->session_type === 'lecture')
                                                            <td class="px-2 py-4 text-sm text-gray-900">
                                                                @if($attendance->attached_image)
                                                                    <a href="{{ asset('storage/' . $attendance->attached_image) }}" target="_blank" 
                                                                       class="action-button bg-blue-500 text-white">
                                                                        <i class="fas fa-image mr-1.5"></i>
                                                                        View Image
                                                                    </a>
                                                                @else
                                                                    <span class="text-gray-500 text-xs">No image</span>
                                                                @endif
                                                            </td>
                                                        @endif
                                                        <td class="px-2 py-4 text-sm text-gray-900">
                                                            {{ $attendance->check_in_time->format('M d, Y H:i:s') }}
                                                        </td>
                                                        <td class="px-2 py-4 text-sm text-gray-900">
                                                            <div class="flex flex-col space-y-2">
                                                                <button type="button" 
                                                                        onclick="editAttendance('{{ $attendance->id }}', '{{ $attendance->status }}', '{{ $attendance->pc_number ?? '' }}', '{{ $attendance->device_type ?? '' }}')" 
                                                                        class="action-button bg-blue-500 text-white">
                                                                    <i class="fas fa-edit mr-1.5"></i>
                                                                    Edit
                                                                </button>
                                                                <button type="button" 
                                                                        onclick="deleteAttendance('{{ $attendance->id }}', '{{ addslashes($attendance->user->name) }}')" 
                                                                        class="action-button bg-red-500 text-white">
                                                                    <i class="fas fa-trash mr-1.5"></i>
                                                                    Delete
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        
                                        <!-- Absent Students for Block Section -->
                                        @if($blockAttendances->where('status', 'absent')->count() > 0)
                                            <div class="mt-4 pt-4 border-t border-gray-200">
                                                <h4 class="text-sm font-medium text-red-700 mb-3">
                                                    <i class="fas fa-user-times mr-2"></i>
                                                    Absent Students ({{ $blockAttendances->where('status', 'absent')->count() }})
                                                </h4>
                                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                                    @foreach($blockAttendances->where('status', 'absent') as $attendance)
                                                        <div class="flex items-center p-3 bg-red-50 border border-red-200 rounded-lg">
                                                            <div class="flex-shrink-0 h-8 w-8 mr-3">
                                                                @if($attendance->user->profile_picture)
                                                                    <img class="h-8 w-8 rounded-full object-cover" 
                                                                         src="{{ asset('storage/' . $attendance->user->profile_picture) }}" 
                                                                         alt="{{ $attendance->user->name }}'s profile picture">
                                                                @else
                                                                    <div class="h-8 w-8 rounded-full profile-placeholder flex items-center justify-center text-xs">
                                                                        {{ strtoupper(substr($attendance->user->name, 0, 1)) }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="min-w-0 flex-1">
                                                                <p class="text-sm font-medium text-red-900 truncate">{{ $attendance->user->name }}</p>
                                                                <p class="text-xs text-red-600 truncate">{{ $attendance->user->email }}</p>
                                                                <p class="text-xs text-red-500">{{ $attendance->user->student_id ?? 'N/A' }}</p>
                                                            </div>
                                                            <div class="flex-shrink-0">
                                                                <div class="flex flex-col space-y-1">
                                                                    <button type="button" 
                                                                            onclick="editAttendance('{{ $attendance->id }}', '{{ $attendance->status }}', '{{ $attendance->pc_number ?? '' }}', '{{ $attendance->device_type ?? '' }}')" 
                                                                            class="action-button bg-blue-500 text-white text-xs px-2 py-1">
                                                                        <i class="fas fa-edit mr-1"></i>
                                                                        Edit
                                                                    </button>
                                                                    <button type="button" 
                                                                            onclick="deleteAttendance('{{ $attendance->id }}', '{{ addslashes($attendance->user->name) }}')" 
                                                                            class="action-button bg-red-500 text-white text-xs px-2 py-1">
                                                                        <i class="fas fa-trash mr-1"></i>
                                                                        Delete
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

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
                                    <div class="w-full">
                                        <table class="w-full divide-y divide-gray-200 attendance-table">
                                            <thead class="bg-green-50">
                                                <tr>
                                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">Student</th>
                                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Student ID</th>
                                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8">Year Level</th>
                                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8">Student Type</th>
                                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8">Their Section</th>
                                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8">Status</th>
                                                    @if($session->session_type === 'lab')
                                                        <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8">PC Number</th>
                                                    @elseif($session->session_type === 'online')
                                                        <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8">Device Type</th>
                                                    @elseif($session->session_type === 'lecture')
                                                        <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8">Attached Image</th>
                                                    @endif
                                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Check-in Time</th>
                                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($regularAttendances->where('status', '!=', 'absent') as $attendance)
                                                    <tr class="bg-green-50 attendance-row" 
                                                        data-name="{{ strtolower($attendance->user->name) }}"
                                                        data-status="{{ $attendance->status }}"
                                                        data-section="regular"
                                                        data-type="{{ $attendance->user->student_type }}">
                                                        <td class="px-3 py-4">
                                                            <div class="flex items-center">
                                                                <div class="flex-shrink-0 h-10 w-10 mr-3">
                                                                    @if($attendance->user->profile_picture)
                                                                        <img class="h-10 w-10 rounded-full object-cover profile-picture" 
                                                                             src="{{ asset('storage/' . $attendance->user->profile_picture) }}" 
                                                                             alt="{{ $attendance->user->name }}'s profile picture">
                                                                    @else
                                                                        <div class="h-10 w-10 rounded-full profile-placeholder flex items-center justify-center text-xs">
                                                                            {{ strtoupper(substr($attendance->user->name, 0, 1)) }}
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="min-w-0">
                                                                    <div class="text-sm font-medium text-gray-900 truncate">{{ $attendance->user->name }}</div>
                                                                    <div class="text-sm text-gray-500 truncate">{{ $attendance->user->email }}</div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="px-2 py-4 text-sm text-gray-900">
                                                            {{ $attendance->user->student_id ?? 'N/A' }}
                                                        </td>
                                                        <td class="px-2 py-4 text-sm text-gray-900">
                                                            {{ $attendance->user->year_level ?? 'N/A' }}
                                                        </td>
                                                        <td class="px-2 py-4">
                                                            <span class="status-badge 
                                                                @if($attendance->user->student_type == 'regular') bg-blue-100 text-blue-800
                                                                @elseif($attendance->user->student_type == 'irregular') bg-yellow-100 text-yellow-800
                                                                @else bg-purple-100 text-purple-800
                                                                @endif">
                                                                {{ ucfirst($attendance->user->student_type ?? 'N/A') }}
                                                            </span>
                                                        </td>
                                                        <td class="px-2 py-4 text-sm text-gray-900">
                                                            <span class="status-badge bg-purple-100 text-purple-800">
                                                                {{ $attendance->user->section }}
                                                            </span>
                                                        </td>
                                                        <td class="px-2 py-4">
                                                            <span class="status-badge 
                                                                @if($attendance->status === 'present') bg-green-100 text-green-800
                                                                @elseif($attendance->status === 'late') bg-yellow-100 text-yellow-800
                                                                @else bg-red-100 text-red-800
                                                                @endif">
                                                                {{ ucfirst($attendance->status) }}
                                                            </span>
                                                        </td>
                                                        @if($session->session_type === 'lab')
                                                            <td class="px-2 py-4 text-sm text-gray-900">
                                                                <span class="status-badge bg-blue-100 text-blue-800">
                                                                    {{ $attendance->pc_number ?? 'N/A' }}
                                                                </span>
                                                            </td>
                                                        @elseif($session->session_type === 'online')
                                                            <td class="px-2 py-4 text-sm text-gray-900">
                                                                <span class="status-badge 
                                                                    @if($attendance->device_type === 'mobile') bg-green-100 text-green-800
                                                                    @elseif($attendance->device_type === 'desktop') bg-blue-100 text-blue-800
                                                                    @elseif($attendance->device_type === 'laptop') bg-purple-100 text-purple-800
                                                                    @else bg-gray-100 text-gray-800
                                                                    @endif">
                                                                    {{ ucfirst($attendance->device_type ?? 'N/A') }}
                                                                </span>
                                                            </td>
                                                        @elseif($session->session_type === 'lecture')
                                                            <td class="px-2 py-4 text-sm text-gray-900">
                                                                @if($attendance->attached_image)
                                                                    <a href="{{ asset('storage/' . $attendance->attached_image) }}" target="_blank" 
                                                                       class="action-button bg-blue-500 text-white">
                                                                        <i class="fas fa-image mr-1.5"></i>
                                                                        View Image
                                                                    </a>
                                                                @else
                                                                    <span class="text-gray-500 text-xs">No image</span>
                                                                @endif
                                                            </td>
                                                        @endif
                                                        <td class="px-2 py-4 text-sm text-gray-900">
                                                            {{ $attendance->check_in_time->format('M d, Y H:i:s') }}
                                                        </td>
                                                        <td class="px-2 py-4 text-sm text-gray-900">
                                                            <div class="flex flex-col space-y-2">
                                                                <button type="button" 
                                                                        onclick="editAttendance('{{ $attendance->id }}', '{{ $attendance->status }}', '{{ $attendance->pc_number ?? '' }}', '{{ $attendance->device_type ?? '' }}')" 
                                                                        class="action-button bg-blue-500 text-white">
                                                                    <i class="fas fa-edit mr-1.5"></i>
                                                                    Edit
                                                                </button>
                                                                <button type="button" 
                                                                        onclick="deleteAttendance('{{ $attendance->id }}', '{{ addslashes($attendance->user->name) }}')" 
                                                                        class="action-button bg-red-500 text-white">
                                                                    <i class="fas fa-trash mr-1.5"></i>
                                                                    Delete
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        
                                        <!-- Absent Students for Regular Section -->
                                        @if($regularAttendances->where('status', 'absent')->count() > 0)
                                            <div class="mt-4 pt-4 border-t border-gray-200">
                                                <h4 class="text-sm font-medium text-red-700 mb-3">
                                                    <i class="fas fa-user-times mr-2"></i>
                                                    Absent Students ({{ $regularAttendances->where('status', 'absent')->count() }})
                                                </h4>
                                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                                    @foreach($regularAttendances->where('status', 'absent') as $attendance)
                                                        <div class="flex items-center p-3 bg-red-50 border border-red-200 rounded-lg">
                                                            <div class="flex-shrink-0 h-8 w-8 mr-3">
                                                                @if($attendance->user->profile_picture)
                                                                    <img class="h-8 w-8 rounded-full object-cover" 
                                                                         src="{{ asset('storage/' . $attendance->user->profile_picture) }}" 
                                                                         alt="{{ $attendance->user->name }}'s profile picture">
                                                                @else
                                                                    <div class="h-8 w-8 rounded-full profile-placeholder flex items-center justify-center text-xs">
                                                                        {{ strtoupper(substr($attendance->user->name, 0, 1)) }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="min-w-0 flex-1">
                                                                <p class="text-sm font-medium text-red-900 truncate">{{ $attendance->user->name }}</p>
                                                                <p class="text-xs text-red-600 truncate">{{ $attendance->user->email }}</p>
                                                                <p class="text-xs text-red-500">{{ $attendance->user->student_id ?? 'N/A' }}</p>
                                                            </div>
                                                            <div class="flex-shrink-0">
                                                                <div class="flex flex-col space-y-1">
                                                                    <button type="button" 
                                                                            onclick="editAttendance('{{ $attendance->id }}', '{{ $attendance->status }}', '{{ $attendance->pc_number ?? '' }}', '{{ $attendance->device_type ?? '' }}')" 
                                                                            class="action-button bg-blue-500 text-white text-xs px-2 py-1">
                                                                        <i class="fas fa-edit mr-1"></i>
                                                                        Edit
                                                                    </button>
                                                                    <button type="button" 
                                                                            onclick="deleteAttendance('{{ $attendance->id }}', '{{ addslashes($attendance->user->name) }}')" 
                                                                            class="action-button bg-red-500 text-white text-xs px-2 py-1">
                                                                        <i class="fas fa-trash mr-1"></i>
                                                                        Delete
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
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
                                    <div class="w-full">
                                        <table class="w-full divide-y divide-gray-200 attendance-table">
                                            <thead class="bg-yellow-50">
                                                <tr>
                                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">Student</th>
                                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Student ID</th>
                                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8">Year Level</th>
                                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8">Student Type</th>
                                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8">Their Section</th>
                                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8">Status</th>
                                                    @if($session->session_type === 'lab')
                                                        <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8">PC Number</th>
                                                    @elseif($session->session_type === 'online')
                                                        <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8">Device Type</th>
                                                    @elseif($session->session_type === 'lecture')
                                                        <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8">Attached Image</th>
                                                    @endif
                                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Check-in Time</th>
                                                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($irregularAttendances->where('status', '!=', 'absent') as $attendance)
                                                    <tr class="bg-yellow-50 attendance-row" 
                                                        data-name="{{ strtolower($attendance->user->name) }}"
                                                        data-status="{{ $attendance->status }}"
                                                        data-section="irregular"
                                                        data-type="{{ $attendance->user->student_type }}">
                                                        <td class="px-3 py-4">
                                                            <div class="flex items-center">
                                                                <div class="flex-shrink-0 h-10 w-10 mr-3">
                                                                    @if($attendance->user->profile_picture)
                                                                        <img class="h-10 w-10 rounded-full object-cover profile-picture" 
                                                                             src="{{ asset('storage/' . $attendance->user->profile_picture) }}" 
                                                                             alt="{{ $attendance->user->name }}'s profile picture">
                                                                    @else
                                                                        <div class="h-10 w-10 rounded-full profile-placeholder flex items-center justify-center text-xs">
                                                                            {{ strtoupper(substr($attendance->user->name, 0, 1)) }}
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="min-w-0">
                                                                    <div class="text-sm font-medium text-gray-900 truncate">{{ $attendance->user->name }}</div>
                                                                    <div class="text-sm text-gray-500 truncate">{{ $attendance->user->email }}</div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="px-2 py-4 text-sm text-gray-900">
                                                            {{ $attendance->user->student_id ?? 'N/A' }}
                                                        </td>
                                                        <td class="px-2 py-4 text-sm text-gray-900">
                                                            {{ $attendance->user->year_level ?? 'N/A' }}
                                                        </td>
                                                        <td class="px-2 py-4">
                                                            <span class="status-badge 
                                                                @if($attendance->user->student_type == 'regular') bg-blue-100 text-blue-800
                                                                @elseif($attendance->user->student_type == 'irregular') bg-yellow-100 text-yellow-800
                                                                @else bg-purple-100 text-purple-800
                                                                @endif">
                                                                {{ ucfirst($attendance->user->student_type ?? 'N/A') }}
                                                            </span>
                                                        </td>
                                                        <td class="px-2 py-4 text-sm text-gray-900">
                                                            <span class="status-badge bg-purple-100 text-purple-800">
                                                                {{ $attendance->user->section }}
                                                            </span>
                                                        </td>
                                                        <td class="px-2 py-4">
                                                            <span class="status-badge 
                                                                @if($attendance->status === 'present') bg-green-100 text-green-800
                                                                @elseif($attendance->status === 'late') bg-yellow-100 text-yellow-800
                                                                @else bg-red-100 text-red-800
                                                                @endif">
                                                                {{ ucfirst($attendance->status) }}
                                                            </span>
                                                        </td>
                                                        @if($session->session_type === 'lab')
                                                            <td class="px-2 py-4 text-sm text-gray-900">
                                                                <span class="status-badge bg-blue-100 text-blue-800">
                                                                    {{ $attendance->pc_number ?? 'N/A' }}
                                                                </span>
                                                            </td>
                                                        @elseif($session->session_type === 'online')
                                                            <td class="px-2 py-4 text-sm text-gray-900">
                                                                <span class="status-badge 
                                                                    @if($attendance->device_type === 'mobile') bg-green-100 text-green-800
                                                                    @elseif($attendance->device_type === 'desktop') bg-blue-100 text-blue-800
                                                                    @elseif($attendance->device_type === 'laptop') bg-purple-100 text-purple-800
                                                                    @else bg-gray-100 text-gray-800
                                                                    @endif">
                                                                    {{ ucfirst($attendance->device_type ?? 'N/A') }}
                                                                </span>
                                                            </td>
                                                        @elseif($session->session_type === 'lecture')
                                                            <td class="px-2 py-4 text-sm text-gray-900">
                                                                @if($attendance->attached_image)
                                                                    <a href="{{ asset('storage/' . $attendance->attached_image) }}" target="_blank" 
                                                                       class="action-button bg-blue-500 text-white">
                                                                        <i class="fas fa-image mr-1.5"></i>
                                                                        View Image
                                                                    </a>
                                                                @else
                                                                    <span class="text-gray-500 text-xs">No image</span>
                                                                @endif
                                                            </td>
                                                        @endif
                                                        <td class="px-2 py-4 text-sm text-gray-900">
                                                            {{ $attendance->check_in_time->format('M d, Y H:i:s') }}
                                                        </td>
                                                        <td class="px-2 py-4 text-sm text-gray-900">
                                                            <div class="flex flex-col space-y-2">
                                                                <button type="button" 
                                                                        onclick="editAttendance('{{ $attendance->id }}', '{{ $attendance->status }}', '{{ $attendance->pc_number ?? '' }}', '{{ $attendance->device_type ?? '' }}')" 
                                                                        class="action-button bg-blue-500 text-white">
                                                                    <i class="fas fa-edit mr-1.5"></i>
                                                                    Edit
                                                                </button>
                                                                <button type="button" 
                                                                        onclick="deleteAttendance('{{ $attendance->id }}', '{{ addslashes($attendance->user->name) }}')" 
                                                                        class="action-button bg-red-500 text-white">
                                                                    <i class="fas fa-trash mr-1.5"></i>
                                                                    Delete
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        
                                        <!-- Absent Students for Irregular Section -->
                                        @if($irregularAttendances->where('status', 'absent')->count() > 0)
                                            <div class="mt-4 pt-4 border-t border-gray-200">
                                                <h4 class="text-sm font-medium text-red-700 mb-3">
                                                    <i class="fas fa-user-times mr-2"></i>
                                                    Absent Students ({{ $irregularAttendances->where('status', 'absent')->count() }})
                                                </h4>
                                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                                    @foreach($irregularAttendances->where('status', 'absent') as $attendance)
                                                        <div class="flex items-center p-3 bg-red-50 border border-red-200 rounded-lg">
                                                            <div class="flex-shrink-0 h-8 w-8 mr-3">
                                                                @if($attendance->user->profile_picture)
                                                                    <img class="h-8 w-8 rounded-full object-cover" 
                                                                         src="{{ asset('storage/' . $attendance->user->profile_picture) }}" 
                                                                         alt="{{ $attendance->user->name }}'s profile picture">
                                                                @else
                                                                    <div class="h-8 w-8 rounded-full profile-placeholder flex items-center justify-center text-xs">
                                                                        {{ strtoupper(substr($attendance->user->name, 0, 1)) }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="min-w-0 flex-1">
                                                                <p class="text-sm font-medium text-red-900 truncate">{{ $attendance->user->name }}</p>
                                                                <p class="text-xs text-red-600 truncate">{{ $attendance->user->email }}</p>
                                                                <p class="text-xs text-red-500">{{ $attendance->user->student_id ?? 'N/A' }}</p>
                                                            </div>
                                                            <div class="flex-shrink-0">
                                                                <div class="flex flex-col space-y-1">
                                                                    <button type="button" 
                                                                            onclick="editAttendance('{{ $attendance->id }}', '{{ $attendance->status }}', '{{ $attendance->pc_number ?? '' }}', '{{ $attendance->device_type ?? '' }}')" 
                                                                            class="action-button bg-blue-500 text-white text-xs px-2 py-1">
                                                                        <i class="fas fa-edit mr-1"></i>
                                                                        Edit
                                                                    </button>
                                                                    <button type="button" 
                                                                            onclick="deleteAttendance('{{ $attendance->id }}', '{{ addslashes($attendance->user->name) }}')" 
                                                                            class="action-button bg-red-500 text-white text-xs px-2 py-1">
                                                                        <i class="fas fa-trash mr-1"></i>
                                                                        Delete
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
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

<!-- Students Who Haven't Marked Attendance Yet -->
@if($session->is_active)
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-clock mr-2"></i>
                        Students Without Attendance Records
                    </h3>
                    
                    @php
                        $studentsNotMarked = $targetSectionStudents->filter(function($student) use ($attendances) {
                            // Check if student has any attendance record (present, late, or absent)
                            return !$attendances->where('user_id', $student->id)->first();
                        });
                    @endphp
                    
                    @if($studentsNotMarked->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($studentsNotMarked as $student)
                                <div class="flex items-center p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <div class="flex-shrink-0 h-10 w-10 mr-3">
                                        @if($student->profile_picture)
                                            <img class="h-10 w-10 rounded-full object-cover" 
                                                 src="{{ asset('storage/' . $student->profile_picture) }}" 
                                                 alt="{{ $student->name }}'s profile picture">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-800 font-semibold">
                                                {{ strtoupper(substr($student->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $student->student_id ?? 'N/A' }}</div>
                                        <div class="text-xs text-yellow-600">No attendance record</div>
                                    </div>
                                    <div class="flex-shrink-0 space-y-2">
                                        <!-- Mark Present Button -->
                                        <form action="{{ route('teacher.sessions.mark-present', $session) }}" method="POST" class="block">
                                            @csrf
                                            <input type="hidden" name="student_id" value="{{ $student->id }}">
                                            @if($session->session_type === 'lab')
                                                <input type="number" name="pc_number" min="1" max="40" 
                                                       placeholder="PC #" 
                                                       class="w-16 text-xs px-2 py-1 border border-gray-300 rounded mb-2"
                                                       required>
                                            @elseif($session->session_type === 'online')
                                                <select name="device_type" 
                                                        class="w-20 text-xs px-2 py-1 border border-gray-300 rounded mb-2"
                                                        required>
                                                    <option value="">Device</option>
                                                    <option value="mobile">Mobile</option>
                                                    <option value="desktop">Desktop</option>
                                                    <option value="laptop">Laptop</option>
                                                </select>
                                            @elseif($session->session_type === 'lecture')
                                                <input type="file" name="attached_image" 
                                                       accept="image/*"
                                                       class="w-24 text-xs px-2 py-1 border border-gray-300 rounded mb-2"
                                                       required>
                                            @endif
                                            <button type="submit" 
                                                    class="w-full text-xs bg-green-100 text-green-700 px-2 py-1 rounded hover:bg-green-200 transition-colors"
                                                    onclick="return confirm('Mark {{ $student->name }} as present?')">
                                                Mark Present
                                            </button>
                                        </form>
                                        
                                        <!-- Mark Absent Button -->
                                        <form action="{{ route('teacher.sessions.mark-absent', $session) }}" method="POST" class="block">
                                            @csrf
                                            <input type="hidden" name="student_id" value="{{ $student->id }}">
                                            <button type="submit" 
                                                    class="w-full text-xs bg-red-100 text-red-700 px-2 py-1 rounded hover:bg-red-200 transition-colors"
                                                    onclick="return confirm('Mark {{ $student->name }} as absent?')">
                                                Mark Absent
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                <div class="text-sm text-blue-700">
                                    <strong>Note:</strong> These students have no attendance records yet. 
                                    You can manually mark them as <strong>present</strong> (with required details) or <strong>absent</strong>.
                                    If a student already has attendance marked but shows as absent, use the "Edit" button in the attendance list above to change their status.
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-check-circle text-4xl text-green-400 mb-4"></i>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">All Students Have Attendance Records</h4>
                            <p class="text-gray-600">Every student in section {{ $session->section }} has an attendance record. Use the "Edit" buttons above to modify any attendance details.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Edit Attendance Modal -->
<div id="editAttendanceModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Attendance</h3>
            <form id="editAttendanceForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="editStatus" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="present">Present</option>
                        <option value="late">Late</option>
                        <option value="absent">Absent</option>
                    </select>
                </div>

                @if($session->session_type === 'lab')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">PC Number</label>
                    <input type="number" name="pc_number" id="editPcNumber" min="1" max="40" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                @elseif($session->session_type === 'online')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Device Type</label>
                    <select name="device_type" id="editDeviceType" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="mobile">Mobile</option>
                        <option value="desktop">Desktop</option>
                        <option value="laptop">Laptop</option>
                    </select>
                </div>
                @elseif($session->session_type === 'lecture')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Attached Image</label>
                    <input type="file" name="attached_image" accept="image/*" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Leave empty to keep existing image</p>
                </div>
                @endif

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeEditModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                        Update Attendance
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Attendance Confirmation Modal -->
<div id="deleteAttendanceModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Delete Attendance Record</h3>
            <p class="text-sm text-gray-500 mb-6" id="deleteConfirmationText">
                Are you sure you want to delete this attendance record? This action cannot be undone.
            </p>
            <form id="deleteAttendanceForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeDeleteModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                        Delete Record
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

<script>
// Global functions for attendance management
function editAttendance(attendanceId, status, pcNumber, deviceType) {
    console.log('Edit button clicked:', { attendanceId, status, pcNumber, deviceType });
    
    // Set form action
    document.getElementById('editAttendanceForm').action = `/teacher/sessions/{{ $session->id }}/attendance/${attendanceId}`;
    
    // Set current values
    document.getElementById('editStatus').value = status;
    
    if (pcNumber) {
        document.getElementById('editPcNumber').value = pcNumber;
    }
    
    if (deviceType) {
        document.getElementById('editDeviceType').value = deviceType;
    }
    
    // Show modal
    document.getElementById('editAttendanceModal').classList.remove('hidden');
    console.log('Edit modal should be visible now');
}

function deleteAttendance(attendanceId, studentName) {
    console.log('Delete button clicked:', { attendanceId, studentName });
    
    // Set the confirmation text
    document.getElementById('deleteConfirmationText').innerHTML = 
        `Are you sure you want to delete <strong>${studentName}</strong>'s attendance record? This action cannot be undone.`;
    
    // Set the form action
    document.getElementById('deleteAttendanceForm').action = `/teacher/sessions/{{ $session->id }}/attendance/${attendanceId}`;
    
    // Show the delete confirmation modal
    document.getElementById('deleteAttendanceModal').classList.remove('hidden');
    console.log('Delete modal should be visible now');
}

function closeEditModal() {
    document.getElementById('editAttendanceModal').classList.add('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteAttendanceModal').classList.add('hidden');
}

// Make functions globally accessible
window.editAttendance = editAttendance;
window.deleteAttendance = deleteAttendance;
window.closeEditModal = closeEditModal;
window.closeDeleteModal = closeDeleteModal;

console.log('Global functions defined:', {
    editAttendance: typeof editAttendance,
    deleteAttendance: typeof deleteAttendance,
    closeEditModal: typeof closeEditModal,
    closeDeleteModal: typeof closeDeleteModal
});

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, checking for elements...');
    
    // Check if required elements exist
    const editModal = document.getElementById('editAttendanceModal');
    const deleteModal = document.getElementById('deleteAttendanceModal');
    const editForm = document.getElementById('editAttendanceForm');
    const deleteForm = document.getElementById('deleteAttendanceForm');
    
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

        // Update block students count
        const blockRows = document.querySelectorAll('.attendance-row[data-section="block"]');
        const visibleBlockRows = Array.from(blockRows).filter(row => row.style.display !== 'none');
        const presentBlockRows = visibleBlockRows.filter(row => row.getAttribute('data-status') === 'present');
        
        const blockHeader = document.querySelector('.accordion-header:has(.fa-user-clock)');
        if (blockHeader) {
            const countSpans = blockHeader.querySelectorAll('span');
            if (countSpans.length >= 2) {
                countSpans[0].innerHTML = `<span class="font-medium">${visibleBlockRows.length}</span> students`;
                countSpans[1].innerHTML = `<span class="font-medium">${presentBlockRows.length}</span> present`;
            }
        }
    }

    // Close modal when clicking outside
    document.getElementById('editAttendanceModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });

    // Close delete modal when clicking outside
    document.getElementById('deleteAttendanceModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });

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