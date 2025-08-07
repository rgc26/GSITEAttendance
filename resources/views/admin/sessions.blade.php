@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">
                        <i class="fas fa-clock mr-2"></i>
                        Scheduled Sessions Management
                    </h2>
                </div>

                <!-- Manual Activation Buttons -->
                <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                        <h3 class="text-lg font-medium text-blue-800 mb-2">Activate Sessions</h3>
                        <p class="text-sm text-blue-700 mb-3">Manually activate scheduled sessions that should have started</p>
                        <button id="activateBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-play mr-2"></i>
                            Activate Sessions
                        </button>
                        <div id="activateResult" class="mt-3 text-sm"></div>
                    </div>

                    <div class="bg-red-50 border border-red-200 rounded-md p-4">
                        <h3 class="text-lg font-medium text-red-800 mb-2">Deactivate Sessions</h3>
                        <p class="text-sm text-red-700 mb-3">Manually deactivate sessions that should have ended</p>
                        <button id="deactivateBtn" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-stop mr-2"></i>
                            Deactivate Sessions
                        </button>
                        <div id="deactivateResult" class="mt-3 text-sm"></div>
                    </div>
                </div>

                <!-- Scheduled Sessions List -->
                <div class="bg-gray-50 border border-gray-200 rounded-md p-4">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Scheduled Sessions</h3>
                    <div id="sessionsList" class="text-sm text-gray-600">
                        Loading sessions...
                    </div>
                </div>

                <!-- Setup Instructions -->
                <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Setup Instructions</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <ol class="list-decimal pl-5 space-y-1">
                                    <li>Create a <code>.env</code> file in your project root with proper database credentials</li>
                                    <li>Run <code>php artisan key:generate</code> to generate an application key</li>
                                    <li>Set up a cron job to run <code>php artisan schedule:run</code> every minute</li>
                                    <li>Or manually activate sessions using the buttons above</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const activateBtn = document.getElementById('activateBtn');
    const deactivateBtn = document.getElementById('deactivateBtn');
    const activateResult = document.getElementById('activateResult');
    const deactivateResult = document.getElementById('deactivateResult');
    const sessionsList = document.getElementById('sessionsList');

    // Load sessions on page load
    loadSessions();

    activateBtn.addEventListener('click', function() {
        activateBtn.disabled = true;
        activateBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Activating...';
        activateResult.innerHTML = '';

        fetch('/admin/activate-sessions')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    activateResult.innerHTML = `<div class="text-green-600">${data.message}</div>`;
                    if (data.results && data.results.length > 0) {
                        activateResult.innerHTML += '<ul class="mt-2 text-xs text-gray-600">' + 
                            data.results.map(result => `<li>• ${result}</li>`).join('') + '</ul>';
                    }
                } else {
                    activateResult.innerHTML = `<div class="text-red-600">Error: ${data.error}</div>`;
                }
                loadSessions(); // Refresh the sessions list
            })
            .catch(error => {
                activateResult.innerHTML = `<div class="text-red-600">Error: ${error.message}</div>`;
            })
            .finally(() => {
                activateBtn.disabled = false;
                activateBtn.innerHTML = '<i class="fas fa-play mr-2"></i>Activate Sessions';
            });
    });

    deactivateBtn.addEventListener('click', function() {
        deactivateBtn.disabled = true;
        deactivateBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Deactivating...';
        deactivateResult.innerHTML = '';

        fetch('/admin/deactivate-sessions')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    deactivateResult.innerHTML = `<div class="text-green-600">${data.message}</div>`;
                    if (data.results && data.results.length > 0) {
                        deactivateResult.innerHTML += '<ul class="mt-2 text-xs text-gray-600">' + 
                            data.results.map(result => `<li>• ${result}</li>`).join('') + '</ul>';
                    }
                } else {
                    deactivateResult.innerHTML = `<div class="text-red-600">Error: ${data.error}</div>`;
                }
                loadSessions(); // Refresh the sessions list
            })
            .catch(error => {
                deactivateResult.innerHTML = `<div class="text-red-600">Error: ${error.message}</div>`;
            })
            .finally(() => {
                deactivateBtn.disabled = false;
                deactivateBtn.innerHTML = '<i class="fas fa-stop mr-2"></i>Deactivate Sessions';
            });
    });

    function loadSessions() {
        fetch('/admin/sessions-data')
            .then(response => response.json())
            .then(data => {
                if (data.sessions && data.sessions.length > 0) {
                    const sessionsHtml = data.sessions.map(session => `
                        <div class="border-b border-gray-200 py-2">
                            <div class="flex justify-between items-start">
                                <div>
                                    <strong>${session.name || 'Unnamed Session'}</strong>
                                    <div class="text-xs text-gray-500">
                                        Subject: ${session.subject_name} | Section: ${session.section}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Scheduled: ${session.scheduled_start_time} - ${session.scheduled_end_time}
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="px-2 py-1 text-xs rounded-full ${session.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                                        ${session.is_active ? 'Active' : 'Inactive'}
                                    </span>
                                </div>
                            </div>
                        </div>
                    `).join('');
                    sessionsList.innerHTML = sessionsHtml;
                } else {
                    sessionsList.innerHTML = '<div class="text-gray-500">No scheduled sessions found.</div>';
                }
            })
            .catch(error => {
                sessionsList.innerHTML = `<div class="text-red-600">Error loading sessions: ${error.message}</div>`;
            });
    }
});
</script>
@endsection
