<?php $__env->startSection('title', 'Users'); ?>
<?php $__env->startSection('header', 'Users'); ?>
<?php $__env->startSection('subheader', 'Manage all system users and their access.'); ?>

<?php $__env->startSection('content'); ?>
<div class="stats stats-4" style="margin-bottom:12px;">
    <div class="stat stat-row"><div class="stat-icon" style="background:var(--blue-bg);"></div><div><div class="stat-val"><?php echo e($users->total()); ?></div><div class="stat-label">Total Users</div></div></div>
    <div class="stat stat-row"><div class="stat-icon" style="background:var(--red-bg);"></div><div><div class="stat-val"><?php echo e(\App\Models\User::where('role', 'admin')->count()); ?></div><div class="stat-label">Administrators</div></div></div>
    <div class="stat stat-row"><div class="stat-icon" style="background:var(--purple-glow);"></div><div><div class="stat-val"><?php echo e(\App\Models\User::where('role', 'professor')->count()); ?></div><div class="stat-label">Professors</div></div></div>
    <div class="stat stat-row"><div class="stat-icon" style="background:var(--green-bg);"></div><div><div class="stat-val"><?php echo e(\App\Models\User::where('role', 'student')->count()); ?></div><div class="stat-label">Students</div></div></div>
</div>

<div style="display:flex;gap:8px;margin-bottom:12px;align-items:center;">
    <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-sm btn-p">+ Add User</a>
</div>

<div class="tbl-wrap">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Date Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="td-mono">USR-<?php echo e(str_pad((string) $user->id, 4, '0', STR_PAD_LEFT)); ?></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:7px;">
                            <div class="log-av"><?php echo e(strtoupper(substr($user->name, 0, 2))); ?></div>
                            <span style="font-weight:500;"><?php echo e($user->name); ?></span>
                        </div>
                    </td>
                    <td style="font-size:10px;color:var(--text2);"><?php echo e($user->email); ?></td>
                    <td>
                        <span class="badge <?php echo e($user->role === 'admin' ? 'br' : ($user->role === 'professor' ? 'bp' : 'bx')); ?>"><?php echo e(ucfirst($user->role)); ?></span>
                    </td>
                    <td>
                        <span class="badge <?php echo e($user->is_active ? 'bg' : 'ba'); ?>"><?php echo e($user->is_active ? 'Active' : 'Inactive'); ?></span>
                    </td>
                    <td class="td-mono"><?php echo e($user->created_at?->format('M d, Y')); ?></td>
                    <td style="display:flex;gap:4px;">
                        <a class="btn btn-sm" href="<?php echo e(route('admin.users.edit', $user)); ?>">Edit</a>
                        <form action="<?php echo e(route('admin.users.delete', $user)); ?>" method="POST" onsubmit="return confirm('Delete this user?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-sm btn-d">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7" style="text-align:center;color:var(--text2);">No users found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="pag">
        <span>Showing <?php echo e($users->firstItem() ?? 0); ?> to <?php echo e($users->lastItem() ?? 0); ?> of <?php echo e($users->total()); ?> users</span>
        <div><?php echo e($users->links()); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\qr_attendace-system\resources\views/admin/users.blade.php ENDPATH**/ ?>