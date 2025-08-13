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
            gap: 0.5rem;
        }
        
        @media (min-width: 640px) {
            .mobile-friendly-nav {
                flex-direction: row;
                align-items: center;
                gap: 1rem;
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
            gap: 0.5rem;
            padding: 0.5rem 0;
        }
        
        @media (min-width: 640px) {
            .nav-container {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                padding: 0;
            }
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
                    <div class="flex items-center">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ Auth::check() ? (Auth::user()->role === 'teacher' ? route('teacher.dashboard') : (Auth::user()->role === 'student' ? route('student.dashboard') : '/')) : (Route::has('login') ? route('login') : '/') }}" class="text-lg sm:text-xl font-bold text-gray-800">
                                {{ config('app.name', 'SmartTrack') }}
                            </a>
                        </div>
                    </div>
                    
                    <!-- Navigation - Always Visible -->
                    <div class="mobile-friendly-nav">
                        <span class="text-gray-700 font-medium mobile-text text-center">{{ Auth::user() ? Auth::user()->name : 'Guest' }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline-flex">
                            @csrf
                            <button type="submit" class="mobile-btn inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-md transition-colors duration-200">
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