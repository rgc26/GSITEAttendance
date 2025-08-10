<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Student Registration
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Create your student account
            </p>
        </div>
        <form class="mt-8 space-y-6" action="<?php echo e(route('register.student')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            
            <!-- Profile Picture Upload -->
            <div class="text-center">
                <div class="mb-4">
                    <label for="profile_picture" class="block text-sm font-medium text-gray-700 mb-2">Profile Picture (Optional)</label>
                    <div class="flex justify-center">
                        <div class="relative">
                            <img id="preview" src="https://via.placeholder.com/150x150?text=Upload+Photo" alt="Profile Preview" class="w-32 h-32 rounded-full border-4 border-gray-200 object-cover">
                            <label for="profile_picture" class="absolute bottom-0 right-0 bg-blue-500 text-white rounded-full p-2 cursor-pointer hover:bg-blue-600">
                                <i class="fas fa-camera text-sm"></i>
                            </label>
                        </div>
                    </div>
                    <input type="file" id="profile_picture" name="profile_picture" accept="image/*" class="hidden" onchange="previewImage(this)">
                    <p class="text-xs text-gray-500 mt-2">Click the camera icon to upload a profile picture (JPEG, PNG, JPG, GIF up to 2MB)</p>
                </div>
            </div>

            <div class="rounded-md shadow-sm -space-y-px">
                <div class="grid grid-cols-3 gap-0">
                    <div>
                        <label for="last_name" class="sr-only">Last Name</label>
                        <input id="last_name" name="last_name" type="text" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm border-r-0" placeholder="Last Name" value="<?php echo e(old('last_name')); ?>">
                    </div>
                    <div>
                        <label for="first_name" class="sr-only">First Name</label>
                        <input id="first_name" name="first_name" type="text" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm border-r-0" placeholder="First Name" value="<?php echo e(old('first_name')); ?>">
                    </div>
                    <div>
                        <label for="middle_initial" class="sr-only">Middle Initial</label>
                        <input id="middle_initial" name="middle_initial" type="text" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="M.I. (Optional)" value="<?php echo e(old('middle_initial')); ?>">
                    </div>
                </div>
                <div>
                    <label for="email" class="sr-only">Email address</label>
                    <input id="email" name="email" type="email" autocomplete="email" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Email address" value="<?php echo e(old('email')); ?>">
                </div>
                <div>
                    <label for="student_id" class="sr-only">Student ID</label>
                    <input id="student_id" name="student_id" type="text" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Student ID" value="<?php echo e(old('student_id')); ?>">
                </div>
                <div class="grid grid-cols-3 gap-0">
                    <div>
                        <label for="year_level" class="sr-only">Year Level</label>
                        <select id="year_level" name="year_level" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm border-r-0">
                            <option value="">Select Year Level</option>
                            <option value="1st Year" <?php echo e(old('year_level') == '1st Year' ? 'selected' : ''); ?>>1st Year</option>
                            <option value="2nd Year" <?php echo e(old('year_level') == '2nd Year' ? 'selected' : ''); ?>>2nd Year</option>
                            <option value="3rd Year" <?php echo e(old('year_level') == '3rd Year' ? 'selected' : ''); ?>>3rd Year</option>
                            <option value="4th Year" <?php echo e(old('year_level') == '4th Year' ? 'selected' : ''); ?>>4th Year</option>
                        </select>
                    </div>
                    <div>
                        <label for="section" class="sr-only">Section</label>
                        <input id="section" name="section" type="text" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm border-r-0" placeholder="Section (e.g., 301, 302, A1, B2)" value="<?php echo e(old('section')); ?>">
                    </div>
                    <div>
                        <label for="student_type" class="sr-only">Student Type</label>
                        <select id="student_type" name="student_type" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Student Type">
                            <option value="">Select Type</option>
                            <option value="regular" <?php echo e(old('student_type') == 'regular' ? 'selected' : ''); ?>>Regular</option>
                            <option value="irregular" <?php echo e(old('student_type') == 'irregular' ? 'selected' : ''); ?>>Irregular</option>
                            <option value="block" <?php echo e(old('student_type') == 'block' ? 'selected' : ''); ?>>Block</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Password">
                </div>
                <div>
                    <label for="password_confirmation" class="sr-only">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Confirm Password">
                </div>
            </div>

            <div class="flex justify-center">
                <div class="h-captcha" data-sitekey="<?php echo e(config('services.hcaptcha.site_key')); ?>"></div>
            </div>

            <?php if($errors->any()): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Register as Student
                </button>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Already have an account? 
                    <a href="<?php echo e(route('login')); ?>" class="font-medium text-indigo-600 hover:text-indigo-500">
                        Sign in here
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://js.hcaptcha.com/1/api.js" async defer></script>
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<?php $__env->stopPush(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\webSys\resources\views/auth/register-student.blade.php ENDPATH**/ ?>