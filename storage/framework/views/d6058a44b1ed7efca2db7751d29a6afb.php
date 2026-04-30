<?php $__env->startSection('title', 'My Classes - Professor'); ?>
<?php $__env->startSection('header', 'My Classes'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6 space-y-6">
    <!-- Classes Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php $__empty_1 = true; $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-gray-900 border border-gray-800 rounded-lg overflow-hidden hover:border-purple-500 transition">
                <div class="bg-gradient-to-r from-purple-600 to-blue-600 p-4">
                    <h3 class="text-xl font-bold text-white"><?php echo e($classe->code); ?></h3>
                    <p class="text-gray-200 text-sm mt-1"><?php echo e($classe->name); ?></p>
                </div>
                <div class="p-4 space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 text-sm">Students:</span>
                        <span class="text-white font-semibold"><?php echo e($classe->students_count); ?></span>
                    </div>
                    <?php if($classe->description): ?>
                        <p class="text-gray-400 text-sm line-clamp-2"><?php echo e($classe->description); ?></p>
                    <?php endif; ?>
                    <div class="flex items-center justify-between pt-3 border-t border-gray-800">
                        <span class="inline-block px-3 py-1 bg-green-900/30 text-green-300 text-xs rounded-full">
                            <?php echo e($classe->is_active ? 'Active' : 'Inactive'); ?>

                        </span>
                        <a href="<?php echo e(route('professor.class-detail', $classe)); ?>" class="text-purple-500 hover:text-purple-400 font-semibold text-sm">
                            View →
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full bg-gray-900 border border-gray-800 rounded-lg p-12 text-center">
                <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17.5S6.5 28.747 12 28.747s10-4.745 10-10.247S17.5 6.253 12 6.253z"></path>
                </svg>
                <p class="text-gray-400">No classes assigned yet</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\dashboard\Attendance-System\resources\views/professor/classes.blade.php ENDPATH**/ ?>