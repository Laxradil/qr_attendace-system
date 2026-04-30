<?php $__env->startSection('title', 'Professors'); ?>
<?php $__env->startSection('header', 'Professors'); ?>
<?php $__env->startSection('subheader', 'Manage all professor accounts in the system.'); ?>

<?php $__env->startSection('content'); ?>
<div style="display:flex;gap:8px;margin-bottom:12px;">
    <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-sm btn-p">+ Add Professor</a>
</div>

<div class="tbl-wrap">
    <table>
        <thead><tr><th>ID</th><th>Professor</th><th>Email</th><th>Status</th><th>Date Joined</th><th>Actions</th></tr></thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $professors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $professor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="td-mono">PRF-<?php echo e(str_pad((string) $professor->id, 4, '0', STR_PAD_LEFT)); ?></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:7px;"><div class="log-av"><?php echo e(strtoupper(substr($professor->name, 0, 2))); ?></div><span style="font-weight:500;"><?php echo e($professor->name); ?></span></div>
                    </td>
                    <td style="font-size:10px;color:var(--text2);"><?php echo e($professor->email); ?></td>
                    <td><span class="badge <?php echo e($professor->is_active ? 'bg' : 'br'); ?>"><?php echo e($professor->is_active ? 'Active' : 'Inactive'); ?></span></td>
                    <td class="td-mono"><?php echo e($professor->created_at?->format('M d, Y')); ?></td>
                    <td style="display:flex;gap:4px;">
                        <a href="<?php echo e(route('admin.users.edit', $professor)); ?>" class="btn btn-sm">Edit</a>
                        <form action="<?php echo e(route('admin.users.delete', $professor)); ?>" method="POST" onsubmit="return confirm('Delete this professor?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="btn btn-sm btn-d" type="submit">Delete</button></form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" style="text-align:center;color:var(--text2);">No professors found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="pag"><span>Showing <?php echo e($professors->firstItem() ?? 0); ?> to <?php echo e($professors->lastItem() ?? 0); ?> of <?php echo e($professors->total()); ?> professors</span><div><?php echo e($professors->links()); ?></div></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\qr_attendace-system\resources\views/admin/professors.blade.php ENDPATH**/ ?>