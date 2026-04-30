<?php $__env->startSection('title', 'Reports - Admin'); ?>
<?php $__env->startSection('header', 'System Reports'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6 space-y-6">
    <div class="bg-gray-900 border border-gray-800 rounded-lg p-6">
        <h2 class="text-2xl font-bold text-white mb-4">System Reports</h2>
        <p class="text-gray-400 mb-6">View comprehensive analytics and trends across the system.</p>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gray-800 p-4 rounded-lg">
                <p class="text-gray-400 text-sm">Total Classes</p>
                <p class="text-3xl font-bold text-white mt-2">-</p>
            </div>
            <div class="bg-gray-800 p-4 rounded-lg">
                <p class="text-gray-400 text-sm">Total Attendance</p>
                <p class="text-3xl font-bold text-white mt-2">-</p>
            </div>
            <div class="bg-gray-800 p-4 rounded-lg">
                <p class="text-gray-400 text-sm">Avg Attendance Rate</p>
                <p class="text-3xl font-bold text-white mt-2">-</p>
            </div>
            <div class="bg-gray-800 p-4 rounded-lg">
                <p class="text-gray-400 text-sm">Active Classes</p>
                <p class="text-3xl font-bold text-white mt-2">-</p>
            </div>
        </div>

        <div class="mt-8 p-6 bg-gray-800 rounded-lg text-center text-gray-400">
            <p>Advanced reporting features coming soon</p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\qr_attendace-system\resources\views/admin/reports.blade.php ENDPATH**/ ?>