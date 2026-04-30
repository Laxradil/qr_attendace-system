<?php $__env->startSection('title', 'Activity Logs - Professor'); ?>
<?php $__env->startSection('header', 'My Activity Logs'); ?>
<?php $__env->startSection('subheader', 'Track your recent system activities'); ?>

<?php $__env->startSection('content'); ?>
<div class="content">
    <div style="display:grid;gap:8px;">
        <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="card" style="margin-bottom:0;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:8px;">
                    <span class="badge bp" style="font-size:9px;"><?php echo e(ucfirst(str_replace('_', ' ', $log->action))); ?></span>
                    <span style="font-size:9px;color:var(--text3);"><?php echo e($log->created_at->diffForHumans()); ?></span>
                </div>
                <?php if($log->description): ?>
                    <div style="font-size:11px;color:var(--text);margin-bottom:8px;line-height:1.4;"><?php echo e($log->description); ?></div>
                <?php endif; ?>
                <div style="display:flex;gap:12px;font-size:9px;color:var(--text3);padding-top:8px;border-top:1px solid var(--border2);">
                    <?php if($log->ip_address): ?>
                        <span class="td-mono">IP: <?php echo e($log->ip_address); ?></span>
                    <?php endif; ?>
                    <span class="td-mono"><?php echo e($log->created_at->format('M d, Y H:i:s')); ?></span>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div style="text-align:center;padding:40px;color:var(--text2);">
                <div style="font-size:24px;margin-bottom:8px;">📋</div>
                <div style="font-size:12px;">No activity logs yet</div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if($logs->hasPages()): ?>
        <div style="display:flex;justify-content:center;margin-top:18px;">
            <div class="pag" style="width:100%;max-width:400px;border-top:1px solid var(--border);border-radius:0;padding:12px;">
                <span style="font-size:10px;color:var(--text2);">Showing <?php echo e($logs->firstItem()); ?> to <?php echo e($logs->lastItem()); ?> of <?php echo e($logs->total()); ?></span>
                <div class="pag-btns">
                    <?php if($logs->onFirstPage()): ?>
                        <button class="pb" disabled style="opacity:0.5;cursor:not-allowed;">←</button>
                    <?php else: ?>
                        <a href="<?php echo e($logs->previousPageUrl()); ?>" class="pb">←</a>
                    <?php endif; ?>
                    
                    <?php $__currentLoopData = $logs->getUrlRange(1, $logs->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($page == $logs->currentPage()): ?>
                            <button class="pb active"><?php echo e($page); ?></button>
                        <?php else: ?>
                            <a href="<?php echo e($url); ?>" class="pb"><?php echo e($page); ?></a>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                    <?php if($logs->hasMorePages()): ?>
                        <a href="<?php echo e($logs->nextPageUrl()); ?>" class="pb">→</a>
                    <?php else: ?>
                        <button class="pb" disabled style="opacity:0.5;cursor:not-allowed;">→</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.professor', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\dashboard\Attendance-System\resources\views/professor/logs.blade.php ENDPATH**/ ?>