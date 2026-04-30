<?php $__env->startSection('title', 'Attendance Records'); ?>
<?php $__env->startSection('header', 'Attendance Records'); ?>
<?php $__env->startSection('subheader', 'View and manage all attendance records.'); ?>

<?php $__env->startSection('content'); ?>
<div class="stats stats-4" style="margin-bottom:12px;">
    <div class="stat stat-row"><div class="stat-icon" style="background:var(--blue-bg);"></div><div><div class="stat-val"><?php echo e($records->total()); ?></div><div class="stat-label">Total Records</div></div></div>
    <div class="stat stat-row"><div class="stat-icon" style="background:var(--green-bg);"></div><div><div class="stat-val" style="color:var(--green);"><?php echo e(\App\Models\AttendanceRecord::where('status', 'present')->count()); ?></div><div class="stat-label">Present</div></div></div>
    <div class="stat stat-row"><div class="stat-icon" style="background:var(--amber-bg);"></div><div><div class="stat-val" style="color:var(--amber);"><?php echo e(\App\Models\AttendanceRecord::where('status', 'late')->count()); ?></div><div class="stat-label">Late</div></div></div>
    <div class="stat stat-row"><div class="stat-icon" style="background:var(--red-bg);"></div><div><div class="stat-val" style="color:var(--red);"><?php echo e(\App\Models\AttendanceRecord::where('status', 'absent')->count()); ?></div><div class="stat-label">Absent</div></div></div>
</div>

<div class="tbl-wrap">
    <table>
        <thead><tr><th>Date & Time</th><th>Student</th><th>Class</th><th>Status</th><th>Minutes Late</th></tr></thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="td-mono"><?php echo e($record->recorded_at?->format('M d, Y h:i A')); ?></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:6px;">
                            <div class="log-av"><?php echo e(strtoupper(substr($record->student->name ?? 'ST', 0, 2))); ?></div>
                            <div>
                                <div style="font-size:11px;font-weight:500;"><?php echo e($record->student->name ?? 'Unknown'); ?></div>
                                <div class="td-mono" style="font-size:9px;"><?php echo e($record->student->student_id ?? 'N/A'); ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-size:11px;font-weight:500;"><?php echo e($record->classe->name ?? 'N/A'); ?></div>
                        <div style="font-size:9px;color:var(--text2);"><?php echo e($record->classe->code ?? '-'); ?></div>
                    </td>
                    <td>
                        <?php if($record->status === 'present'): ?>
                            <span class="badge bg">Present</span>
                        <?php elseif($record->status === 'late'): ?>
                            <span class="badge ba">Late</span>
                        <?php else: ?>
                            <span class="badge br">Absent</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($record->status === 'late' ? (int) $record->minutes_late . ' min' : '-'); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" style="text-align:center;color:var(--text2);">No attendance records found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="pag"><span>Showing <?php echo e($records->firstItem() ?? 0); ?> to <?php echo e($records->lastItem() ?? 0); ?> of <?php echo e($records->total()); ?> records</span><div><?php echo e($records->links()); ?></div></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\qr_attendace-system\resources\views/admin/attendance-records.blade.php ENDPATH**/ ?>