
<?php $__env->startSection('title', 'Supervision Meetings'); ?>
<?php $__env->startSection('page-title', 'Supervision Meetings'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">All recorded supervision meetings</p>
    <?php if(Auth::user()->hasRole('supervisor')): ?>
    <a href="<?php echo e(route('supervision.log')); ?>" class="btn btn-success">
        <i class="fas fa-plus me-2"></i>Log New Meeting
    </a>
    <?php endif; ?>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <h6 class="mb-0 fw-semibold">Meetings — <?php echo e($meetings->total()); ?> records</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="font-size:12px;">Date</th>
                    <?php if(!Auth::user()->hasRole('student')): ?>
                    <th style="font-size:12px;">Student</th>
                    <?php endif; ?>
                    <?php if(!Auth::user()->hasRole('supervisor')): ?>
                    <th style="font-size:12px;">Supervisor</th>
                    <?php endif; ?>
                    <th style="font-size:12px;">Agenda</th>
                    <th style="font-size:12px;">Minutes</th>
                    <th style="font-size:12px;">Signatures</th>
                    <?php if(Auth::user()->hasRole('student')): ?>
                    <th style="font-size:12px;">Action</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $meetings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meeting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><small><?php echo e(\Carbon\Carbon::parse($meeting->meeting_date)->format('d M Y')); ?></small></td>
                    <?php if(!Auth::user()->hasRole('student')): ?>
                    <td>
                        <div class="fw-medium" style="font-size:13px;"><?php echo e(optional(optional($meeting->student)->user)->name ?? 'N/A'); ?></div>
                        <div class="text-muted" style="font-size:11px;"><?php echo e(optional($meeting->student)->reg_no); ?></div>
                    </td>
                    <?php endif; ?>
                    <?php if(!Auth::user()->hasRole('supervisor')): ?>
                    <td style="font-size:13px;"><?php echo e(optional(optional($meeting->supervisor)->user)->name ?? 'N/A'); ?></td>
                    <?php endif; ?>
                    <td>
                        <div style="font-size:13px; max-width:220px;" class="text-truncate" title="<?php echo e($meeting->agenda); ?>"><?php echo e($meeting->agenda); ?></div>
                    </td>
                    <td>
                        <?php if($meeting->minutes): ?>
                            <button class="btn btn-sm btn-outline-secondary" type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#minutes-<?php echo e($meeting->id); ?>">
                                <i class="fas fa-eye"></i>
                            </button>
                        <?php else: ?>
                            <span class="text-muted small">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($meeting->signed_student && $meeting->signed_supervisor): ?>
                            <span class="badge bg-success"><i class="fas fa-check-double me-1"></i>Signed</span>
                        <?php elseif($meeting->signed_supervisor && !$meeting->signed_student): ?>
                            <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Awaiting Student</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Incomplete</span>
                        <?php endif; ?>
                    </td>
                    <?php if(Auth::user()->hasRole('student')): ?>
                    <td>
                        <?php if($meeting->signed_supervisor && !$meeting->signed_student): ?>
                        <form method="POST" action="<?php echo e(route('meetings.sign', $meeting)); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-sm btn-warning">Sign Off</button>
                        </form>
                        <?php endif; ?>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php if($meeting->minutes): ?>
                <tr class="collapse" id="minutes-<?php echo e($meeting->id); ?>">
                    <td colspan="7" class="bg-light">
                        <div class="p-3">
                            <strong class="small">Minutes:</strong>
                            <p class="mb-0 small text-muted mt-1"><?php echo e($meeting->minutes); ?></p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7" class="text-center text-muted py-4">No meetings recorded yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($meetings->hasPages()): ?>
    <div class="card-footer bg-white border-top"><?php echo e($meetings->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\supervision\meetings-list.blade.php ENDPATH**/ ?>