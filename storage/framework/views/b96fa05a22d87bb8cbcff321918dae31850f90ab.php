<?php $__env->startSection('title', 'New Invoice'); ?>
<?php $__env->startSection('page-title', 'Create Invoice'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center"><div class="col-lg-8">
<div class="card border-0 shadow-sm"><div class="card-body p-4">
    <form method="POST" action="<?php echo e(route('finance.invoices.store')); ?>">
        <?php echo csrf_field(); ?>
        <div class="mb-3"><label class="form-label">Student</label>
            <select name="student_id" class="form-select" required>
                <option value="">Select student…</option>
                <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($s->id); ?>"><?php echo e($s->user->name ?? 'Student #'.$s->id); ?> (<?php echo e($s->reg_no); ?>)</option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="row g-3">
            <div class="col-md-6 mb-3"><label class="form-label">Academic year</label><input name="academic_year" value="<?php echo e(date('Y')); ?>/<?php echo e(date('Y')+1); ?>" class="form-control" required></div>
            <div class="col-md-6 mb-3"><label class="form-label">Semester</label><select name="semester" class="form-select"><option>1</option><option>2</option><option>3</option></select></div>
        </div>
        <div class="mb-3"><label class="form-label">Description</label><input name="description" class="form-control" placeholder="e.g. Tuition fee — Year 1" required></div>
        <div class="row g-3">
            <div class="col-md-6 mb-3"><label class="form-label">Amount (KES)</label><input type="number" step="0.01" name="amount" class="form-control" required></div>
            <div class="col-md-6 mb-3"><label class="form-label">Due date</label><input type="date" name="due_date" class="form-control"></div>
        </div>
        <button class="btn btn-primary"><i class="fas fa-save me-1"></i> Create invoice</button>
        <a href="<?php echo e(route('finance.invoices')); ?>" class="btn btn-link">Cancel</a>
    </form>
</div></div>
</div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\finance\invoice-create.blade.php ENDPATH**/ ?>