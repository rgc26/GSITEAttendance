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
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <title><?php echo e(config('app.name', 'SmartTrack')); ?></title>
    
    <!-- Resource Loading -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        /* Mobile-first responsive styles */
        .mobile-menu {
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
        }
        
        .mobile-menu.open {
            transform: translateX(0);
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
        .mobile-nav-toggle {
            display: block;
        }
        
        @media (min-width: 768px) {
            .mobile-nav-toggle {
                display: none;
            }
        }
        
        .desktop-nav {
            display: none;
        }
        
        @media (min-width: 768px) {
            .desktop-nav {
                display: flex;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <?php if(auth()->guard()->check()): ?>
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo and Brand -->
                    <div class="flex items-center">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="<?php echo e(Auth::check() ? (Auth::user()->role === 'teacher' ? route('teacher.dashboard') : (Auth::user()->role === 'student' ? route('student.dashboard') : '/')) : (Route::has('login') ? route('login') : '/')); ?>" class="text-xl font-bold text-gray-800">
                                <?php echo e(config('app.name', 'SmartTrack')); ?>

                            </a>
                        </div>
                    </div>
                    
                    <!-- Desktop Navigation -->
                    <div class="desktop-nav items-center space-x-4">
                        <span class="text-gray-700 font-medium"><?php echo e(Auth::user() ? Auth::user()->name : 'Guest'); ?></span>
                        <form method="POST" action="<?php echo e(route('logout')); ?>" class="inline-flex">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-md transition-colors duration-200">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                    
                    <!-- Mobile menu button -->
                    <div class="mobile-nav-toggle flex items-center">
                        <button id="mobile-menu-toggle" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Mobile menu -->
            <div id="mobile-menu" class="mobile-menu sm:hidden bg-white border-t border-gray-200">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <div class="px-3 py-2 text-sm font-medium text-gray-900 border-b border-gray-200">
                        <?php echo e(Auth::user() ? Auth::user()->name : 'Guest'); ?>

                    </div>
                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="px-3 py-2">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-full text-left inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-md transition-colors duration-200">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </nav>
    <?php endif; ?>

    <main class="py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <?php if(session('success')): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <?php if(isset($errors) && $errors->any()): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </main>

    <script>
        // Mobile menu functionality
            document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuToggle && mobileMenu) {
                mobileMenuToggle.addEventListener('click', function() {
                    mobileMenu.classList.toggle('open');
                });
                
                // Close menu when clicking outside
                document.addEventListener('click', function(event) {
                    if (!mobileMenu.contains(event.target) && !mobileMenuToggle.contains(event.target)) {
                        mobileMenu.classList.remove('open');
                    }
                });
            }
        });
    </script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html> <?php /**PATH C:\xampp\htdocs\webSys\resources\views/layouts/app.blade.php ENDPATH**/ ?>