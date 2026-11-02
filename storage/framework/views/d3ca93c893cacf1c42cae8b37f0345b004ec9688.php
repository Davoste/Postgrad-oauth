
<?php $__env->startSection('title', 'Progress Reports'); ?>
<?php $__env->startSection('page-title', 'Progress Reports'); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-bottom py-3">
        <h6 class="mb-0 fw-semibold">My Students</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="font-size:12px;">Student</th>
                    <th style="font-size:12px;">Reports</th>
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
                    <td><span class="badge bg-secondary"><?php echo e($thesis->progress_reports_count); ?></span></td>
                    <td>
                        <a href="<?php echo e(route('progress-reports.create', $thesis)); ?>" class="btn btn-sm btn-outline-primary me-1">
                            <i class="fas fa-plus me-1"></i>New Report
                        </a>
                        <a href="<?php echo e(route('progress-reports.index', $thesis)); ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-eye me-1"></i>View
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="3" class="text-center text-muted py-4">No students assigned yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <h6 class="mb-0 fw-semibold">Submitted Reports — <?php echo e($reports->total()); ?> total</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="font-size:12px;">Student</th>
                    <th style="font-size:12px;">Period</th>
                    <th style="font-size:12px;">Type</th>
                    <th style="font-size:12px;">Rating</th>
                    <th style="font-size:12px;">Acknowledged</th>
                    <th style="font-size:12px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div class="fw-medium" style="font-size:13px;"><?php echo e($report->thesis->student->user->name); ?></div>
                        <div class="text-muted" style="font-size:11px;"><?php echo e($report->thesis->student->reg_no); ?></div>
                    </td>
                    <td style="font-size:13px;">
                        <?php echo e($report->period_start->format('d M Y')); ?> &ndash; <?php echo e($report->period_end->format('d M Y')); ?>

                    </td>
                    <td><span class="badge bg-<?php echo e($report->report_type === 'quarterly' ? 'info' : 'primary'); ?>"><?php echo e(ucfirst($report->report_type)); ?></span></td>
                    <td>
                        <span class="badge bg-<?php echo e($report->student_progress_rating >= 4 ? 'success' : ($report->student_progress_rating >= 3 ? 'warning' : 'danger')); ?>">
                            <?php echo e($report->student_progress_rating); ?>/5
                        </span>
                    </td>
                    <td>
                        <?php if($report->student_acknowledged_at): ?>
                            <span class="badge bg-success"><i class="fas fa-check me-1"></i><?php echo e($report->student_acknowledged_at->format('d M Y')); ?></span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Pending</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?php echo e(route('progress-reports.show', $report)); ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye me-1"></i>View
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="text-center text-muted py-5">No progress reports submitted yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($reports->hasPages()): ?>
    <div class="card-footer bg-white border-top"><?php echo e($reports->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\progress-reports\supervisor-index.blade.php ENDPATH**/ ?>