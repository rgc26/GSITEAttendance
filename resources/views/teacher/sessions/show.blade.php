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
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0.5rem 0.75rem !important;
        border-radius: 0.375rem !important;
        font-size: 0.75rem !important;
        font-weight: 500 !important;
        text-decoration: none !important;
        transition: all 0.2s ease-in-out !important;
        cursor: pointer !important;
        border: none !important;
        outline: none !important;
        width: 100% !important;
        min-height: 36px !important;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06) !important;
    }
    
    .action-button:focus {
        outline: none !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
    }
    
    .action-button:hover {
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
    }
    
    .action-button:active {
        transform: translateY(0) !important;
    }
    
    /* Specific button color improvements */
    .action-button.bg-blue-500 {
        background-color: #3b82f6 !important;
        color: white !important;
    }
    
    .action-button.bg-blue-500:hover {
        background-color: #2563eb !important;
    }
    
    .action-button.bg-red-500 {
        background-color: #ef4444 !important;
        color: white !important;
    }
    
    .action-button.bg-red-500:hover {
        background-color: #dc2626 !important;
    }
    
    .action-button.bg-green-500 {
        background-color: #10b981 !important;
        color: white !important;
    }
    
    .action-button.bg-green-500:hover {
        background-color: #059669 !important;
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
        padding: 2.5rem;
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
    
    /* Custom CSS for Delete Button */
    .delete-student-btn {
        position: absolute !important;
        top: 8px !important;
        right: 8px !important;
        z-index: 50 !important;
        width: 32px !important;
        height: 32px !important;
        background-color: #dc2626 !important;
        color: white !important;
        border-radius: 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border: none !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
    }
    
    .delete-student-btn:hover {
        background-color: #b91c1c !important;
        transform: scale(1.1) !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
    }
    
    .delete-student-btn:active {
        transform: scale(0.95) !important;
    }
    
    .delete-student-btn i {
        font-size: 14px !important;
        font-weight: bold !important;
    }

    /* Custom CSS for Students Without Attendance Records */
    .students-without-attendance-card {
        display: flex !important;
        flex-direction: column !important;
        align-items: stretch !important;
        padding: 1rem !important;
        background-color: #fefce8 !important;
        border: 1px solid #fde047 !important;
        border-radius: 0.5rem !important;
        position: relative !important;
        margin-bottom: 0.75rem !important;
    }

    .students-without-attendance-card .flex.items-center.mb-3 {
        margin-bottom: 1rem !important;
    }

    .students-without-attendance-card .student-info {
        flex: 1 !important;
        min-width: 0 !important;
    }

    .students-without-attendance-card .action-buttons {
        width: 100% !important;
        display: flex !important;
        flex-direction: row !important;
        gap: 0.5rem !important;
    }

    .students-without-attendance-card .action-buttons .flex {
        width: 100% !important;
        display: flex !important;
        gap: 0.5rem !important;
    }

    .students-without-attendance-card .action-buttons form {
        flex: 1 1 0% !important;
        min-width: 0 !important;
        max-width: 50% !important;
    }

    .students-without-attendance-card .action-buttons button {
        width: 100% !important;
        height: 40px !important;
        padding: 0.5rem 0.75rem !important;
        font-size: 0.75rem !important;
        font-weight: 500 !important;
        border-radius: 0.375rem !important;
        transition: all 0.2s ease !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        white-space: nowrap !important;
    }

    /* Button container styling for attendance tables */
    .attendance-table .flex.flex-col.space-y-2 {
        min-width: 140px !important;
        gap: 0.75rem !important;
    }
    
    .attendance-table .flex.flex-col.space-y-1 {
        min-width: 140px !important;
        gap: 0.5rem !important;
    }
    
    /* Ensure consistent button sizing in tables */
    .attendance-table .action-button {
        min-width: 40px !important;
        max-width: 40px !important;
        height: 36px !important;
        margin-bottom: 0 !important;
        font-size: 0.875rem !important;
        padding: 0.5rem !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    
    .attendance-table .action-button:last-child {
        margin-bottom: 0 !important;
    }
    
    /* Force proper spacing between buttons */
    .attendance-table .space-y-2 > * + * {
        margin-top: 0 !important;
    }
    
    .attendance-table .space-x-2 > * + * {
        margin-left: 0.5rem !important;
    }
    
    /* Ensure button containers have proper height */
    .attendance-table td:last-child {
        min-height: 60px !important;
        vertical-align: middle !important;
        padding: 0.5rem !important;
    }
    
    /* Button spacing override for Tailwind */
    .attendance-table .space-y-2 > button {
        margin-top: 0 !important;
    }
    
    .attendance-table .space-y-2 > button:first-child {
        margin-top: 0 !important;
    }
    
    /* Specific styling for action buttons in tables */
    .attendance-table .action-button.bg-blue-500 {
        background-color: #3b82f6 !important;
        color: white !important;
    }
    
    .attendance-table .action-button.bg-red-500 {
        background-color: #ef4444 !important;
        color: white !important;
    }
    
    /* Actions column width and spacing */
    .attendance-table th:last-child,
    .attendance-table td:last-child {
        min-width: 160px !important;
        width: 160px !important;
        max-width: 160px !important;
    }
    
    /* Button text overflow handling */
    .attendance-table .action-button span,
    .attendance-table .action-button i {
        pointer-events: none !important;
    }
    
    /* Ensure buttons don't wrap or overflow */
    .attendance-table .action-button {
        word-wrap: normal !important;
        overflow-wrap: normal !important;
    }
    
    /* Icon-only button styling */
    .attendance-table .action-button i {
        font-size: 14px !important;
        line-height: 1 !important;
    }

    /* Enhanced Section Header Styling */
    .accordion-header {
        position: relative;
        overflow: hidden;
    }

    .accordion-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
        pointer-events: none;
    }

    .accordion-header:hover::before {
        background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0.08) 100%);
    }

    /* Section header count styling */
    .accordion-header .text-center {
        background: rgba(255, 255, 255, 0.1);
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .accordion-header .text-center:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }

    /* Enhanced Table Styling */
    .attendance-table {
        border-radius: 0.5rem;
        overflow: visible;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        width: 100%;
        table-layout: auto;
    }

    .attendance-table thead {
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .attendance-table th {
        font-weight: 700 !important;
        font-size: 0.875rem !important;
        padding: 1rem 0.75rem !important;
        background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%) !important;
        color: #581c87 !important;
        border-left: 2px solid #c084fc !important;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1) !important;
        transition: all 0.2s ease !important;
        white-space: normal !important;
        word-wrap: break-word !important;
        min-width: auto !important;
    }

    .attendance-table th:first-child {
        border-left: none !important;
    }

    .attendance-table th:hover {
        background: linear-gradient(135deg, #e9d5ff 0%, #d8b4fe 100%) !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 8px rgba(147, 51, 234, 0.2) !important;
    }

    .attendance-table tbody tr {
        transition: all 0.2s ease !important;
        border-bottom: 1px solid #e5e7eb !important;
    }

    .attendance-table tbody tr:hover {
        background-color: #faf5ff !important;
        transform: translateX(2px) !important;
        box-shadow: 0 2px 4px rgba(147, 51, 234, 0.1) !important;
    }

    .attendance-table tbody tr:nth-child(even) {
        background-color: #fefefe !important;
    }

    .attendance-table tbody tr:nth-child(even):hover {
        background-color: #faf5ff !important;
    }

    .attendance-table td {
        padding: 1rem 0.75rem !important;
        vertical-align: middle !important;
        border-left: 1px solid #f3f4f6 !important;
        transition: all 0.2s ease !important;
        white-space: normal !important;
        word-wrap: break-word !important;
        max-width: none !important;
    }

    .attendance-table td:first-child {
        border-left: none !important;
    }

    /* Remove table wrapper constraints */
    .table-wrapper {
        overflow: visible;
        width: 100%;
    }

    /* Make main container wider */
    .mobile-friendly-container {
        padding: 2.5rem !important;
        max-width: none !important;
        width: 100% !important;
    }

    /* Ensure content area is full width */
    .max-w-7xl {
        max-width: none !important;
        width: 100% !important;
    }

    /* Responsive adjustments */
    @media (min-width: 1280px) {
        .mobile-friendly-container {
            padding: 3rem !important;
        }
    }

    @media (min-width: 1536px) {
        .mobile-friendly-container {
            padding: 4rem !important;
        }
    }

    /* Enhanced Status Badge Styling */
    .status-badge {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0.375rem 0.75rem !important;
        border-radius: 9999px !important;
        font-size: 0.75rem !important;
        font-weight: 600 !important;
        text-align: center !important;
        white-space: nowrap !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
        transition: all 0.2s ease !important;
        min-width: 60px !important;
    }

    .status-badge:hover {
        transform: translateY(-1px) !important;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15) !important;
    }

    /* Table Header Icons */
    .attendance-table th i {
        font-size: 0.875rem !important;
        opacity: 0.8 !important;
        transition: all 0.2s ease !important;
    }

    .attendance-table th:hover i {
        opacity: 1 !important;
        transform: scale(1.1) !important;
    }

    /* Responsive table improvements */
    @media (max-width: 1024px) {
        .attendance-table th,
        .attendance-table td {
            padding: 0.75rem 0.5rem !important;
        }
        
        .attendance-table th {
            font-size: 0.75rem !important;
        }
    }

    @media (max-width: 768px) {
        .attendance-table th,
        .attendance-table td {
            padding: 0.5rem 0.375rem !important;
        }
        
        .attendance-table th {
            font-size: 0.7rem !important;
        }
        
        .status-badge {
            padding: 0.25rem 0.5rem !important;
            font-size: 0.65rem !important;
            min-width: 50px !important;
        }
    }

    /* Improved accordion icon animation */
    .accordion-icon {
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .accordion-header[aria-expanded="true"] .accordion-icon {
        transform: rotate(180deg);
    }

    /* Section-specific color enhancements */
    .accordion-header.bg-gradient-to-r.from-purple-500.to-purple-600 {
        box-shadow: 0 4px 15px rgba(147, 51, 234, 0.3);
    }

    .accordion-header.bg-gradient-to-r.from-green-500.to-green-600 {
        box-shadow: 0 4px 15px rgba(34, 197, 94, 0.3);
    }

    .accordion-header.bg-gradient-to-r.from-yellow-500.to-yellow-600 {
        box-shadow: 0 4px 15px rgba(234, 179, 8, 0.3);
    }

    /* Responsive header adjustments */
    @media (max-width: 768px) {
        .accordion-header .px-6 {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .accordion-header .py-5 {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
        
        .accordion-header h3 {
            font-size: 1.125rem;
        }
        
        .accordion-header .text-2xl {
            font-size: 1.25rem;
        }
        
        .accordion-header .space-x-6 {
            gap: 1rem;
        }
    }

    @media (max-width: 640px) {
        .accordion-header .flex.items-center.justify-between {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .accordion-header .space-x-6 {
            gap: 0.75rem;
        }
        
        .accordion-header .text-center {
            padding: 0.375rem 0.75rem;
        }
    }
</style>
<div class="py-6">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200 mobile-friendly-container">
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
                            <div class="flex justify-between items-start">
                                <span class="text-gray-600">Subject:</span>
                                <span class="font-medium text-right">{{ $session->subject->name }} ({{ $session->subject->code }})</span>
                            </div>
                            @if($session->name)
                                <div class="flex justify-between items-start">
                                    <span class="text-gray-600">Session Name:</span>
                                    <span class="font-medium text-right">{{ $session->name }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between items-start">
                                <span class="text-gray-600">Target Section:</span>
                                <span class="font-bold text-indigo-600 text-right">{{ $session->section }}</span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span class="text-gray-600">Session Type:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $session->session_type === 'lab' ? 'bg-blue-100 text-blue-800' : ($session->session_type === 'online' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($session->session_type ?? 'lecture') }}
                                </span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span class="text-gray-600">Attendance Code:</span>
                                <span class="font-mono font-bold text-lg text-right">{{ $session->code }}</span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span class="text-gray-600">Status:</span>
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
                            <div class="flex justify-between items-start">
                                <span class="text-gray-600">Started:</span>
                                <span class="font-medium text-right">{{ $session->start_time ? $session->start_time->setTimezone('Asia/Manila')->format('M d, Y g:i A') : 'Not started yet' }}</span>
                            </div>
                            @if($session->scheduled_start_time)
                                <div class="flex justify-between items-start">
                                    <span class="text-gray-600">Scheduled Start:</span>
                                    <span class="font-medium text-right">{{ $session->scheduled_start_time ? $session->scheduled_start_time->setTimezone('Asia/Manila')->format('M d, Y g:i A') : 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between items-start">
                                    <span class="text-gray-600">Grace Period End:</span>
                                    <span class="font-medium text-right">{{ $session->getGracePeriodEndTime() ? $session->getGracePeriodEndTime()->setTimezone('Asia/Manila')->format('M d, Y g:i A') : 'N/A' }}</span>
                                </div>
                            @endif
                            @if($session->scheduled_end_time)
                                <div class="flex justify-between items-start">
                                    <span class="text-gray-600">Scheduled End:</span>
                                    <span class="font-medium text-right">{{ $session->scheduled_end_time ? $session->scheduled_end_time->setTimezone('Asia/Manila')->format('M d, Y g:i A') : 'N/A' }}</span>
                                </div>
                            @endif
                            @if($session->end_time)
                                <div class="flex justify-between items-start">
                                    <span class="text-gray-600">Ended:</span>
                                    <span class="font-medium text-right">{{ $session->end_time->setTimezone('Asia/Manila')->format('M d, Y g:i A') }}</span>
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
                                <span class="text-green-700">Late:</span>
                                <span class="font-medium text-yellow-600">{{ $lateTargetStudents }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-green-700">Absent:</span>
                                <span class="font-medium text-red-600">{{ $absentTargetStudents }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-green-700">Not Marked Yet:</span>
                                <span class="font-medium text-gray-600">{{ $notMarkedYet }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-green-700">Attendance Rate:</span>
                                <span class="font-medium text-green-900">
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
                                <span class="text-blue-700">Block Students:</span>
                                <span class="font-medium text-purple-600">{{ $blockAttendances->count() }}</span>
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

                <!-- Helpful Note for Teachers -->
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-green-500 mr-3 text-xl"></i>
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
                        <div class="border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                            <div class="accordion-header bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 cursor-pointer transition-all duration-300">
                                <div class="px-6 py-5">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <i class="fas fa-chevron-down accordion-icon mr-4 text-white transition-transform duration-200 text-lg"></i>
                                            <h3 class="text-xl font-semibold text-white">
                                                <i class="fas fa-user-clock text-white mr-3 text-lg"></i>
                                                Block Students (Section {{ $session->section }})
                                            </h3>
                                        </div>
                                        <div class="flex items-center space-x-6">
                                            <div class="text-center">
                                                <div class="text-white text-sm font-medium">Total Students</div>
                                                <div class="text-white text-2xl font-bold">{{ $blockAttendances->count() }}</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-white text-sm font-medium">Present</div>
                                                <div class="text-white text-2xl font-bold">{{ $blockAttendances->where('status', 'present')->count() }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-content bg-white">
                                <div class="p-4">
                                    <div class="w-full">
                                        <table class="w-full divide-y divide-gray-200 attendance-table">
                                            <thead class="bg-gradient-to-r from-purple-100 to-purple-200 border-b-2 border-purple-300">
                                                <tr>
                                                    <th class="px-4 py-4 text-left text-sm font-bold text-purple-800 uppercase tracking-wider">
                                                        <div class="flex items-center">
                                                            <i class="fas fa-user mr-2 text-purple-600"></i>
                                                            Student
                                                        </div>
                                                    </th>
                                                    <th class="px-3 py-4 text-left text-sm font-bold text-purple-800 uppercase tracking-wider border-l border-purple-300">
                                                        <div class="flex items-center">
                                                            <i class="fas fa-id-card mr-2 text-purple-600"></i>
                                                            Student ID
                                                        </div>
                                                    </th>
                                                    <th class="px-3 py-4 text-left text-sm font-bold text-purple-800 uppercase tracking-wider border-l border-purple-300">
                                                        <div class="flex items-center">
                                                            <i class="fas fa-graduation-cap mr-2 text-purple-600"></i>
                                                            Year Level
                                                        </div>
                                                    </th>
                                                    <th class="px-3 py-4 text-left text-sm font-bold text-purple-800 uppercase tracking-wider border-l border-purple-300">
                                                        <div class="flex items-center">
                                                            <i class="fas fa-tag mr-2 text-purple-600"></i>
                                                            Student Type
                                                        </div>
                                                    </th>
                                                    <th class="px-3 py-4 text-left text-sm font-bold text-purple-800 uppercase tracking-wider border-l border-purple-300">
                                                        <div class="flex items-center">
                                                            <i class="fas fa-building mr-2 text-purple-600"></i>
                                                            Their Section
                                                        </div>
                                                    </th>
                                                    <th class="px-3 py-4 text-left text-sm font-bold text-purple-800 uppercase tracking-wider border-l border-purple-300">
                                                        <div class="flex items-center">
                                                            <i class="fas fa-check-circle mr-2 text-purple-600"></i>
                                                            Status
                                                        </div>
                                                    </th>
                                                    @if($session->session_type === 'lab')
                                                        <th class="px-3 py-4 text-left text-sm font-bold text-purple-800 uppercase tracking-wider border-l border-purple-300">
                                                            <div class="flex items-center">
                                                                <i class="fas fa-desktop mr-2 text-purple-600"></i>
                                                                PC Number
                                                            </div>
                                                        </th>
                                                    @elseif($session->session_type === 'online')
                                                        <th class="px-3 py-4 text-left text-sm font-bold text-purple-800 uppercase tracking-wider border-l border-purple-300">
                                                            <div class="flex items-center">
                                                                <i class="fas fa-mobile-alt mr-2 text-purple-600"></i>
                                                                Device Type
                                                            </div>
                                                        </th>
                                                    @elseif($session->session_type === 'lecture')
                                                        <th class="px-3 py-4 text-left text-sm font-bold text-purple-800 uppercase tracking-wider border-l border-purple-300">
                                                            <div class="flex items-center">
                                                                <i class="fas fa-image mr-2 text-purple-600"></i>
                                                                Attached Image
                                                            </div>
                                                        </th>
                                                    @endif
                                                    <th class="px-3 py-4 text-left text-sm font-bold text-purple-800 uppercase tracking-wider border-l border-purple-300">
                                                        <div class="flex items-center">
                                                            <i class="fas fa-clock mr-2 text-purple-600"></i>
                                                            Check-in Time
                                                        </div>
                                                    </th>
                                                    <th class="px-4 py-4 text-center text-sm font-bold text-purple-800 uppercase tracking-wider border-l border-purple-300">
                                                        <div class="flex items-center justify-center">
                                                            <i class="fas fa-cogs mr-2 text-purple-600"></i>
                                                            Actions
                                                        </div>
                                                    </th>
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
                                                            {{ $attendance->check_in_time->setTimezone('Asia/Manila')->format('M d, Y g:i A') }}
                                                        </td>
                                                        <td class="px-2 py-4 text-sm text-gray-900">
                                                            <div class="flex space-x-2">
                                                                <button type="button" 
                                                                        onclick="editAttendance('{{ $attendance->id }}', '{{ $attendance->status }}', '{{ $attendance->pc_number ?? '' }}', '{{ $attendance->device_type ?? '' }}')" 
                                                                        class="action-button bg-blue-500 text-white"
                                                                        title="Edit Attendance">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button type="button" 
                                                                        onclick="deleteAttendance('{{ $attendance->id }}', '{{ addslashes($attendance->user->name) }}')" 
                                                                        class="action-button bg-red-500 text-white"
                                                                        title="Delete Attendance">
                                                                    <i class="fas fa-trash"></i>
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
                                                                <div class="flex space-x-2">
                                                                    <button type="button" 
                                                                            onclick="editAttendance('{{ $attendance->id }}', '{{ $attendance->status }}', '{{ $attendance->pc_number ?? '' }}', '{{ $attendance->device_type ?? '' }}')" 
                                                                            class="action-button bg-blue-500 text-white text-xs px-2 py-1"
                                                                            title="Edit Attendance">
                                                                        <i class="fas fa-edit"></i>
                                                                    </button>
                                                                    <button type="button" 
                                                                            onclick="deleteAttendance('{{ $attendance->id }}', '{{ addslashes($attendance->user->name) }}')" 
                                                                            class="action-button bg-red-500 text-white text-xs px-2 py-1"
                                                                            title="Delete Attendance">
                                                                        <i class="fas fa-trash"></i>
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
                        <div class="border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                            <div class="accordion-header bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 cursor-pointer transition-all duration-300">
                                <div class="px-6 py-5">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <i class="fas fa-chevron-down accordion-icon mr-4 text-white transition-transform duration-200 text-lg"></i>
                                            <h3 class="text-xl font-semibold text-white">
                                                <i class="fas fa-users text-white mr-3 text-lg"></i>
                                                Regular Students (Section {{ $session->section }})
                                            </h3>
                                        </div>
                                        <div class="flex items-center space-x-6">
                                            <div class="text-center">
                                                <div class="text-white text-sm font-medium">Total Students</div>
                                                <div class="text-white text-2xl font-bold">{{ $regularAttendances->count() }}</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-white text-sm font-medium">Present</div>
                                                <div class="text-white text-2xl font-bold">{{ $regularAttendances->where('status', 'present')->count() }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-content bg-white">
                                <div class="p-4">
                                    <div class="w-full">
                                        <table class="w-full divide-y divide-gray-200 attendance-table">
                                            <thead class="bg-gradient-to-r from-purple-100 to-purple-200 border-b-2 border-purple-300">
                                                <tr>
                                                    <th class="px-4 py-4 text-left text-sm font-bold text-purple-800 uppercase tracking-wider">
                                                        <div class="flex items-center">
                                                            <i class="fas fa-user mr-2 text-purple-600"></i>
                                                            Student
                                                        </div>
                                                    </th>
                                                    <th class="px-3 py-4 text-left text-sm font-bold text-purple-800 uppercase tracking-wider border-l border-purple-300">
                                                        <div class="flex items-center">
                                                            <i class="fas fa-id-card mr-2 text-purple-600"></i>
                                                            Student ID
                                                        </div>
                                                    </th>
                                                    <th class="px-3 py-4 text-left text-sm font-bold text-purple-800 uppercase tracking-wider border-l border-purple-300">
                                                        <div class="flex items-center">
                                                            <i class="fas fa-graduation-cap mr-2 text-purple-600"></i>
                                                            Year Level
                                                        </div>
                                                    </th>
                                                    <th class="px-3 py-4 text-left text-sm font-bold text-purple-800 uppercase tracking-wider border-l border-purple-300">
                                                        <div class="flex items-center">
                                                            <i class="fas fa-tag mr-2 text-purple-600"></i>
                                                            Student Type
                                                        </div>
                                                    </th>
                                                    <th class="px-3 py-4 text-left text-sm font-bold text-purple-800 uppercase tracking-wider border-l border-purple-300">
                                                        <div class="flex items-center">
                                                            <i class="fas fa-building mr-2 text-purple-600"></i>
                                                            Their Section
                                                        </div>
                                                    </th>
                                                    <th class="px-3 py-4 text-left text-sm font-bold text-purple-800 uppercase tracking-wider border-l border-purple-300">
                                                        <div class="flex items-center">
                                                            <i class="fas fa-check-circle mr-2 text-purple-600"></i>
                                                            Status
                                                        </div>
                                                    </th>
                                                    @if($session->session_type === 'lab')
                                                        <th class="px-3 py-4 text-left text-sm font-bold text-purple-800 uppercase tracking-wider border-l border-purple-300">
                                                            <div class="flex items-center">
                                                                <i class="fas fa-desktop mr-2 text-purple-600"></i>
                                                                PC Number
                                                            </div>
                                                        </th>
                                                    @elseif($session->session_type === 'online')
                                                        <th class="px-3 py-4 text-left text-sm font-bold text-purple-800 uppercase tracking-wider border-l border-purple-300">
                                                            <div class="flex items-center">
                                                                <i class="fas fa-mobile-alt mr-2 text-purple-600"></i>
                                                                Device Type
                                                            </div>
                                                        </th>
                                                    @elseif($session->session_type === 'lecture')
                                                        <th class="px-3 py-4 text-left text-sm font-bold text-purple-800 uppercase tracking-wider border-l border-purple-300">
                                                            <div class="flex items-center">
                                                                <i class="fas fa-image mr-2 text-purple-600"></i>
                                                                Attached Image
                                                            </div>
                                                        </th>
                                                    @endif
                                                    <th class="px-3 py-4 text-left text-sm font-bold text-purple-800 uppercase tracking-wider border-l border-purple-300">
                                                        <div class="flex items-center">
                                                            <i class="fas fa-clock mr-2 text-purple-600"></i>
                                                            Check-in Time
                                                        </div>
                                                    </th>
                                                    <th class="px-4 py-4 text-center text-sm font-bold text-purple-800 uppercase tracking-wider border-l border-purple-300">
                                                        <div class="flex items-center justify-center">
                                                            <i class="fas fa-cogs mr-2 text-purple-600"></i>
                                                            Actions
                                                        </div>
                                                    </th>
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
                                                            {{ $attendance->check_in_time->setTimezone('Asia/Manila')->format('M d, Y g:i A') }}
                                                        </td>
                                                        <td class="px-2 py-4 text-sm text-gray-900">
                                                            <div class="flex space-x-2">
                                                                <button type="button" 
                                                                        onclick="editAttendance('{{ $attendance->id }}', '{{ $attendance->status }}', '{{ $attendance->pc_number ?? '' }}', '{{ $attendance->device_type ?? '' }}')" 
                                                                        class="action-button bg-blue-500 text-white"
                                                                        title="Edit Attendance">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button type="button" 
                                                                        onclick="deleteAttendance('{{ $attendance->id }}', '{{ addslashes($attendance->user->name) }}')" 
                                                                        class="action-button bg-red-500 text-white"
                                                                        title="Delete Attendance">
                                                                    <i class="fas fa-trash"></i>
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
                                                                <div class="flex space-x-2">
                                                                    <button type="button" 
                                                                            onclick="editAttendance('{{ $attendance->id }}', '{{ $attendance->status }}', '{{ $attendance->pc_number ?? '' }}', '{{ $attendance->device_type ?? '' }}')" 
                                                                            class="action-button bg-blue-500 text-white text-xs px-2 py-1"
                                                                            title="Edit Attendance">
                                                                        <i class="fas fa-edit"></i>
                                                                    </button>
                                                                    <button type="button" 
                                                                            onclick="deleteAttendance('{{ $attendance->id }}', '{{ addslashes($attendance->user->name) }}')" 
                                                                            class="action-button bg-red-500 text-white text-xs px-2 py-1"
                                                                            title="Delete Attendance">
                                                                        <i class="fas fa-trash"></i>
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
                        <div class="border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                            <div class="accordion-header bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 cursor-pointer transition-all duration-300">
                                <div class="px-6 py-5">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <i class="fas fa-chevron-down accordion-icon mr-4 text-white transition-transform duration-200 text-lg"></i>
                                            <h3 class="text-xl font-semibold text-white">
                                                <i class="fas fa-user-plus text-white mr-3 text-lg"></i>
                                                Irregular Students (Other Sections)
                                            </h3>
                                        </div>
                                        <div class="flex items-center space-x-6">
                                            <div class="text-center">
                                                <div class="text-white text-sm font-medium">Total Students</div>
                                                <div class="text-white text-2xl font-bold">{{ $irregularAttendances->count() }}</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-white text-sm font-medium">Present</div>
                                                <div class="text-white text-2xl font-bold">{{ $irregularAttendances->where('status', 'present')->count() }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-content bg-white">
                                <div class="p-4">
                                    <div class="w-full">
                                        <table class="w-full divide-y divide-gray-200 attendance-table">
                                            <thead class="bg-gradient-to-r from-purple-100 to-purple-200 border-b-2 border-purple-300">
                                                <tr>
                                                    <th class="px-4 py-4 text-left text-sm font-bold text-purple-800 uppercase tracking-wider">
                                                        <div class="flex items-center">
                                                            <i class="fas fa-user mr-2 text-purple-600"></i>
                                                            Student
                                                        </div>
                                                    </th>
                                                    <th class="px-3 py-4 text-left text-sm font-bold text-purple-800 uppercase tracking-wider border-l border-purple-300">
                                                        <div class="flex items-center">
                                                            <i class="fas fa-id-card mr-2 text-purple-600"></i>
                                                            Student ID
                                                        </div>
                                                    </th>
                                                    <th class="px-3 py-4 text-left text-sm font-bold text-purple-800 uppercase tracking-wider border-l border-purple-300">
                                                        <div class="flex items-center">
                                                            <i class="fas fa-graduation-cap mr-2 text-purple-600"></i>
                                                            Year Level
                                                        </div>
                                                    </th>
                                                    <th class="px-3 py-4 text-left text-sm font-bold text-purple-800 uppercase tracking-wider border-l border-purple-300">
                                                        <div class="flex items-center">
                                                            <i class="fas fa-tag mr-2 text-purple-600"></i>
                                                            Student Type
                                                        </div>
                                                    </th>
                                                    <th class="px-3 py-4 text-left text-sm font-bold text-purple-800 uppercase tracking-wider border-l border-purple-300">
                                                        <div class="flex items-center">
                                                            <i class="fas fa-building mr-2 text-purple-600"></i>
                                                            Their Section
                                                        </div>
                                                    </th>
                                                    <th class="px-3 py-4 text-left text-sm font-bold text-purple-800 uppercase tracking-wider border-l border-purple-300">
                                                        <div class="flex items-center">
                                                            <i class="fas fa-check-circle mr-2 text-purple-600"></i>
                                                            Status
                                                        </div>
                                                    </th>
                                                    @if($session->session_type === 'lab')
                                                        <th class="px-3 py-4 text-left text-sm font-bold text-purple-800 uppercase tracking-wider border-l border-purple-300">
                                                            <div class="flex items-center">
                                                                <i class="fas fa-desktop mr-2 text-purple-600"></i>
                                                                PC Number
                                                            </div>
                                                        </th>
                                                    @elseif($session->session_type === 'online')
                                                        <th class="px-3 py-4 text-left text-sm font-bold text-purple-800 uppercase tracking-wider border-l border-purple-300">
                                                            <div class="flex items-center">
                                                                <i class="fas fa-mobile-alt mr-2 text-purple-600"></i>
                                                                Device Type
                                                            </div>
                                                        </th>
                                                    @elseif($session->session_type === 'lecture')
                                                        <th class="px-3 py-4 text-left text-sm font-bold text-purple-800 uppercase tracking-wider border-l border-purple-300">
                                                            <div class="flex items-center">
                                                                <i class="fas fa-image mr-2 text-purple-600"></i>
                                                                Attached Image
                                                            </div>
                                                        </th>
                                                    @endif
                                                    <th class="px-3 py-4 text-left text-sm font-bold text-purple-800 uppercase tracking-wider border-l border-purple-300">
                                                        <div class="flex items-center">
                                                            <i class="fas fa-clock mr-2 text-purple-600"></i>
                                                            Check-in Time
                                                        </div>
                                                    </th>
                                                    <th class="px-4 py-4 text-center text-sm font-bold text-purple-800 uppercase tracking-wider border-l border-purple-300">
                                                        <div class="flex items-center justify-center">
                                                            <i class="fas fa-cogs mr-2 text-purple-600"></i>
                                                            Actions
                                                        </div>
                                                    </th>
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
                                                            {{ $attendance->check_in_time->setTimezone('Asia/Manila')->format('M d, Y g:i A') }}
                                                        </td>
                                                        <td class="px-2 py-4 text-sm text-gray-900">
                                                            <div class="flex space-x-2">
                                                                <button type="button" 
                                                                        onclick="editAttendance('{{ $attendance->id }}', '{{ $attendance->status }}', '{{ $attendance->pc_number ?? '' }}', '{{ $attendance->device_type ?? '' }}')" 
                                                                        class="action-button bg-blue-500 text-white"
                                                                        title="Edit Attendance">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button type="button" 
                                                                        onclick="deleteAttendance('{{ $attendance->id }}', '{{ addslashes($attendance->user->name) }}')" 
                                                                        class="action-button bg-red-500 text-white"
                                                                        title="Delete Attendance">
                                                                    <i class="fas fa-trash"></i>
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
                                                                <div class="flex space-x-2">
                                                                    <button type="button" 
                                                                            onclick="editAttendance('{{ $attendance->id }}', '{{ $attendance->status }}', '{{ $attendance->pc_number ?? '' }}', '{{ $attendance->device_type ?? '' }}')" 
                                                                            class="action-button bg-blue-500 text-white text-xs px-2 py-1"
                                                                            title="Edit Attendance">
                                                                        <i class="fas fa-edit"></i>
                                                                    </button>
                                                                    <button type="button" 
                                                                            onclick="deleteAttendance('{{ $attendance->id }}', '{{ addslashes($attendance->user->name) }}')" 
                                                                            class="action-button bg-red-500 text-white text-xs px-2 py-1"
                                                                            title="Delete Attendance">
                                                                        <i class="fas fa-trash"></i>
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
                        
                        // Group students by email to identify potential duplicates
                        $emailGroups = $studentsNotMarked->groupBy('email');
                        $potentialDuplicates = $emailGroups->filter(function($group) {
                            return $group->count() > 1;
                        });
                    @endphp
                    
                    @if($studentsNotMarked->count() > 0)
                        <!-- Summary Information -->
                        <div class="mb-4 p-3 bg-orange-50 border border-orange-200 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle text-orange-500 mr-2"></i>
                                    <div class="text-sm text-orange-700">
                                        <strong>Summary:</strong> {{ $studentsNotMarked->count() }} students without attendance records
                                        @if($potentialDuplicates->count() > 0)
                                            • <strong>{{ $potentialDuplicates->count() }} potential duplicate accounts</strong> detected
                                        @endif
                                    </div>
                                </div>
                                @if($potentialDuplicates->count() > 0)
                                    <div class="text-xs text-orange-600 bg-orange-100 px-2 py-1 rounded">
                                        Check accounts with same email addresses
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($studentsNotMarked as $student)
                                @php
                                    $isDuplicate = $emailGroups->get($student->email)->count() > 1;
                                @endphp
                                <div class="students-without-attendance-card {{ $isDuplicate ? 'ring-2 ring-orange-300 bg-orange-50' : '' }}">
                                    <!-- Duplicate Indicator -->
                                    @if($isDuplicate)
                                        <div class="absolute top-2 left-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                <i class="fas fa-copy mr-1"></i>Duplicate
                                            </span>
                                        </div>
                                    @endif
                                    
                                    <div class="flex items-center mb-3">
                                        <div class="flex-shrink-0 h-12 w-12 mr-4">
                                            @if($student->profile_picture)
                                                <img class="h-12 w-12 rounded-full object-cover" 
                                                     src="{{ asset('storage/' . $student->profile_picture) }}" 
                                                     alt="{{ $student->name }}'s profile picture">
                                            @else
                                                <div class="h-12 w-12 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-800 font-semibold text-lg">
                                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="student-info">
                                            <div class="text-base font-semibold text-gray-900 mb-1">{{ $student->name }}</div>
                                            <div class="text-sm text-gray-600 mb-1">{{ $student->student_id ?? 'Student ID: N/A' }}</div>
                                            <div class="text-sm text-gray-500">{{ $student->email }}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="action-buttons">
                                        <div class="flex space-x-2">
                                            <!-- Mark Present Button -->
                                            <button type="button" 
                                                    onclick="openPresentModal('{{ $student->id }}', '{{ $student->name }}')"
                                                    class="flex-1 text-xs bg-green-500 text-white px-3 py-2 rounded hover:bg-green-600 transition-colors font-medium">
                                                <i class="fas fa-check mr-1"></i>
                                                Present
                                            </button>
                                            
                                            <!-- Mark Absent Button -->
                                            <form action="{{ route('teacher.sessions.mark-absent', $session) }}" method="POST" class="flex-1">
                                                @csrf
                                                <input type="hidden" name="student_id" value="{{ $student->id }}">
                                                <button type="submit" 
                                                        class="w-full text-xs bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600 transition-colors font-medium"
                                                        onclick="return confirm('Mark {{ $student->name }} as absent?')">
                                                    <i class="fas fa-times mr-1"></i>
                                                    Absent
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-blue-500 mr-3 text-lg"></i>
                                <div class="text-sm text-blue-700">
                                    <strong>Quick Actions for Students Without Attendance:</strong><br><br>
                                    • <strong>Mark Present:</strong> Use the green "Present" button to mark attendance. For lab sessions, enter PC number (1-40). For online sessions, select device type. For lecture sessions, upload an image.<br>
                                    • <strong>Mark Absent:</strong> Use the red "Absent" button to mark students as absent.<br>
                                    • <strong>Late Status:</strong> Automatically calculated by the system based on check-in time and grace period.<br>
                                    • <strong>Duplicate Accounts:</strong> Students with orange "Duplicate" badges may have multiple accounts. Consider cleaning up duplicate accounts if needed.
                                </div>
                            </div>
                        </div>
                        
                        @if(config('app.debug'))
                        <!-- Debug Information -->
                        <div class="mt-4 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-bug text-gray-500 mr-2"></i>
                                <div class="text-sm text-gray-700">
                                    <strong>Debug Info:</strong><br>
                                    Total Students: {{ $totalTargetStudents }}<br>
                                    Present: {{ $presentTargetStudents }}<br>
                                    Late: {{ $lateTargetStudents }}<br>
                                    Absent: {{ $absentTargetStudents }}<br>
                                    Not Marked: {{ $notMarkedYet }}<br>
                                    Session Start: {{ $session->scheduled_start_time ? $session->scheduled_start_time->setTimezone('Asia/Manila')->format('g:i A') : 'N/A' }}<br>
                                    Grace Period: {{ $session->grace_period_minutes ?? 15 }} minutes<br>
                                    Current Time: {{ now()->format('H:i') }}
                                </div>
                            </div>
                        </div>
                        @endif
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

<!-- Mark Present Modal -->
<div id="presentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Mark Student Present</h3>
            <form action="{{ route('teacher.sessions.mark-present', $session) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="student_id" id="presentStudentId">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Student</label>
                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded border" id="presentStudentName"></p>
                </div>

                @if($session->session_type === 'lab')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">PC Number <span class="text-red-500">*</span></label>
                    <input type="number" name="pc_number" id="presentPcNumber" min="1" max="40" 
                           placeholder="Enter PC number (1-40)"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                    <p class="text-xs text-gray-500 mt-1">Please enter the PC number the student is using</p>
                </div>
                @elseif($session->session_type === 'online')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Device Type <span class="text-red-500">*</span></label>
                    <select name="device_type" id="presentDeviceType" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="">Select device type</option>
                        <option value="mobile">Mobile</option>
                        <option value="desktop">Desktop</option>
                        <option value="laptop">Laptop</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Please select the device type the student is using</p>
                </div>
                @elseif($session->session_type === 'lecture')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Attached Image <span class="text-red-500">*</span></label>
                    <input type="file" name="attached_image" accept="image/*" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                    <p class="text-xs text-gray-500 mt-1">Please upload an image for lecture attendance</p>
                </div>
                @endif

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closePresentModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700">
                        <i class="fas fa-check mr-2"></i>
                        Mark Present
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

    // Mark Present Modal Functions
    window.openPresentModal = function(studentId, studentName) {
        document.getElementById('presentModal').classList.remove('hidden');
        document.getElementById('presentStudentId').value = studentId;
        document.getElementById('presentStudentName').textContent = studentName;
        document.getElementById('presentPcNumber').focus();
    };

    window.closePresentModal = function() {
        document.getElementById('presentModal').classList.add('hidden');
        document.getElementById('presentPcNumber').value = '';
    };

    // Close present modal when clicking outside
    const presentModal = document.getElementById('presentModal');
    if (presentModal) {
        presentModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closePresentModal();
            }
        });
    }
});
</script> 