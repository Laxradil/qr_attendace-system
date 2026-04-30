<?php $__env->startSection('title', 'Classes'); ?>
<?php $__env->startSection('header', 'Classes'); ?>
<?php $__env->startSection('subheader', 'Manage all classes and schedules in the system.'); ?>

<?php $__env->startSection('content'); ?>
<div style="display:flex;gap:8px;margin-bottom:12px;">
    <a href="<?php echo e(route('admin.classes.create')); ?>" class="btn btn-sm btn-p">+ Add Class</a>
</div>

<div class="tbl-wrap">
    <table>
        <thead><tr><th>Class Code</th><th>Class Name</th><th>Professor</th><th>Enrolled</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="td-mono"><?php echo e($classe->code); ?></td>
                    <td>
                        <div style="font-weight:500;font-size:11px;"><?php echo e($classe->name); ?></div>
                        <div style="font-size:9px;color:var(--text3);"><?php echo e($classe->description ?: 'No description'); ?></div>
                    </td>
                    <td style="font-size:10px;"><?php echo e($classe->professor->name ?? 'N/A'); ?></td>
                    <td style="font-size:11px;font-weight:600;"><?php echo e($classe->students->count()); ?></td>
                    <td><span class="badge <?php echo e($classe->is_active ? 'bg' : 'ba'); ?>"><?php echo e($classe->is_active ? 'Active' : 'Inactive'); ?></span></td>
                    <td style="display:flex;gap:4px;">
                        <a href="<?php echo e(route('admin.classes.edit', $classe)); ?>" class="btn btn-sm">Edit</a>
                        <form action="<?php echo e(route('admin.classes.delete', $classe)); ?>" method="POST" onsubmit="return confirm('Delete this class?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="btn btn-sm btn-d" type="submit">Delete</button></form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" style="text-align:center;color:var(--text2);">No classes found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="pag"><span>Showing <?php echo e($classes->firstItem() ?? 0); ?> to <?php echo e($classes->lastItem() ?? 0); ?> of <?php echo e($classes->total()); ?> classes</span><div><?php echo e($classes->links()); ?></div></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\qr_attendace-system\resources\views/admin/classes.blade.php ENDPATH**/ ?>