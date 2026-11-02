<?php $__env->startSection('title', 'Programmes'); ?>
<?php $__env->startSection('page-title', 'Manage Programmes'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <p class="text-muted mb-0">Add, edit or remove the postgraduate programmes offered.</p>
    <a href="<?php echo e(route('admin.programmes.create')); ?>" class="btn btn-primary"><i class="fas fa-plus me-1"></i> New programme</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th class="ps-3">Programme</th><th>School / Dept</th><th>Level</th><th>Yrs</th><th>Fees/yr</th><th>Status</th><th class="text-end pe-3"></th></tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $programmes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-3"><span class="fw-semibold"><?php echo e($p->name); ?></span><div class="text-muted small font-monospace"><?php echo e($p->code); ?></div></td>
                        <td class="small text-muted"><?php echo e(optional($p->department->school)->code); ?> · <?php echo e(optional($p->department)->name); ?></td>
                        <td><span class="badge bg-secondary"><?php echo e($p->level_label); ?></span></td>
                        <td><?php echo e($p->duration_years); ?></td>
                        <td class="text-nowrap">KES <?php echo e(number_format($p->tuition_per_year)); ?></td>
                        <td>
                            <?php if($p->is_active): ?><span class="badge bg-success">Active</span><?php else: ?><span class="badge bg-secondary">Inactive</span><?php endif; ?>
                            <?php if($p->applications_open): ?><span class="badge bg-info">Open</span><?php else: ?><span class="badge bg-warning text-dark">Closed</span><?php endif; ?>
                        </td>
                        <td class="text-end pe-3">
                            <a href="<?php echo e(route('admin.programmes.edit', $p)); ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form method="POST" action="<?php echo e(route('admin.programmes.destroy', $p)); ?>" class="d-inline" onsubmit="return confirm('Delete this programme?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">No programmes yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white"><?php echo e($programmes->links('pagination::bootstrap-4')); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\admin\programmes\index.blade.php ENDPATH**/ ?>