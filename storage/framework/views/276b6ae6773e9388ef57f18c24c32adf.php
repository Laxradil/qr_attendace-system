<?php $__env->startSection('title', 'Activity Logs - Professor'); ?>
<?php $__env->startSection('header', 'My Activity Logs'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6 space-y-6">
    <!-- Logs List -->
    <div class="space-y-3">
        <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-gray-900 border border-gray-800 rounded-lg p-4 hover:border-gray-700 transition">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <span class="inline-block px-3 py-1 bg-purple-900/30 text-purple-300 text-xs rounded-full font-semibold">
                            <?php echo e(ucfirst(str_replace('_', ' ', $log->action))); ?>

                        </span>
                    </div>
                    <span class="text-gray-500 text-sm"><?php echo e($log->created_at->diffForHumans()); ?></span>
                </div>
                <?php if($log->description): ?>
                    <p class="text-gray-300 text-sm mt-2"><?php echo e($log->description); ?></p>
                <?php endif; ?>
                <div class="flex gap-4 text-gray-500 text-xs mt-3 pt-3 border-t border-gray-800">
                    <?php if($log->ip_address): ?>
                        <span>IP: <?php echo e($log->ip_address); ?></span>
                    <?php endif; ?>
                    <span><?php echo e($log->created_at->format('M d, Y H:i:s')); ?></span>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="bg-gray-900 border border-gray-800 rounded-lg p-12 text-center">
                <p class="text-gray-400">No activity logs yet</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if($logs->hasPages()): ?>
        <div class="flex justify-center">
            <?php echo e($logs->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\dashboard\Attendance-System\resources\views/professor/logs.blade.php ENDPATH**/ ?>