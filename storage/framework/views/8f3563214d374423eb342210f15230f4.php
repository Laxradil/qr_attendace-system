<?php $__env->startSection('title', 'Students - Professor'); ?>
<?php $__env->startSection('header', 'My Students'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6 space-y-6">
    <!-- Students Table -->
    <div class="bg-gray-900 border border-gray-800 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Student ID</th>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Name</th>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Email</th>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Classes</th>
                        <th class="px-6 py-3 text-left text-gray-300 font-semibold text-sm">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-800/50 transition">
                            <td class="px-6 py-4 text-white font-mono"><?php echo e($student->student_id ?? 'N/A'); ?></td>
                            <td class="px-6 py-4 text-white"><?php echo e($student->name); ?></td>
                            <td class="px-6 py-4 text-gray-400"><?php echo e($student->email); ?></td>
                            <td class="px-6 py-4 text-gray-400">
                                <?php echo e($student->enrolledClasses->count()); ?>

                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 bg-green-900/30 text-green-300 text-xs rounded-full font-semibold">
                                    <?php echo e($student->is_active ? 'Active' : 'Inactive'); ?>

                                </span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-400">No students found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\dashboard\Attendance-System\resources\views/professor/students.blade.php ENDPATH**/ ?>