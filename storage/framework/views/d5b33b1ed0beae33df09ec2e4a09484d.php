<?php $__env->startSection('title', 'Create User'); ?>
<?php $__env->startSection('header', 'Create User'); ?>
<?php $__env->startSection('subheader', 'Add a new user account to the system.'); ?>

<?php $__env->startSection('content'); ?>
<div class="card" style="max-width:760px;">
    <form action="<?php echo e(route('admin.users.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <label class="fl">Full Name *</label>
        <input class="fi" type="text" name="name" value="<?php echo e(old('name')); ?>" required>
        <div style="height:8px;"></div>

        <label class="fl">Email *</label>
        <input class="fi" type="email" name="email" value="<?php echo e(old('email')); ?>" required>
        <div style="height:8px;"></div>

        <label class="fl">Username *</label>
        <input class="fi" type="text" name="username" value="<?php echo e(old('username')); ?>" required>
        <div style="height:8px;"></div>

        <label class="fl">Role *</label>
        <select class="fi" name="role" required>
            <option value="">Select a role...</option>
            <option value="admin" <?php echo e(old('role') == 'admin' ? 'selected' : ''); ?>>Admin</option>
            <option value="professor" <?php echo e(old('role') == 'professor' ? 'selected' : ''); ?>>Professor</option>
            <option value="student" <?php echo e(old('role') == 'student' ? 'selected' : ''); ?>>Student</option>
        </select>
        <div style="height:8px;"></div>

        <label class="fl">Student ID</label>
        <input class="fi" type="text" name="student_id" value="<?php echo e(old('student_id')); ?>">
        <div style="height:8px;"></div>

        <label class="fl">Password *</label>
        <input class="fi" type="password" name="password" required>
        <div style="height:8px;"></div>

        <label class="fl">Confirm Password *</label>
        <input class="fi" type="password" name="password_confirmation" required>

        <div style="display:flex;gap:8px;margin-top:14px;">
            <button type="submit" class="btn btn-p">Create User</button>
            <a href="<?php echo e(route('admin.users')); ?>" class="btn">Cancel</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\qr_attendace-system\resources\views/admin/create-user.blade.php ENDPATH**/ ?>