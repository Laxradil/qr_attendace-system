<?php $__env->startSection('title', 'Reports'); ?>
<?php $__env->startSection('header', 'Reports'); ?>
<?php $__env->startSection('subheader', 'Generate and view attendance reports and statistics.'); ?>

<?php $__env->startSection('content'); ?>
<div class="stats stats-5" style="margin-bottom:12px;">
    <div class="stat stat-row"><div class="stat-icon" style="background:var(--blue-bg);"></div><div><div class="stat-val"><?php echo e($totalStudents); ?></div><div class="stat-label">Total Students</div></div></div>
    <div class="stat stat-row"><div class="stat-icon" style="background:var(--green-bg);"></div><div><div class="stat-val" style="color:var(--green);"><?php echo e($presentCount); ?></div><div class="stat-label">Present</div></div></div>
    <div class="stat stat-row"><div class="stat-icon" style="background:var(--amber-bg);"></div><div><div class="stat-val" style="color:var(--amber);"><?php echo e($lateCount); ?></div><div class="stat-label">Late</div></div></div>
    <div class="stat stat-row"><div class="stat-icon" style="background:var(--red-bg);"></div><div><div class="stat-val" style="color:var(--red);"><?php echo e($absentCount); ?></div><div class="stat-label">Absent</div></div></div>
    <div class="stat stat-row"><div class="stat-icon" style="background:var(--purple-glow);"></div><div><div class="stat-val"><?php echo e($totalRecords); ?></div><div class="stat-label">Attendance Records</div></div></div>
</div>

<div class="sh">Top Classes by Attendance</div>
<div class="tbl-wrap">
    <table>
        <thead><tr><th>Rank</th><th>Class</th><th>Professor</th><th>Present</th><th>Total</th><th>Attendance Rate</th></tr></thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $topClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $classe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $rate = $classe->total_records > 0 ? round(($classe->present_records / $classe->total_records) * 100, 2) : 0;
                ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td>
                        <div style="font-size:11px;font-weight:500;"><?php echo e($classe->name); ?></div>
                        <div class="td-mono" style="font-size:9px;"><?php echo e($classe->code); ?></div>
                    </td>
                    <td style="font-size:10px;"><?php echo e($classe->professor->name ?? 'N/A'); ?></td>
                    <td><?php echo e($classe->present_records); ?></td>
                    <td><?php echo e($classe->total_records); ?></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div style="flex:1;height:4px;background:var(--navy3);border-radius:2px;"><div style="width:<?php echo e($rate); ?>%;height:100%;background:<?php echo e($rate >= 85 ? 'var(--green)' : 'var(--amber)'); ?>;border-radius:2px;"></div></div>
                            <span style="font-size:10px;font-weight:600;"><?php echo e($rate); ?>%</span>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" style="text-align:center;color:var(--text2);">No report data available yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\qr_attendace-system\resources\views/admin/reports.blade.php ENDPATH**/ ?>