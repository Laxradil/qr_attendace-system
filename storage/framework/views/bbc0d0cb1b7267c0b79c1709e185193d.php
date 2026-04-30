<?php $__env->startSection('title', 'Dashboard - Admin'); ?>
<?php $__env->startSection('header', 'Admin Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6 space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-gray-900 border border-gray-800 p-6 rounded-lg">
            <p class="text-gray-400 text-sm">Total Users</p>
            <p class="text-3xl font-bold text-white mt-2"><?php echo e($totalUsers); ?></p>
        </div>
        <div class="bg-gray-900 border border-gray-800 p-6 rounded-lg">
            <p class="text-gray-400 text-sm">Professors</p>
            <p class="text-3xl font-bold text-purple-400 mt-2"><?php echo e($totalProfessors); ?></p>
        </div>
        <div class="bg-gray-900 border border-gray-800 p-6 rounded-lg">
            <p class="text-gray-400 text-sm">Students</p>
            <p class="text-3xl font-bold text-blue-400 mt-2"><?php echo e($totalStudents); ?></p>
        </div>
        <div class="bg-gray-900 border border-gray-800 p-6 rounded-lg">
            <p class="text-gray-400 text-sm">Classes</p>
            <p class="text-3xl font-bold text-green-400 mt-2"><?php echo e($totalClasses); ?></p>
        </div>
        <div class="bg-gray-900 border border-gray-800 p-6 rounded-lg">
            <p class="text-gray-400 text-sm">Attendance Records</p>
            <p class="text-3xl font-bold text-amber-400 mt-2"><?php echo e($totalAttendance); ?></p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <a href="<?php echo e(route('admin.users.create')); ?>" class="bg-purple-600 hover:bg-purple-700 text-white p-6 rounded-lg text-center font-semibold transition transform hover:scale-105">
            <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
            </svg>
            Add User
        </a>
        <a href="<?php echo e(route('admin.classes.create')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white p-6 rounded-lg text-center font-semibold transition transform hover:scale-105">
            <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Class
        </a>
        <a href="<?php echo e(route('admin.qr-codes')); ?>" class="bg-green-600 hover:bg-green-700 text-white p-6 rounded-lg text-center font-semibold transition transform hover:scale-105">
            <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            QR Codes
        </a>
        <a href="<?php echo e(route('admin.logs')); ?>" class="bg-red-600 hover:bg-red-700 text-white p-6 rounded-lg text-center font-semibold transition transform hover:scale-105">
            <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            System Logs
        </a>
    </div>

    <!-- Recent Activity -->
    <div class="bg-gray-900 border border-gray-800 rounded-lg p-6">
        <h2 class="text-xl font-bold text-white mb-4">Recent Activity</h2>
        <div class="space-y-3">
            <?php $__empty_1 = true; $__currentLoopData = $recentLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex items-start justify-between p-3 hover:bg-gray-800/50 rounded transition">
                    <div>
                        <p class="text-white text-sm font-semibold"><?php echo e($log->user->name ?? 'System'); ?></p>
                        <p class="text-gray-400 text-xs"><?php echo e(ucfirst(str_replace('_', ' ', $log->action))); ?></p>
                    </div>
                    <span class="text-gray-500 text-xs"><?php echo e($log->created_at->diffForHumans()); ?></span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-gray-400 text-sm">No recent activity</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\qr_attendace-system\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>