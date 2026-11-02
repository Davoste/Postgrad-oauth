
<?php $__env->startSection('title', 'Reports'); ?>
<?php $__env->startSection('page-title', 'System Reports'); ?>

<?php $__env->startSection('content'); ?>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3"><i class="fas fa-user-graduate text-primary fa-lg"></i></div>
                <div>
                    <div class="fs-4 fw-bold"><?php echo e($data['total_students']); ?></div>
                    <div class="text-muted small">Total Students</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-info bg-opacity-10 p-3"><i class="fas fa-chalkboard-teacher text-info fa-lg"></i></div>
                <div>
                    <div class="fs-4 fw-bold"><?php echo e($data['total_supervisors']); ?></div>
                    <div class="text-muted small">Supervisors</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success bg-opacity-10 p-3"><i class="fas fa-graduation-cap text-success fa-lg"></i></div>
                <div>
                    <div class="fs-4 fw-bold"><?php echo e($data['graduated']); ?></div>
                    <div class="text-muted small">Graduated</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-danger bg-opacity-10 p-3"><i class="fas fa-exclamation-triangle text-danger fa-lg"></i></div>
                <div>
                    <div class="fs-4 fw-bold"><?php echo e($data['overdue_milestones']); ?></div>
                    <div class="text-muted small">Overdue Milestones</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-layer-group me-2 text-primary"></i>Students by Stage</h6>
            </div>
            <div class="card-body">
                <?php $__currentLoopData = $byStage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span style="font-size:13px;"><?php echo e($info['label']); ?></span>
                        <span class="badge bg-primary"><?php echo e($info['count']); ?></span>
                    </div>
                    <?php $pct = $data['total_students'] > 0 ? ($info['count'] / $data['total_students']) * 100 : 0; ?>
                    <div class="progress" style="height:5px;">
                        <div class="progress-bar bg-primary" style="width:<?php echo e($pct); ?>%;"></div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-users me-2 text-info"></i>Supervisor Workload</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="font-size:12px;">Supervisor</th>
                            <th style="font-size:12px;">Dept</th>
                            <th style="font-size:12px;">Active</th>
                            <th style="font-size:12px;">Capacity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $supervisorLoad; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td style="font-size:13px;"><?php echo e($sup->user->name); ?></td>
                            <td><small class="text-muted"><?php echo e($sup->department); ?></small></td>
                            <td>
                                <span class="badge <?php echo e($sup->active_students >= $sup->max_capacity ? 'bg-danger' : 'bg-success'); ?>">
                                    <?php echo e($sup->active_students); ?>

                                </span>
                            </td>
                            <td><small class="text-muted"><?php echo e($sup->max_capacity); ?></small></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="4" class="text-center text-muted py-3">No supervisors.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <h6 class="mb-0 fw-semibold"><i class="fas fa-calendar-check me-2 text-success"></i>Meeting Activity (Last 3 Months)</h6>
    </div>
    <div class="card-body">
        <?php if($meetingStats->count() > 0): ?>
        <div class="row">
            <?php $__currentLoopData = $meetingStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-4 mb-3">
                <div class="border rounded p-3 text-center">
                    <div class="fs-3 fw-bold text-success"><?php echo e($stat->total); ?></div>
                    <div class="text-muted small"><?php echo e(date('F Y', mktime(0,0,0,$stat->mo,1,$stat->yr))); ?></div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php else: ?>
        <p class="text-muted mb-0">No meeting data for the last 3 months.</p>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\admin\reports.blade.php ENDPATH**/ ?>