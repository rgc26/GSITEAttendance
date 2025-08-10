<?php $__env->startSection('content'); ?>
<div class="py-4 sm:py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-4 sm:p-6 bg-white border-b border-gray-200">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6">
                    <i class="fas fa-chalkboard-teacher mr-2"></i>
                    Teacher Dashboard
                </h2>
                
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2 sm:mb-4">Welcome, <?php echo e(Auth::user()->name); ?>!</h3>
                    <p class="text-gray-600">Manage your subjects and attendance sessions.</p>
                </div>

                <!-- Teacher Profile Section -->
                <div class="mb-6 sm:mb-8 bg-gray-50 rounded-lg p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                        <div class="flex flex-col sm:flex-row sm:items-center space-y-3 sm:space-y-0 sm:space-x-4">
                            <?php if(Auth::user()->profile_picture): ?>
                                <img src="<?php echo e(asset('storage/' . Auth::user()->profile_picture)); ?>" 
                                     alt="Profile Picture" class="w-16 h-16 rounded-full object-cover border-2 border-gray-200">
                            <?php else: ?>
                                <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-user text-gray-400 text-xl"></i>
                                </div>
                            <?php endif; ?>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900"><?php echo e(Auth::user()->name); ?></h4>
                                <p class="text-gray-600"><?php echo e(Auth::user()->department); ?></p>
                                <p class="text-sm text-gray-500"><?php echo e(Auth::user()->email); ?></p>
                            </div>
                        </div>
                        <a href="<?php echo e(route('teacher.profile')); ?>" class="mobile-btn inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Profile
                        </a>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mb-6 sm:mb-8">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                        <a href="<?php echo e(route('teacher.subjects.create')); ?>" class="mobile-btn inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            <i class="fas fa-plus mr-2"></i>
                            Create Subject
                        </a>

                        <a href="<?php echo e(route('teacher.subjects.archived')); ?>" class="mobile-btn inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-archive mr-2"></i>
                            Archived Subjects
                        </a>
                    </div>
                </div>

                <!-- Active Sessions -->
                <?php if(count($activeSessions) > 0): ?>
                    <div class="mb-6 sm:mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Active Attendance Sessions</h3>
                        <div class="mobile-card-grid">
                            <?php $__currentLoopData = $activeSessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4 sm:p-6">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 space-y-2 sm:space-y-0">
                                        <h4 class="text-lg font-semibold text-green-900"><?php echo e($session->subject->name); ?></h4>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-green-700">Code:</span>
                                            <span class="font-mono font-bold text-green-900"><?php echo e($session->code); ?></span>
                                        </div>
                                        
                                        <div class="flex justify-between">
                                            <span class="text-green-700">Started:</span>
                                            <span class="text-green-900"><?php echo e($session->start_time->format('M d, Y H:i')); ?></span>
                                        </div>
                                        
                                        <div class="flex justify-between">
                                            <span class="text-green-700">Attendees:</span>
                                            <span class="font-medium text-green-900"><?php echo e($session->attendances->count()); ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4 flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                                        <a href="<?php echo e(route('teacher.sessions.show', $session)); ?>" class="mobile-btn inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200">
                                            <i class="fas fa-eye mr-1"></i>
                                            View
                                        </a>
                                        <form method="POST" action="<?php echo e(route('teacher.sessions.end', $session)); ?>" class="w-full sm:w-auto">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="mobile-btn inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200">
                                                <i class="fas fa-stop mr-1"></i>
                                                End Session
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Subjects List -->
                <div>
                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Your Subjects</h3>
                    </div>
                    
                    <?php if(count($subjects) > 0): ?>
                        <div class="mobile-card-grid">
                            <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="bg-white border border-gray-200 rounded-lg p-4 sm:p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-4 space-y-2 sm:space-y-0">
                                        <div>
                                            <h4 class="text-lg font-semibold text-gray-900"><?php echo e($subject->name); ?></h4>
                                            <p class="text-sm text-gray-500"><?php echo e($subject->code); ?></p>
                                            <?php if($subject->description): ?>
                                                <p class="text-sm text-gray-600 mt-1"><?php echo e(Str::limit($subject->description, 100)); ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                                            <a href="<?php echo e(route('teacher.subjects.show', $subject)); ?>" class="mobile-btn inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                                <i class="fas fa-eye mr-1"></i>
                                                View
                                            </a>
                                            <form method="POST" action="<?php echo e(route('teacher.subjects.archive', $subject)); ?>" class="w-full sm:w-auto" onsubmit="return confirmArchive(event)">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="mobile-btn inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700">
                                                    <i class="fas fa-archive mr-1"></i>
                                                    Archive
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                                        <div class="text-center">
                                            <div class="text-2xl font-bold text-indigo-600"><?php echo e($subject->schedules->count()); ?></div>
                                            <div class="text-gray-500">Schedules</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-2xl font-bold text-green-600"><?php echo e($subject->attendanceSessions->count()); ?></div>
                                            <div class="text-gray-500">Sessions</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-2xl font-bold text-blue-600"><?php echo e($subject->attendanceSessions->where('is_active', true)->count()); ?></div>
                                            <div class="text-gray-500">Active</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-2xl font-bold text-purple-600"><?php echo e($subject->attendanceSessions->sum(function($session) { return $session->attendances->count(); })); ?></div>
                                            <div class="text-gray-500">Attendees</div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8 sm:py-12">
                            <div class="bg-gray-50 rounded-full w-16 h-16 sm:w-20 sm:h-20 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-book text-2xl sm:text-3xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No Subjects Yet</h3>
                            <p class="text-gray-600 mb-6">Create your first subject to get started.</p>
                            <a href="<?php echo e(route('teacher.subjects.create')); ?>" class="mobile-btn inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                <i class="fas fa-plus mr-2"></i>
                                Create Your First Subject
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Archive confirmation function
    function confirmArchive(event) {
        event.preventDefault();
        
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
            if (result.isConfirmed) {
                event.target.closest('form').submit();
            }
        });
        
        return false;
    }
</script>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\webSys\resources\views/teacher/dashboard.blade.php ENDPATH**/ ?>