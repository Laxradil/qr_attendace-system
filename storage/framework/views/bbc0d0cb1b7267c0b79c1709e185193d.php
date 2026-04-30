<?php $__env->startSection('title', 'Admin Dashboard'); ?>
<?php $__env->startSection('header', 'Dashboard'); ?>
<?php $__env->startSection('subheader', "Welcome back, Admin! Here's what's happening in the system."); ?>

<?php $__env->startSection('content'); ?>
<div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px;">
    <a class="stat" style="flex:1;min-width:100px;text-decoration:none;" href="<?php echo e(route('admin.users')); ?>">
        <div class="stat-val"><?php echo e($totalUsers); ?></div>
        <div class="stat-label">Total Users</div>
        <div style="font-size:10px;color:var(--blue);margin-top:2px;">View all -></div>
    </a>
    <a class="stat" style="flex:1;min-width:100px;text-decoration:none;" href="<?php echo e(route('admin.professors')); ?>">
        <div class="stat-val"><?php echo e($totalProfessors); ?></div>
        <div class="stat-label">Professors</div>
        <div style="font-size:10px;color:var(--blue);margin-top:2px;">View all -></div>
    </a>
    <a class="stat" style="flex:1;min-width:100px;text-decoration:none;" href="<?php echo e(route('admin.students')); ?>">
        <div class="stat-val"><?php echo e($totalStudents); ?></div>
        <div class="stat-label">Students</div>
        <div style="font-size:10px;color:var(--blue);margin-top:2px;">View all -></div>
    </a>
    <a class="stat" style="flex:1;min-width:100px;text-decoration:none;" href="<?php echo e(route('admin.classes')); ?>">
        <div class="stat-val"><?php echo e($totalClasses); ?></div>
        <div class="stat-label">Classes</div>
        <div style="font-size:10px;color:var(--blue);margin-top:2px;">View all -></div>
    </a>
</div>

<div class="g-6-4">
    <div>
        <div class="sh">Attendance Overview</div>
        <div class="card">
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:8px;">
                <div>
                    <div class="stat-val" style="font-size:18px;color:var(--green);"><?php echo e($presentCount); ?></div>
                    <div class="stat-label">Present</div>
                </div>
                <div>
                    <div class="stat-val" style="font-size:18px;color:var(--amber);"><?php echo e($lateCount); ?></div>
                    <div class="stat-label">Late</div>
                </div>
                <div>
                    <div class="stat-val" style="font-size:18px;color:var(--red);"><?php echo e($absentCount); ?></div>
                    <div class="stat-label">Absent</div>
                </div>
                <div>
                    <div class="stat-val" style="font-size:18px;color:var(--blue);"><?php echo e($totalAttendance); ?></div>
                    <div class="stat-label">Total Records</div>
                </div>
            </div>
            <a class="btn btn-sm" href="<?php echo e(route('admin.reports')); ?>" style="width:100%;justify-content:center;margin-top:8px;">View Full Report -></a>
        </div>

        <div class="sh">Recent Activities</div>
        <div class="card">
            <?php $__empty_1 = true; $__currentLoopData = $recentLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div style="display:flex;gap:9px;padding:7px 0;border-bottom:1px solid var(--border2);align-items:flex-start;">
                    <div style="width:26px;height:26px;border-radius:6px;background:var(--purple-glow);display:flex;align-items:center;justify-content:center;font-size:10px;"><?php echo e(strtoupper(substr($log->action, 0, 1))); ?></div>
                    <div style="font-size:11px;flex:1;">
                        <strong><?php echo e($log->user->name ?? 'System'); ?></strong> <?php echo e(str_replace('_', ' ', $log->action)); ?>

                        <?php if($log->description): ?>
                            <div style="font-size:10px;color:var(--text2);margin-top:1px;"><?php echo e($log->description); ?></div>
                        <?php endif; ?>
                    </div>
                    <div style="font-size:9px;color:var(--text3);white-space:nowrap;font-family:'JetBrains Mono',monospace;"><?php echo e($log->created_at?->format('h:i A')); ?></div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div style="color:var(--text2);font-size:11px;">No recent activity.</div>
            <?php endif; ?>
            <a class="btn btn-sm" href="<?php echo e(route('admin.logs')); ?>" style="width:100%;justify-content:center;margin-top:6px;">View All Activities -></a>
        </div>
    </div>

    <div>
        <div class="sh">System Overview</div>
        <div class="card">
            <div style="display:flex;justify-content:space-between;align-items:center;padding:7px 0;border-bottom:1px solid var(--border2);"><span style="font-size:11px;">Active QR Codes</span><span style="font-size:13px;font-weight:700;color:var(--green);"><?php echo e($activeQRCodes); ?></span></div>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:7px 0;border-bottom:1px solid var(--border2);"><span style="font-size:11px;">Today's Records</span><span style="font-size:13px;font-weight:700;"><?php echo e($todayRecords); ?></span></div>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:7px 0;border-bottom:1px solid var(--border2);"><span style="font-size:11px;">Total Classes</span><span style="font-size:13px;font-weight:700;"><?php echo e($totalClasses); ?></span></div>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:7px 0;"><span style="font-size:11px;">System Status</span><span class="badge bg">All Systems Operational</span></div>
        </div>

        <div class="sh">Quick Actions</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:7px;">
            <a class="btn" href="<?php echo e(route('admin.users.create')); ?>" style="justify-content:center;">Add User</a>
            <a class="btn" href="<?php echo e(route('admin.classes.create')); ?>" style="justify-content:center;">Add Class</a>
            <a class="btn" href="<?php echo e(route('admin.students')); ?>" style="justify-content:center;">Manage Students</a>
            <a class="btn" href="<?php echo e(route('admin.qr-codes')); ?>" style="justify-content:center;">Generate QR</a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\qr_attendace-system\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>