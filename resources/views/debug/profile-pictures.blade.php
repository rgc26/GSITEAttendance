@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Profile Pictures Debug</h2>
                
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Storage Information</h3>
                    <div class="bg-gray-50 p-4 rounded-md">
                        <p><strong>Storage Path:</strong> {{ storage_path('app/public') }}</p>
                        <p><strong>Public Storage Path:</strong> {{ public_path('storage') }}</p>
                        <p><strong>Profile Pictures Path:</strong> {{ storage_path('app/public/profile_pictures') }}</p>
                        <p><strong>Storage Link Exists:</strong> {{ is_link(public_path('storage')) ? 'YES' : 'NO' }}</p>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Users with Profile Pictures</h3>
                    @if($users->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($users as $user)
                                <div class="border rounded-lg p-4">
                                    <h4 class="font-medium text-gray-900">{{ $user->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $user->email }}</p>
                                    <p class="text-sm text-gray-600">Role: {{ ucfirst($user->role) }}</p>
                                    <p class="text-sm text-gray-600">Profile Picture: {{ $user->profile_picture }}</p>
                                    
                                    <div class="mt-3">
                                        <h5 class="text-sm font-medium text-gray-700 mb-2">Image Display Test:</h5>
                                        @if($user->profile_picture)
                                            <div class="space-y-2">
                                                <div>
                                                    <strong>Asset URL:</strong><br>
                                                    <code class="text-xs">{{ asset('storage/' . $user->profile_picture) }}</code>
                                                </div>
                                                <div>
                                                    <strong>Direct URL:</strong><br>
                                                    <code class="text-xs">{{ url('storage/' . $user->profile_picture) }}</code>
                                                </div>
                                                <div>
                                                    <strong>Image Preview:</strong><br>
                                                    <img src="{{ asset('storage/' . $user->profile_picture) }}" 
                                                         alt="Profile Picture" 
                                                         class="w-16 h-16 rounded-full object-cover border-2 border-gray-200"
                                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                                    <div style="display:none;" class="text-red-500 text-xs">Image failed to load</div>
                                                </div>
                                            </div>
                                        @else
                                            <p class="text-gray-500 text-sm">No profile picture</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No users with profile pictures found.</p>
                    @endif
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">File System Test</h3>
                    <div class="bg-gray-50 p-4 rounded-md">
                        @php
                            $profilePath = storage_path('app/public/profile_pictures');
                            $files = is_dir($profilePath) ? scandir($profilePath) : [];
                        @endphp
                        
                        <p><strong>Files in profile_pictures directory:</strong></p>
                        @if(count($files) > 2)
                            <ul class="list-disc list-inside text-sm">
                                @foreach($files as $file)
                                    @if($file !== '.' && $file !== '..')
                                        <li>{{ $file }} ({{ filesize($profilePath . '/' . $file) }} bytes)</li>
                                    @endif
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500">No files found in profile_pictures directory.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
