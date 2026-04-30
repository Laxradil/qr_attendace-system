<?php $__env->startSection('title', 'QR Code Management'); ?>
<?php $__env->startSection('header', 'QR Code Management'); ?>
<?php $__env->startSection('subheader', 'Generate, view and manage QR codes for classes.'); ?>

<?php $__env->startSection('content'); ?>
<div class="card" style="margin-bottom:12px;">
    <form action="<?php echo e(route('admin.qr-codes.generate')); ?>" method="POST" style="display:flex;gap:8px;flex-wrap:wrap;">
        <?php echo csrf_field(); ?>
        <select name="class_id" required class="fi" style="flex:1;min-width:220px;">
            <option value="">Select class...</option>
            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($classe->id); ?>"><?php echo e($classe->code); ?> - <?php echo e($classe->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <input class="fi" type="number" name="count" min="1" max="100" value="1" required style="width:120px;" placeholder="Count">
        <input class="fi" type="date" name="expires_at" style="width:180px;">
        <button type="submit" class="btn btn-p">+ Generate QR Code</button>
    </form>
</div>

<div class="tbl-wrap">
    <table>
        <thead><tr><th>QR Code</th><th>Class</th><th>Professor</th><th>Generated On</th><th>Expires On</th><th>Status</th></tr></thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $qrCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $qr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div class="td-mono" style="color:var(--purple-light);"><?php echo e(strtoupper(substr($qr->uuid, 0, 12))); ?></div>
                        <div style="font-size:9px;color:var(--text3);"><?php echo e($qr->uuid); ?></div>
                    </td>
                    <td>
                        <div style="font-size:11px;font-weight:500;"><?php echo e($qr->classe->name ?? 'N/A'); ?></div>
                        <div style="font-size:9px;color:var(--text2);"><?php echo e($qr->classe->code ?? '-'); ?></div>
                    </td>
                    <td style="font-size:10px;"><?php echo e($qr->professor->name ?? 'N/A'); ?></td>
                    <td class="td-mono" style="font-size:10px;"><?php echo e($qr->created_at?->format('M d, Y h:i A')); ?></td>
                    <td class="td-mono" style="font-size:10px;"><?php echo e($qr->expires_at?->format('M d, Y h:i A') ?: 'No expiry'); ?></td>
                    <td>
                        <?php if($qr->is_used): ?>
                            <span class="badge bb">Used</span>
                        <?php elseif($qr->isExpired()): ?>
                            <span class="badge br">Expired</span>
                        <?php else: ?>
                            <span class="badge bg">Active</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" style="text-align:center;color:var(--text2);">No QR codes generated yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="pag"><span>Showing <?php echo e($qrCodes->firstItem() ?? 0); ?> to <?php echo e($qrCodes->lastItem() ?? 0); ?> of <?php echo e($qrCodes->total()); ?> QR codes</span><div><?php echo e($qrCodes->links()); ?></div></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\qr_attendace-system\resources\views/admin/qr-codes.blade.php ENDPATH**/ ?>