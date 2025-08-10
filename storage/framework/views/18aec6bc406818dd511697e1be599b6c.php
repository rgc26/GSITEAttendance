<?php $__env->startSection('content'); ?>
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">
                        <i class="fas fa-qrcode mr-2"></i>
                        Start Attendance Session
                    </h2>
                    <a href="<?php echo e(route('teacher.subjects.show', $subject)); ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Subject
                    </a>
                </div>

                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Subject Information</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p><strong>Subject:</strong> <?php echo e($subject->name); ?> (<?php echo e($subject->code); ?>)</p>
                                <p><strong>Teacher:</strong> <?php echo e(Auth::user()->name); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="<?php echo e(route('teacher.sessions.store', $subject)); ?>" method="POST" class="space-y-6">
                    <?php echo csrf_field(); ?>
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Session Name (Optional)</label>
                        <input type="text" name="name" id="name" value="<?php echo e(old('name')); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="e.g., Week 1 - Introduction, Lab Session 1, etc.">
                        <p class="mt-1 text-sm text-gray-500">Give your session a descriptive name for easy identification</p>
                    </div>

                    <div>
                        <label for="section" class="block text-sm font-medium text-gray-700">Target Section *</label>
                        <input type="text" name="section" id="section" value="<?php echo e(old('section')); ?>" required 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                               placeholder="e.g., 301, 302, 303, A1, B2, etc.">
                        <p class="mt-1 text-sm text-gray-500">Enter the target section for this attendance session. The system will find all students registered with this section.</p>
                    </div>

                    <div>
                        <label for="session_type" class="block text-sm font-medium text-gray-700">Session Type *</label>
                        <select name="session_type" id="session_type" required 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Select session type</option>
                            <option value="lecture" <?php echo e(old('session_type') == 'lecture' ? 'selected' : ''); ?>>Lecture</option>
                            <option value="lab" <?php echo e(old('session_type') == 'lab' ? 'selected' : ''); ?>>Lab</option>
                            <option value="online" <?php echo e(old('session_type') == 'online' ? 'selected' : ''); ?>>Online</option>
                        </select>
                        <p class="mt-1 text-sm text-gray-500">Select the type of session. This will determine what students need to provide when marking attendance.</p>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-clock text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Session Timing</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p><strong>Immediate Session:</strong> Leave the scheduled times empty to start the session immediately.</p>
                                    <p><strong>Scheduled Session:</strong> Set both start and end times to schedule the session for later activation.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="scheduled_start_time" class="block text-sm font-medium text-gray-700">Scheduled Start Time (Optional)</label>
                            <input type="datetime-local" name="scheduled_start_time" id="scheduled_start_time" value="<?php echo e(old('scheduled_start_time')); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="mt-1 text-sm text-gray-500">When the session should start (for time-based attendance)</p>
                        </div>

                        <div>
                            <label for="scheduled_end_time" class="block text-sm font-medium text-gray-700">Scheduled End Time (Optional)</label>
                            <input type="datetime-local" name="scheduled_end_time" id="scheduled_end_time" value="<?php echo e(old('scheduled_end_time')); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="mt-1 text-sm text-gray-500">When the session should end</p>
                        </div>
                    </div>

                    <div>
                        <label for="grace_period_minutes" class="block text-sm font-medium text-gray-700">Grace Period (Minutes)</label>
                        <input type="number" name="grace_period_minutes" id="grace_period_minutes" value="<?php echo e(old('grace_period_minutes', 15)); ?>" min="0" max="60" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <p class="mt-1 text-sm text-gray-500">Students can mark attendance as 'late' within this time after the scheduled start</p>
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Session Notes (Optional)</label>
                        <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="e.g., Lab session, Online lecture, etc."><?php echo e(old('notes')); ?></textarea>
                        <p class="mt-1 text-sm text-gray-500">Optional notes about this attendance session</p>
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Important Information</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li>A unique attendance code will be generated automatically</li>
                                        <li>Students will use this code to mark their attendance</li>
                                        <li><strong>Immediate Sessions:</strong> Start right away and are active immediately</li>
                                        <li><strong>Scheduled Sessions:</strong> Will activate automatically at the scheduled time (requires cron job setup)</li>
                                        <li>If scheduled times are set, students will be marked as 'late' after the grace period</li>
                                        <li>You can end the session anytime from the dashboard</li>
                                        <li>Only one active session per subject at a time</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="<?php echo e(route('teacher.subjects.show', $subject)); ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                            <i class="fas fa-play mr-2"></i>
                            Start Session
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\webSys\resources\views/teacher/sessions/create.blade.php ENDPATH**/ ?>