
<?php $__env->startSection('title', 'Supervisions'); ?>
<?php $__env->startSection('page-title', 'Supervision Meetings'); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-medium">Supervisor</label>
                <select name="supervisor_id" class="form-select form-select-sm">
                    <option value="">All Supervisors</option>
                    <?php $__currentLoopData = $supervisors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($sup->id); ?>" <?php echo e(request('supervisor_id') == $sup->id ? 'selected' : ''); ?>><?php echo e($sup->user->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-medium">Month</label>
                <select name="month" class="form-select form-select-sm">
                    <option value="">All Months</option>
                    <?php for($m = 1; $m <= 12; $m++): ?>
                        <option value="<?php echo e($m); ?>" <?php echo e(request('month') == $m ? 'selected' : ''); ?>><?php echo e(date('F', mktime(0,0,0,$m,1))); ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-medium">Year</label>
                <select name="year" class="form-select form-select-sm">
                    <option value="">All Years</option>
                    <?php for($y = now()->year; $y >= now()->year - 3; $y--): ?>
                        <option value="<?php echo e($y); ?>" <?php echo e(request('year') == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-sm btn-primary me-2"><i class="fas fa-filter me-1"></i>Filter</button>
                <a href="<?php echo e(route('admin.supervisions')); ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <h6 class="mb-0 fw-semibold">Meetings — <?php echo e($meetings->total()); ?> records</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="font-size:12px;">Student</th>
                    <th style="font-size:12px;">Supervisor</th>
                    <th style="font-size:12px;">Date</th>
                    <th style="font-size:12px;">Agenda</th>
                    <th style="font-size:12px;">Minutes</th>
                    <th style="font-size:12px;">Signatures</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $meetings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meeting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div class="fw-medium" style="font-size:13px;"><?php echo e(optional(optional($meeting->student)->user)->name ?? 'N/A'); ?></div>
                        <div class="text-muted" style="font-size:11px;"><?php echo e(optional($meeting->student)->reg_no); ?></div>
                    </td>
                    <td><span style="font-size:13px;"><?php echo e(optional(optional($meeting->supervisor)->user)->name ?? 'N/A'); ?></span></td>
                    <td><small><?php echo e(\Carbon\Carbon::parse($meeting->meeting_date)->format('d M Y')); ?></small></td>
                    <td>
                        <div style="font-size:13px; max-width:200px;" class="text-truncate" title="<?php echo e($meeting->agenda); ?>"><?php echo e($meeting->agenda); ?></div>
                    </td>
                    <td>
                        <?php if($meeting->minutes): ?>
                            <span class="badge bg-success">Logged</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Pending</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($meeting->signed_student && $meeting->signed_supervisor): ?>
                            <span class="badge bg-success"><i class="fas fa-check-double me-1"></i>Fully Signed</span>
                        <?php elseif($meeting->signed_supervisor && !$meeting->signed_student): ?>
                            <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Awaiting Student</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Incomplete</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">No meetings found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($meetings->hasPages()): ?>
    <div class="card-footer bg-white border-top"><?php echo e($meetings->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\admin\supervisions.blade.php ENDPATH**/ ?>