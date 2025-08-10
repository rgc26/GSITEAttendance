

<?php $__env->startSection('content'); ?>
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">
                        <i class="fas fa-archive mr-2"></i>
                        Archived Subjects
                    </h2>
                    <div class="flex space-x-3">
                        <a href="<?php echo e(route('teacher.dashboard')); ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                            <i class="fas fa-home mr-2"></i>
                            Back to Dashboard
                        </a>
                        <a href="<?php echo e(route('teacher.subjects')); ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Active Subjects
                        </a>
                    </div>
                </div>

                <?php if(count($subjects) > 0): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900"><?php echo e($subject->name); ?></h3>
                                    <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded"><?php echo e($subject->code); ?></span>
                                </div>
                                
                                <?php if($subject->description): ?>
                                    <p class="text-gray-600 mb-4 text-sm"><?php echo e($subject->description); ?></p>
                                <?php endif; ?>
                                
                                <div class="space-y-2 mb-4 bg-gray-50 p-3 rounded">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 text-sm">Schedules:</span>
                                        <span class="font-medium text-gray-900"><?php echo e($subject->schedules->count()); ?></span>
                                    </div>
                                    
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 text-sm">Total Sessions:</span>
                                        <span class="font-medium text-gray-900"><?php echo e($subject->attendanceSessions->count()); ?></span>
                                    </div>
                                    
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 text-sm">Active Sessions:</span>
                                        <span class="font-medium text-green-600"><?php echo e($subject->attendanceSessions->where('is_active', true)->count()); ?></span>
                                    </div>
                                </div>
                                
                                <div class="flex space-x-2">
                                    <form method="POST" action="<?php echo e(route('teacher.subjects.unarchive', $subject)); ?>" class="flex-1">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                            <i class="fas fa-undo mr-1"></i>
                                            Unarchive
                                        </button>
                                    </form>
                                    <a href="<?php echo e(route('teacher.subjects.show', $subject)); ?>" class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                        <i class="fas fa-eye mr-1"></i>
                                        View
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <div class="bg-gray-50 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-archive text-3xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Archived Subjects</h3>
                        <p class="text-gray-600 mb-6">You haven't archived any subjects yet.</p>
                        <div class="flex justify-center space-x-3">
                            <a href="<?php echo e(route('teacher.subjects')); ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Back to Active Subjects
                            </a>
                            <a href="<?php echo e(route('teacher.dashboard')); ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                <i class="fas fa-home mr-2"></i>
                                Dashboard
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\webSys\resources\views/teacher/subjects/archived.blade.php ENDPATH**/ ?>