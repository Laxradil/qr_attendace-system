<?php $__env->startSection('title', 'My Classes - Professor'); ?>
<?php $__env->startSection('header', 'My Classes'); ?>
<?php $__env->startSection('subheader', 'View and manage your assigned classes'); ?>

<?php $__env->startSection('content'); ?>
<div class="content">
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:12px;">
        <?php $__empty_1 = true; $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="card" style="margin-bottom:0;padding:0;overflow:hidden;display:flex;flex-direction:column;">
                <div style="background:linear-gradient(135deg,var(--purple),var(--blue));padding:14px;border-bottom:1px solid var(--border);">
                    <div style="font-size:14px;font-weight:700;"><?php echo e($classe->code); ?></div>
                    <div style="font-size:11px;color:var(--purple-light);margin-top:2px;"><?php echo e($classe->name); ?></div>
                </div>
                <div style="padding:12px;flex:1;display:flex;flex-direction:column;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;font-size:11px;">
                        <span style="color:var(--text2);">Students:</span>
                        <span style="font-weight:600;color:var(--text);"><?php echo e($classe->students_count); ?></span>
                    </div>
                    <?php if($classe->description): ?>
                        <div style="font-size:10px;color:var(--text2);margin-bottom:8px;line-height:1.4;"><?php echo e(substr($classe->description, 0, 80)); ?><?php echo e(strlen($classe->description) > 80 ? '...' : ''); ?></div>
                    <?php endif; ?>
                    <div style="flex:1;"></div>
                    <div style="display:flex;justify-content:space-between;align-items:center;padding-top:8px;border-top:1px solid var(--border);margin-top:8px;">
                        <span class="badge <?php echo e($classe->is_active ? 'bg' : 'br'); ?>" style="font-size:9px;"><?php echo e($classe->is_active ? 'Active' : 'Inactive'); ?></span>
                        <a href="<?php echo e(route('professor.class-detail', $classe)); ?>" class="btn btn-sm" style="text-decoration:none;">View →</a>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div style="grid-column:1 / -1;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:40px;text-align:center;">
                <div style="width:60px;height:60px;border-radius:50%;background:var(--navy3);display:flex;align-items:center;justify-content:center;margin-bottom:12px;font-size:24px;">📚</div>
                <div style="font-size:13px;color:var(--text2);">No classes assigned yet</div>
                <div style="font-size:10px;color:var(--text3);margin-top:4px;">Your assigned classes will appear here</div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.professor', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\dashboard\Attendance-System\resources\views/professor/classes.blade.php ENDPATH**/ ?>