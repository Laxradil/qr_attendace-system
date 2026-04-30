<?php $__env->startSection('title', 'Students'); ?>
<?php $__env->startSection('header', 'Students'); ?>
<?php $__env->startSection('subheader', 'Manage all student accounts in the system.'); ?>

<?php $__env->startSection('content'); ?>
<div style="display:flex;gap:8px;margin-bottom:12px;">
    <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-sm btn-p">+ Add Student</a>
</div>

<div class="tbl-wrap">
    <table>
        <thead><tr><th>Student ID</th><th>Student</th><th>Email</th><th>Username</th><th>Status</th><th>Date Joined</th><th>Actions</th></tr></thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="td-mono"><?php echo e($student->student_id ?: 'N/A'); ?></td>
                    <td><div style="display:flex;align-items:center;gap:7px;"><div class="log-av"><?php echo e(strtoupper(substr($student->name, 0, 2))); ?></div><span style="font-weight:500;"><?php echo e($student->name); ?></span></div></td>
                    <td style="font-size:10px;color:var(--text2);"><?php echo e($student->email); ?></td>
                    <td style="font-size:10px;color:var(--text2);"><?php echo e($student->username); ?></td>
                    <td><span class="badge <?php echo e($student->is_active ? 'bg' : 'br'); ?>"><?php echo e($student->is_active ? 'Active' : 'Inactive'); ?></span></td>
                    <td class="td-mono"><?php echo e($student->created_at?->format('M d, Y')); ?></td>
                    <td style="display:flex;gap:4px;"><a class="btn btn-sm" href="<?php echo e(route('admin.users.edit', $student)); ?>">Edit</a><form action="<?php echo e(route('admin.users.delete', $student)); ?>" method="POST" onsubmit="return confirm('Delete this student?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button type="submit" class="btn btn-sm btn-d">Delete</button></form></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7" style="text-align:center;color:var(--text2);">No students found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="pag"><span>Showing <?php echo e($students->firstItem() ?? 0); ?> to <?php echo e($students->lastItem() ?? 0); ?> of <?php echo e($students->total()); ?> students</span><div><?php echo e($students->links()); ?></div></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\qr_attendace-system\resources\views/admin/students.blade.php ENDPATH**/ ?>