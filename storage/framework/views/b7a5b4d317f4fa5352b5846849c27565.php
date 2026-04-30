<?php $__env->startSection('title', 'Classes Management - Admin'); ?>
<?php $__env->startSection('header', 'Classes Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6 space-y-6">
    <!-- Header with Add Button -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-white">All Classes</h2>
        <a href="<?php echo e(route('admin.classes.create')); ?>" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg font-semibold transition">
            + Add Class
        </a>
    </div>

    <!-- Classes Table -->
    <div class="bg-gray-900 border border-gray-800 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Code</th>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Name</th>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Professor</th>
                        <th class="px-6 py-3 text-center text-gray-300 font-semibold text-sm">Students</th>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Status</th>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    <?php $__empty_1 = true; $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-800/50 transition">
                            <td class="px-6 py-4 text-white font-semibold"><?php echo e($classe->code); ?></td>
                            <td class="px-6 py-4 text-white"><?php echo e($classe->name); ?></td>
                            <td class="px-6 py-4 text-gray-400"><?php echo e($classe->professor->name); ?></td>
                            <td class="px-6 py-4 text-center text-gray-400"><?php echo e($classe->students->count()); ?></td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 <?php echo e($classe->is_active ? 'bg-green-900/30 text-green-300' : 'bg-red-900/30 text-red-300'); ?> text-xs rounded-full font-semibold">
                                    <?php echo e($classe->is_active ? 'Active' : 'Inactive'); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 flex gap-2">
                                <a href="<?php echo e(route('admin.classes.edit', $classe)); ?>" class="text-blue-400 hover:text-blue-300 font-semibold text-sm">Edit</a>
                                <form action="<?php echo e(route('admin.classes.delete', $classe)); ?>" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-red-400 hover:text-red-300 font-semibold text-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-400">No classes found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <?php if($classes->hasPages()): ?>
        <div class="flex justify-center">
            <?php echo e($classes->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\qr_attendace-system\resources\views/admin/classes.blade.php ENDPATH**/ ?>