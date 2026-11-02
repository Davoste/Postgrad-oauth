
<?php $__env->startSection('title', 'All Theses'); ?>
<?php $__env->startSection('page-title', 'Thesis Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-medium">Stage</label>
                <select name="stage" class="form-select form-select-sm">
                    <option value="">All Stages</option>
                    <?php $__currentLoopData = $stages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php echo e(request('stage') == $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-medium">Supervisor</label>
                <select name="supervisor_id" class="form-select form-select-sm">
                    <option value="">All Supervisors</option>
                    <?php $__currentLoopData = $supervisors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($sup->id); ?>" <?php echo e(request('supervisor_id') == $sup->id ? 'selected' : ''); ?>><?php echo e($sup->user->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-sm btn-primary me-2"><i class="fas fa-filter me-1"></i>Filter</button>
                <a href="<?php echo e(route('admin.theses')); ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <h6 class="mb-0 fw-semibold">Theses — <?php echo e($theses->total()); ?> records</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="font-size:12px;">Student</th>
                    <th style="font-size:12px;">Thesis Title</th>
                    <th style="font-size:12px;">Supervisor</th>
                    <th style="font-size:12px;">Stage</th>
                    <th style="font-size:12px;">Progress</th>
                    <th style="font-size:12px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $theses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $thesis): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div class="fw-medium" style="font-size:13px;"><?php echo e($thesis->student->user->name); ?></div>
                        <div class="text-muted" style="font-size:11px;"><?php echo e($thesis->student->reg_no); ?></div>
                    </td>
                    <td>
                        <div style="font-size:13px; max-width:220px;" class="text-truncate" title="<?php echo e($thesis->title); ?>"><?php echo e($thesis->title); ?></div>
                    </td>
                    <td>
                        <?php if($thesis->supervisor): ?>
                            <span style="font-size:13px;"><?php echo e($thesis->supervisor->user->name); ?></span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark">Unassigned</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php
                        $stageColors=['concept_development'=>'secondary','proposal_development'=>'info','ethical_approval'=>'warning','data_collection'=>'primary','writing_thesis'=>'info','intent_to_submit'=>'warning','submitted'=>'primary','examination'=>'warning','graduated'=>'success'];
                        ?>
                        <span class="badge bg-<?php echo e($stageColors[$thesis->current_stage] ?? 'secondary'); ?> stage-badge">
                            <?php echo e($thesis->stage_label); ?>

                        </span>
                    </td>
                    <td style="min-width:120px;">
                        <div class="d-flex align-items-center gap-2">
                            <div class="progress flex-grow-1" style="height:6px;">
                                <div class="progress-bar bg-success" style="width:<?php echo e($thesis->progress_percentage); ?>%;"></div>
                            </div>
                            <small><?php echo e(number_format($thesis->progress_percentage, 0)); ?>%</small>
                        </div>
                    </td>
                    <td>
                        <a href="<?php echo e(route('admin.thesis.detail', $thesis)); ?>" class="btn btn-sm btn-outline-primary me-1" title="View detail"><i class="fas fa-eye"></i></a>
                        <?php if(!$thesis->supervisor_id): ?>
                        <a href="<?php echo e(route('admin.thesis.allocate-supervisor.form', $thesis)); ?>" class="btn btn-sm btn-outline-warning" title="Assign supervisor"><i class="fas fa-user-plus"></i></a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">No theses found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($theses->hasPages()): ?>
    <div class="card-footer bg-white border-top"><?php echo e($theses->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\admin\theses.blade.php ENDPATH**/ ?>