<?php $__env->startSection('title', 'Supervisor Dashboard'); ?>
<?php $__env->startSection('page-title', 'Supervisor Dashboard'); ?>

<?php $__env->startSection('content'); ?>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3"><i class="fas fa-user-graduate text-primary fa-lg"></i></div>
                <div>
                    <div class="fs-4 fw-bold"><?php echo e($theses->count()); ?></div>
                    <div class="text-muted small">Active Students</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-warning bg-opacity-10 p-3"><i class="fas fa-clipboard-list text-warning fa-lg"></i></div>
                <div>
                    <div class="fs-4 fw-bold"><?php echo e($pendingReviews->count()); ?></div>
                    <div class="text-muted small">Pending Reviews</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-info bg-opacity-10 p-3"><i class="fas fa-building text-info fa-lg"></i></div>
                <div>
                    <div class="fs-4 fw-bold"><?php echo e($supervisor->department); ?></div>
                    <div class="text-muted small">Department</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success bg-opacity-10 p-3"><i class="fas fa-users text-success fa-lg"></i></div>
                <div>
                    <div class="fs-4 fw-bold"><?php echo e($theses->count()); ?>/<?php echo e($supervisor->max_capacity); ?></div>
                    <div class="text-muted small">Capacity</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">My Students</h6>
                <a href="<?php echo e(route('supervision.log')); ?>" class="btn btn-sm btn-success">
                    <i class="fas fa-plus me-1"></i>Log Meeting
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="font-size:12px;">Student</th>
                            <th style="font-size:12px;">Stage</th>
                            <th style="font-size:12px;">Progress</th>
                            <th style="font-size:12px;">Meetings/Month</th>
                            <th style="font-size:12px;">Last Meeting</th>
                            <th style="font-size:12px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stageColors=['concept_development'=>'secondary','proposal_development'=>'info','ethical_approval'=>'warning','data_collection'=>'primary','writing_thesis'=>'info','intent_to_submit'=>'warning','submitted'=>'primary','examination'=>'warning','graduated'=>'success'];
                        ?>
                        <?php $__empty_1 = true; $__currentLoopData = $theses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $thesis): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div class="fw-medium" style="font-size:13px;"><?php echo e($thesis->student->user->name); ?></div>
                                <div class="text-muted" style="font-size:11px;"><?php echo e($thesis->student->reg_no); ?></div>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo e($stageColors[$thesis->current_stage] ?? 'secondary'); ?> stage-badge"><?php echo e($thesis->stage_label); ?></span>
                            </td>
                            <td style="min-width:110px;">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height:5px;">
                                        <div class="progress-bar bg-success" style="width:<?php echo e($thesis->progress_percentage); ?>%;"></div>
                                    </div>
                                    <small><?php echo e(number_format($thesis->progress_percentage, 0)); ?>%</small>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge <?php echo e($thesis->meetings_this_month >= 1 ? 'bg-success' : 'bg-danger'); ?>"><?php echo e($thesis->meetings_this_month); ?></span>
                                <?php if($thesis->meetings_this_month == 0): ?>
                                    <i class="fas fa-exclamation-triangle text-danger ms-1" title="No meeting this month"></i>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($thesis->last_meeting): ?>
                                    <small class="text-muted"><?php echo e(\Carbon\Carbon::parse($thesis->last_meeting->meeting_date)->format('d M Y')); ?></small>
                                <?php else: ?>
                                    <small class="text-muted">—</small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($thesis->pending_submissions > 0): ?>
                                <a href="<?php echo e(route('admin.thesis-reviews')); ?>" class="btn btn-sm btn-warning me-1" title="Pending reviews">
                                    <i class="fas fa-clipboard-check"></i>
                                    <span class="badge bg-dark"><?php echo e($thesis->pending_submissions); ?></span>
                                </a>
                                <?php endif; ?>
                                <a href="<?php echo e(route('progress-reports.create', $thesis)); ?>" class="btn btn-sm btn-outline-primary me-1" title="Submit progress report">
                                    <i class="fas fa-file-medical"></i>
                                </a>
                                <a href="<?php echo e(route('progress-reports.index', $thesis)); ?>" class="btn btn-sm btn-outline-secondary" title="View progress reports">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">No students assigned yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        
        <?php if($pendingReviews->count() > 0): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">Pending Reviews</h6>
                <a href="<?php echo e(route('admin.thesis-reviews')); ?>" class="btn btn-sm btn-warning">Review All</a>
            </div>
            <div class="card-body p-0">
                <?php $__currentLoopData = $pendingReviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="d-flex align-items-start gap-3 p-3 border-bottom">
                    <i class="fas fa-file-alt mt-1 text-warning"></i>
                    <div class="flex-grow-1">
                        <div class="fw-medium" style="font-size:13px;"><?php echo e($submission->thesis->student->user->name); ?></div>
                        <div class="text-muted" style="font-size:12px;"><?php echo e($submission->title); ?></div>
                        <small class="text-muted">Submitted: <?php echo e($submission->submitted_at->format('d M Y')); ?></small>
                    </div>
                    <a href="<?php echo e(route('admin.thesis-reviews')); ?>" class="btn btn-sm btn-outline-warning">Review</a>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold">Quick Links</h6>
            </div>
            <div class="list-group list-group-flush">
                <a href="<?php echo e(route('supervision.log')); ?>" class="list-group-item list-group-item-action d-flex align-items-center gap-2">
                    <i class="fas fa-calendar-plus text-success"></i>Log a Meeting
                </a>
                <a href="<?php echo e(route('supervision.meetings')); ?>" class="list-group-item list-group-item-action d-flex align-items-center gap-2">
                    <i class="fas fa-calendar-alt text-primary"></i>All Meetings
                </a>
                <a href="<?php echo e(route('admin.thesis-reviews')); ?>" class="list-group-item list-group-item-action d-flex align-items-center gap-2">
                    <i class="fas fa-clipboard-check text-warning"></i>Review Submissions
                    <?php if($pendingReviews->count() > 0): ?>
                        <span class="badge bg-warning text-dark ms-auto"><?php echo e($pendingReviews->count()); ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\supervisor\dashboard.blade.php ENDPATH**/ ?>