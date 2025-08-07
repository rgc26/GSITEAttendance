<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- Security Headers -->
    
    <!-- <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; font-src 'self' https://cdnjs.cloudflare.com; img-src 'self' data: https:; connect-src 'self'; frame-ancestors 'none';"> -->
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-Frame-Options" content="DENY">
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">
    <meta http-equiv="Referrer-Policy" content="strict-origin-when-cross-origin">
    <meta name="robots" content="noindex, nofollow">
    
    <title><?php echo e(config('app.name', 'SmartTrack')); ?></title>
    
    <!-- Resource Loading -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        /* Fallback styles in case Tailwind doesn't load */
        .fallback-container { max-width: 1200px; margin: 0 auto; padding: 0 1rem; }
        .fallback-card { background: white; border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1.5rem; margin-bottom: 1rem; }
        .fallback-button { display: inline-flex; align-items: center; padding: 0.5rem 1rem; border-radius: 0.375rem; text-decoration: none; margin: 0.25rem; }
        .fallback-button-primary { background: #3b82f6; color: white; }
        .fallback-button-secondary { background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; }
        .fallback-button-success { background: #10b981; color: white; }
    </style>
</head>
<body class="bg-gray-100">
    <?php if(auth()->guard()->check()): ?>
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="<?php echo e(Auth::check() ? (Route::has('student.dashboard') ? route('student.dashboard') : '/') : (Route::has('login') ? route('login') : '/')); ?>" class="text-xl font-bold text-gray-800">
                                <?php echo e(config('app.name', 'SmartTrack')); ?>

                            </a>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700 font-medium"><?php echo e(Auth::user() ? Auth::user()->name : 'Guest'); ?></span>
                        <form method="POST" action="<?php echo e(route('logout')); ?>" class="flex">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-md transition-colors duration-200">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    <?php endif; ?>

    <main class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
        // Secure JavaScript with strict mode and error handling
        'use strict';
        
        // Prevent global scope pollution
        (function() {
            // Check if Tailwind CSS is loaded with error handling
            document.addEventListener('DOMContentLoaded', function() {
                try {
                    const testElement = document.createElement('div');
                    testElement.className = 'hidden';
                    document.body.appendChild(testElement);
                    
                    const isTailwindLoaded = window.getComputedStyle(testElement).display === 'none';
                    document.body.removeChild(testElement);
                    
                    if (!isTailwindLoaded) {
                        console.warn('Tailwind CSS not loaded, applying fallback styles');
                        applyFallbackStyles();
                    }
                } catch (error) {
                    console.error('Error checking Tailwind CSS:', error);
                    applyFallbackStyles();
                }
            });

            function applyFallbackStyles() {
                try {
                    const selectors = [
                        { selector: '.max-w-7xl', class: 'fallback-container' },
                        { selector: '.bg-white', class: 'fallback-card' },
                        { selector: '.bg-indigo-600', class: 'fallback-button fallback-button-primary' },
                        { selector: '.bg-gray-300', class: 'fallback-button fallback-button-secondary' },
                        { selector: '.bg-green-600', class: 'fallback-button fallback-button-success' }
                    ];
                    
                    selectors.forEach(({ selector, class: className }) => {
                        document.querySelectorAll(selector).forEach(el => {
                            el.classList.add(...className.split(' '));
                        });
                    });
                } catch (error) {
                    console.error('Error applying fallback styles:', error);
                }
            }

            // Secure confirmation functions with input validation
            window.confirmDelete = function(event) {
                if (!event || !event.preventDefault) {
                    console.error('Invalid event object');
                    return false;
                }
                
                event.preventDefault();
                
                try {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Are you sure?',
                            text: "You won't be able to revert this!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Yes, delete it!',
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result && result.isConfirmed) {
                                const form = event.target.closest('form');
                                if (form && form.tagName === 'FORM') {
                                    form.submit();
                                }
                            }
                        });
                    } else {
                        // Fallback to native confirm
                        if (confirm('Are you sure you want to delete this item?')) {
                            const form = event.target.closest('form');
                            if (form && form.tagName === 'FORM') {
                                form.submit();
                            }
                        }
                    }
                } catch (error) {
                    console.error('Error in confirmDelete:', error);
                    // Fallback to native confirm
                    if (confirm('Are you sure you want to delete this item?')) {
                        const form = event.target.closest('form');
                        if (form && form.tagName === 'FORM') {
                            form.submit();
                        }
                    }
                }
                
                return false;
            };

            window.confirmArchive = function(event) {
                if (!event || !event.preventDefault) {
                    console.error('Invalid event object');
                    return false;
                }
                
                event.preventDefault();
                
                try {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Archive Subject?',
                            text: "This subject will be moved to archived subjects. You can unarchive it later.",
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#f59e0b',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'Yes, archive it!',
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result && result.isConfirmed) {
                                const form = event.target.closest('form');
                                if (form && form.tagName === 'FORM') {
                                    form.submit();
                                }
                            }
                        });
                    } else {
                        // Fallback to native confirm
                        if (confirm('Are you sure you want to archive this subject?')) {
                            const form = event.target.closest('form');
                            if (form && form.tagName === 'FORM') {
                                form.submit();
                            }
                        }
                    }
                } catch (error) {
                    console.error('Error in confirmArchive:', error);
                    // Fallback to native confirm
                    if (confirm('Are you sure you want to archive this subject?')) {
                        const form = event.target.closest('form');
                        if (form && form.tagName === 'FORM') {
                            form.submit();
                        }
                    }
                }
                
                return false;
            };
        })();
    </script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html> <?php /**PATH C:\xampp\htdocs\webSys\resources\views/layouts/app.blade.php ENDPATH**/ ?>