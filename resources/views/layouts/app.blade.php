<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- Security Headers -->
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-Frame-Options" content="DENY">
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">
    <meta http-equiv="Referrer-Policy" content="strict-origin-when-cross-origin">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'SmartTrack') }}</title>
    
    <!-- Resource Loading -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        /* Mobile-first responsive styles */
        .mobile-friendly-nav {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-align: center;
        }
        
        @media (min-width: 640px) {
            .mobile-friendly-nav {
                flex-direction: row;
                align-items: center;
                justify-content: flex-end;
                gap: 1rem;
                text-align: left;
            }
        }
        
        /* Responsive table styles */
        .responsive-table {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .responsive-table table {
            min-width: 600px;
        }
        
        /* Mobile card grid adjustments */
        .mobile-card-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        @media (min-width: 640px) {
            .mobile-card-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (min-width: 1024px) {
            .mobile-card-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        /* Mobile button improvements */
        .mobile-btn {
            width: 100%;
            justify-content: center;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
        }
        
        @media (min-width: 640px) {
            .mobile-btn {
                width: auto;
            }
        }
        
        /* Mobile form improvements */
        .mobile-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        @media (min-width: 640px) {
            .mobile-form {
                flex-direction: row;
                align-items: end;
            }
        }
        
        /* Mobile navigation improvements */
        .nav-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.5rem 0;
            min-height: 4rem;
        }
        
        @media (min-width: 640px) {
            .nav-container {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                padding: 0;
                min-height: 4rem;
            }
        }
        
        /* Logo container improvements */
        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        @media (min-width: 640px) {
            .logo-container {
                justify-content: flex-start;
            }
        }
        
        /* User info and logout alignment */
        .user-nav-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }
        
        @media (min-width: 640px) {
            .user-nav-section {
                flex-direction: row;
                align-items: center;
                gap: 1rem;
            }
        }
        
        /* Logout button specific styling */
        .logout-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 0.75rem 0.5rem 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #6b7280;
            background-color: transparent;
            border: 1px solid transparent;
            border-radius: 0.375rem;
            transition: all 0.2s ease-in-out;
            cursor: pointer;
            text-decoration: none;
            min-height: auto;
            line-height: 1;
            margin: 0;
        }
        
        .logout-btn:hover {
            color: #111827;
            background-color: #f3f4f6;
        }
        
        .logout-btn:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        @media (min-width: 640px) {
            .logout-btn {
                padding: 0.75rem 1rem 0.75rem 1rem;
                min-height: auto;
            }
        }
        
        /* User name alignment improvements */
        .user-name {
            text-align: center;
            white-space: nowrap;
            font-weight: 500;
            color: #374151;
            line-height: 1;
            margin: 0;
            padding: 0;
        }
        
        @media (min-width: 640px) {
            .user-name {
                text-align: left;
            }
        }
        
        /* Ensure perfect inline alignment */
        .user-nav-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            line-height: 1;
        }
        
        @media (min-width: 640px) {
            .user-nav-section {
                flex-direction: row;
                align-items: center;
                gap: 1rem;
                line-height: 1;
            }
        }
        
        /* Remove any extra spacing from form elements */
        .user-nav-section form {
            margin: 0;
            padding: 0;
            line-height: 1;
        }
        
        /* Ensure button and text are perfectly aligned */
        .user-nav-section .user-name,
        .user-nav-section .logout-btn {
            display: inline-flex;
            align-items: center;
            vertical-align: baseline;
            line-height: 1;
        }
        
        /* Remove any default button margins */
        .logout-btn {
            margin: 0;
            padding: 0.5rem 0.75rem;
            border: none;
            background: none;
            font: inherit;
            cursor: pointer;
            outline: inherit;
        }
        
        /* Mobile-specific button adjustments */
        @media (max-width: 639px) {
            .logout-btn {
                padding: 0.375rem 0.5rem;
            }
        }
        
        /* Ensure proper vertical centering */
        .nav-container > * {
            display: flex;
            align-items: center;
        }
        
        /* Logo link styling */
        .logo-link {
            text-decoration: none;
            color: inherit;
            transition: color 0.2s ease-in-out;
        }
        
        .logo-link:hover {
            color: #1f2937;
        }
        
        /* Mobile-friendly spacing */
        .mobile-spacing {
            padding: 0.5rem;
        }
        
        @media (min-width: 640px) {
            .mobile-spacing {
                padding: 1rem;
            }
        }
        
        /* Mobile-friendly text sizes */
        .mobile-text {
            font-size: 0.875rem;
        }
        
        @media (min-width: 640px) {
            .mobile-text {
                font-size: 1rem;
            }
        }
        
        /* Mobile-friendly buttons */
        .mobile-action-btn {
            padding: 0.5rem 0.75rem;
            font-size: 0.75rem;
            min-height: 2.5rem;
        }
        
        @media (min-width: 640px) {
            .mobile-action-btn {
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
                min-height: 2.75rem;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    @auth
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="nav-container">
                    <!-- Logo and Brand -->
                    <div class="logo-container">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ Auth::check() ? (Auth::user()->role === 'teacher' ? route('teacher.dashboard') : (Auth::user()->role === 'student' ? route('student.dashboard') : '/')) : (Route::has('login') ? route('login') : '/') }}" class="logo-link text-lg sm:text-xl font-bold text-gray-800">
                                {{ config('app.name', 'SmartTrack') }}
                            </a>
                        </div>
                    </div>
                    
                    <!-- Navigation - Always Visible -->
                    <div class="user-nav-section">
                        <span class="user-name mobile-text">{{ Auth::user() ? Auth::user()->name : 'Guest' }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline-flex">
                            @csrf
                            <button type="submit" class="logout-btn">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    @endauth

    <main class="py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 mobile-spacing">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 mobile-spacing">
                    {{ session('error') }}
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 mobile-spacing">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>
    
    @stack('scripts')
</body>
</html> 