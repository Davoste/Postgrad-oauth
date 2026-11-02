<?php $__env->startSection('title', 'Progress Reports'); ?>
<?php $__env->startSection('page-title', 'Progress Reports'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p class="text-muted mb-0 small"><?php echo e($thesis->title); ?></p>
    </div>
    <?php if(Auth::user()->hasRole('supervisor')): ?>
    <a href="<?php echo e(route('progress-reports.create', $thesis)); ?>" class="btn btn-success">
        <i class="fas fa-plus me-2"></i>New Report
    </a>
    <?php endif; ?>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <h6 class="mb-0 fw-semibold">Progress Reports — <?php echo e($reports->count()); ?> total</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="font-size:12px;">Period</th>
                    <th style="font-size:12px;">Type</th>
                    <th style="font-size:12px;">Supervisor</th>
                    <th style="font-size:12px;">Rating</th>
                    <th style="font-size:12px;">Student Acknowledged</th>
                    <th style="font-size:12px;">Submitted</th>
                    <th style="font-size:12px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="font-size:13px;">
                        <?php echo e(\Carbon\Carbon::parse($report->period_start)->format('d M Y')); ?>

                        &ndash;
                        <?php echo e(\Carbon\Carbon::parse($report->period_end)->format('d M Y')); ?>

                    </td>
                    <td>
                        <span class="badge bg-<?php echo e($report->report_type === 'quarterly' ? 'info' : 'primary'); ?>">
                            <?php echo e(ucfirst($report->report_type)); ?>

                        </span>
                    </td>
                    <td style="font-size:13px;"><?php echo e(optional($report->supervisor)->user ? $report->supervisor->user->name : '—'); ?></td>
                    <td>
                        <?php $r = $report->student_progress_rating; ?>
                        <span class="badge bg-<?php echo e($r >= 4 ? 'success' : ($r >= 3 ? 'warning' : 'danger')); ?>">
                            <?php echo e($r); ?>/5
                        </span>
                    </td>
                    <td>
                        <?php if($report->student_acknowledged_at): ?>
                            <span class="badge bg-success"><i class="fas fa-check me-1"></i><?php echo e($report->student_acknowledged_at->format('d M Y')); ?></span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Pending</span>
                        <?php endif; ?>
                    </td>
                    <td><small class="text-muted"><?php echo e($report->submitted_at->format('d M Y')); ?></small></td>
                    <td>
                        <a href="<?php echo e(route('progress-reports.show', $report)); ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye me-1"></i>View
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="fas fa-file-alt d-block mb-2 text-muted" style="font-size:2rem;"></i>
                        No progress reports submitted yet.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\progress-reports\index.blade.php ENDPATH**/ ?>