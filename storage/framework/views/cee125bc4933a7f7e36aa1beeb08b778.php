<?php $__env->startSection('title', 'System Logs'); ?>
<?php $__env->startSection('header', 'System Logs'); ?>
<?php $__env->startSection('subheader', 'View and monitor all system activities and events.'); ?>

<?php $__env->startSection('content'); ?>
<div class="tbl-wrap">
    <table>
        <thead><tr><th>Date & Time</th><th>User</th><th>Action</th><th>Description</th><th>IP Address</th></tr></thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="td-mono"><?php echo e($log->created_at?->format('M d, Y h:i:s A')); ?></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:5px;">
                            <div class="log-av" style="width:22px;height:22px;font-size:9px;"><?php echo e(strtoupper(substr($log->user->name ?? 'SY', 0, 2))); ?></div>
                            <div>
                                <div style="font-size:10px;font-weight:500;"><?php echo e($log->user->name ?? 'System'); ?></div>
                                <div style="font-size:9px;color:var(--text3);"><?php echo e($log->user->role ?? 'system'); ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <?php
                            $action = strtolower($log->action ?? 'event');
                            $badge = str_contains($action, 'delete') ? 'br' : (str_contains($action, 'update') ? 'ba' : (str_contains($action, 'create') || str_contains($action, 'generate') ? 'bg' : 'bb'));
                        ?>
                        <span class="badge <?php echo e($badge); ?>"><?php echo e(strtoupper(str_replace('_', ' ', $log->action))); ?></span>
                    </td>
                    <td style="font-size:10px;"><?php echo e($log->description ?: '-'); ?></td>
                    <td class="td-mono" style="font-size:9px;"><?php echo e($log->ip_address ?: '-'); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" style="text-align:center;color:var(--text2);">No activity logs yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="pag"><span>Showing <?php echo e($logs->firstItem() ?? 0); ?> to <?php echo e($logs->lastItem() ?? 0); ?> of <?php echo e($logs->total()); ?> logs</span><div><?php echo e($logs->links()); ?></div></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\qr_attendace-system\resources\views/admin/logs.blade.php ENDPATH**/ ?>