<?php $__env->startSection('title', 'Create Class'); ?>
<?php $__env->startSection('header', 'Create Class'); ?>
<?php $__env->startSection('subheader', 'Create a class and assign a professor.'); ?>

<?php $__env->startSection('content'); ?>
<div class="card" style="max-width:760px;">
    <form action="<?php echo e(route('admin.classes.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <label class="fl">Class Code *</label>
        <input class="fi" type="text" name="code" value="<?php echo e(old('code')); ?>" maxlength="20" required>
        <div style="height:8px;"></div>

        <label class="fl">Class Name *</label>
        <input class="fi" type="text" name="name" value="<?php echo e(old('name')); ?>" required>
        <div style="height:8px;"></div>

        <label class="fl">Description</label>
        <textarea class="fi" name="description" rows="4"><?php echo e(old('description')); ?></textarea>
        <div style="height:8px;"></div>

        <label class="fl">Assigned Professor *</label>
        <select class="fi" name="professor_id" required>
            <option value="">Select a professor...</option>
            <?php $__currentLoopData = $professors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $professor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($professor->id); ?>" <?php echo e(old('professor_id') == $professor->id ? 'selected' : ''); ?>><?php echo e($professor->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        <div style="display:flex;gap:8px;margin-top:14px;">
            <button type="submit" class="btn btn-p">Create Class</button>
            <a href="<?php echo e(route('admin.classes')); ?>" class="btn">Cancel</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\qr_attendace-system\resources\views/admin/create-class.blade.php ENDPATH**/ ?>