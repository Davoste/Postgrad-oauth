
<?php $__env->startSection('title', 'Admin Dashboard'); ?>
<?php $__env->startSection('page-title', 'Admin Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-4 mb-4">
    
    <?php
    $cards = [
        ['label'=>'Total Users',       'value'=>$stats['total_users'],       'icon'=>'fa-users',            'color'=>'primary',  'bg'=>'rgba(59,130,246,.1)'],
        ['label'=>'Students',           'value'=>$stats['total_students'],    'icon'=>'fa-user-graduate',    'color'=>'success',  'bg'=>'rgba(34,197,94,.1)'],
        ['label'=>'Supervisors',        'value'=>$stats['total_supervisors'], 'icon'=>'fa-chalkboard-teacher','color'=>'info',    'bg'=>'rgba(6,182,212,.1)'],
        ['label'=>'Active Theses',      'value'=>$stats['active_theses'],     'icon'=>'fa-book-open',        'color'=>'warning',  'bg'=>'rgba(251,191,36,.1)'],
        ['label'=>'Graduated',          'value'=>$stats['graduated'],         'icon'=>'fa-graduation-cap',   'color'=>'success',  'bg'=>'rgba(34,197,94,.1)'],
        ['label'=>'Meetings This Month','value'=>$stats['meetings_this_month'],'icon'=>'fa-calendar-check', 'color'=>'secondary','bg'=>'rgba(107,114,128,.1)'],
    ];
    ?>

    <?php $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="icon-wrap" style="background: <?php echo e($card['bg']); ?>; color: var(--bs-<?php echo e($card['color']); ?>);">
                    <i class="fas <?php echo e($card['icon']); ?>"></i>
                </div>
                <div>
                    <div class="fw-bold fs-4 lh-1"><?php echo e($card['value']); ?></div>
                    <div class="text-muted" style="font-size:12px;"><?php echo e($card['label']); ?></div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<div class="row g-4">
    
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                <h6 class="mb-0 fw-semibold">Students by Stage</h6>
                <a href="<?php echo e(route('admin.monitor')); ?>" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                <?php $__currentLoopData = $byStage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small class="fw-medium"><?php echo e($info['label']); ?></small>
                        <small class="text-muted"><?php echo e($info['count']); ?></small>
                    </div>
                    <?php $pct = $stats['total_students'] > 0 ? round($info['count'] / $stats['total_students'] * 100) : 0; ?>
                    <div class="progress" style="height:8px; border-radius:4px;">
                        <div class="progress-bar" role="progressbar" style="width:<?php echo e($pct); ?>%; background:#1a3c6e;"></div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold">Quick Actions</h6>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-primary"><i class="fas fa-user-plus me-2"></i>Add New User</a>
                <a href="<?php echo e(route('admin.monitor')); ?>"      class="btn btn-outline-secondary"><i class="fas fa-eye me-2"></i>Monitor All Students</a>
                <a href="<?php echo e(route('admin.thesis-reviews')); ?>" class="btn btn-outline-warning"><i class="fas fa-tasks me-2"></i>Pending Thesis Reviews</a>
                <a href="<?php echo e(route('admin.reports')); ?>"      class="btn btn-outline-info"><i class="fas fa-chart-bar me-2"></i>Generate Reports</a>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold">Recent Activity</h6>
            </div>
            <div class="card-body p-0">
                <?php $__empty_1 = true; $__currentLoopData = $recentActivity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="px-3 py-2 border-bottom d-flex gap-2 align-items-start">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width:32px;height:32px;">
                        <i class="fas fa-bolt text-primary" style="font-size:12px;"></i>
                    </div>
                    <div>
                        <div style="font-size:13px;"><?php echo e($activity->description); ?></div>
                        <div class="text-muted" style="font-size:11px;">
                            <?php echo e(optional($activity->causer)->name ?? 'System'); ?> &bull; <?php echo e($activity->created_at->diffForHumans()); ?>

                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-muted text-center py-3 mb-0" style="font-size:13px;">No recent activity.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\admin\dashboard.blade.php ENDPATH**/ ?>