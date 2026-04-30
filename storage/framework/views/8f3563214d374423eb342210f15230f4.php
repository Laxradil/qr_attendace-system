<?php $__env->startSection('title', 'Students - Professor'); ?>
<?php $__env->startSection('header', 'My Students'); ?>
<?php $__env->startSection('subheader', 'View all students in your assigned classes'); ?>

<?php $__env->startSection('content'); ?>
<div class="content">
    <div class="tbl-wrap">
        <table>
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Classes</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="td-mono"><?php echo e($student->student_id ?? 'N/A'); ?></td>
                        <td style="font-weight:500;"><?php echo e($student->name); ?></td>
                        <td style="color:var(--text2);"><?php echo e($student->email); ?></td>
                        <td style="text-align:center;color:var(--text2);"><?php echo e($student->enrolledClasses->count()); ?></td>
                        <td><span class="badge <?php echo e($student->is_active ? 'bg' : 'br'); ?>"><?php echo e($student->is_active ? 'Active' : 'Inactive'); ?></span></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" style="text-align:center;color:var(--text2);padding:20px;">No students found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.professor', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\dashboard\Attendance-System\resources\views/professor/students.blade.php ENDPATH**/ ?>