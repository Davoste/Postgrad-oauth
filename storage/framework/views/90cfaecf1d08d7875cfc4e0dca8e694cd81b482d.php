<?php $__env->startSection('title', 'Ethics Reviews'); ?>
<?php $__env->startSection('page-title', 'Ethics Review Queue'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Manage ethics applications submitted for committee review</p>
    <div class="d-flex gap-2">
        <span class="badge bg-warning text-dark fs-6"><?php echo e($pending->count()); ?> pending</span>
        <span class="badge bg-secondary fs-6"><?php echo e($reviewed->count()); ?> reviewed</span>
    </div>
</div>

<ul class="nav nav-tabs mb-0" id="ethicsTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending-pane" type="button" role="tab">
            Pending
            <?php if($pending->count()): ?>
            <span class="badge bg-warning text-dark ms-1"><?php echo e($pending->count()); ?></span>
            <?php endif; ?>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="reviewed-tab" data-bs-toggle="tab" data-bs-target="#reviewed-pane" type="button" role="tab">
            Reviewed
            <?php if($reviewed->count()): ?>
            <span class="badge bg-secondary ms-1"><?php echo e($reviewed->count()); ?></span>
            <?php endif; ?>
        </button>
    </li>
</ul>

<div class="tab-content">

    
    <div class="tab-pane fade show active" id="pending-pane" role="tabpanel">
        <div class="card border-0 shadow-sm border-top-0" style="border-radius:0 0 .5rem .5rem;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="font-size:12px;">Student</th>
                            <th style="font-size:12px;">Thesis Title</th>
                            <th style="font-size:12px;">Supervisor</th>
                            <th style="font-size:12px;">Submitted</th>
                            <th style="font-size:12px;">Status</th>
                            <th style="font-size:12px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $pending; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div class="fw-medium" style="font-size:13px;"><?php echo e($submission->thesis->student->user->name); ?></div>
                                <div class="text-muted" style="font-size:11px;"><?php echo e($submission->thesis->student->reg_no ?? $submission->thesis->student->student_id ?? ''); ?></div>
                            </td>
                            <td>
                                <div style="font-size:13px; max-width:250px;" class="text-truncate" title="<?php echo e($submission->thesis->title); ?>">
                                    <?php echo e($submission->thesis->title); ?>

                                </div>
                            </td>
                            <td style="font-size:13px;"><?php echo e(optional($submission->thesis->supervisor)->user ? $submission->thesis->supervisor->user->name : '—'); ?></td>
                            <td><small class="text-muted"><?php echo e($submission->submitted_at->format('d M Y')); ?></small></td>
                            <td>
                                <?php if($submission->status === 'submitted'): ?>
                                    <span class="badge bg-warning text-dark">Submitted</span>
                                <?php elseif($submission->status === 'under_review'): ?>
                                    <span class="badge bg-info">Under Review</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?php echo e(ucfirst(str_replace('_', ' ', $submission->status))); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo e(route('ethics.show', $submission)); ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-search me-1"></i>Review
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="fas fa-check-circle text-success d-block mb-2" style="font-size:2rem;"></i>
                                No pending ethics submissions.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    <div class="tab-pane fade" id="reviewed-pane" role="tabpanel">
        <div class="card border-0 shadow-sm border-top-0" style="border-radius:0 0 .5rem .5rem;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="font-size:12px;">Student</th>
                            <th style="font-size:12px;">Thesis Title</th>
                            <th style="font-size:12px;">Supervisor</th>
                            <th style="font-size:12px;">Reviewed</th>
                            <th style="font-size:12px;">Decision</th>
                            <th style="font-size:12px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $reviewed; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div class="fw-medium" style="font-size:13px;"><?php echo e($submission->thesis->student->user->name); ?></div>
                                <div class="text-muted" style="font-size:11px;"><?php echo e($submission->thesis->student->reg_no ?? $submission->thesis->student->student_id ?? ''); ?></div>
                            </td>
                            <td>
                                <div style="font-size:13px; max-width:250px;" class="text-truncate" title="<?php echo e($submission->thesis->title); ?>">
                                    <?php echo e($submission->thesis->title); ?>

                                </div>
                            </td>
                            <td style="font-size:13px;"><?php echo e(optional($submission->thesis->supervisor)->user ? $submission->thesis->supervisor->user->name : '—'); ?></td>
                            <td><small class="text-muted"><?php echo e(optional($submission->reviewed_at)->format('d M Y') ?? '—'); ?></small></td>
                            <td>
                                <?php if($submission->status === 'approved'): ?>
                                    <span class="badge bg-success">Approved</span>
                                <?php elseif($submission->status === 'rejected'): ?>
                                    <span class="badge bg-danger">Rejected</span>
                                <?php elseif($submission->status === 'revision_required'): ?>
                                    <span class="badge bg-warning text-dark">Revision Required</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?php echo e(ucfirst(str_replace('_', ' ', $submission->status))); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo e(route('ethics.show', $submission)); ?>" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-eye me-1"></i>View
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="fas fa-inbox d-block mb-2 text-muted" style="font-size:2rem;"></i>
                                No reviewed submissions yet.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\ethics\pending.blade.php ENDPATH**/ ?>