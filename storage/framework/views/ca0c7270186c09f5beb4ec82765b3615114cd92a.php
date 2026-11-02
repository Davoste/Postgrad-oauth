

<?php $__env->startSection('title', 'My Milestones'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1">My Milestones</h2>
                            <p class="text-muted mb-0">Track your thesis milestones and deadlines</p>
                        </div>
                        <a href="<?php echo e(route('thesis.dashboard')); ?>" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Milestones by Stage -->
    <?php if($milestones->count() > 0): ?>
        <?php $__currentLoopData = $milestones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage => $stageMilestones): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0"><?php echo e(ucfirst(str_replace('_', ' ', $stage))); ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php $__currentLoopData = $stageMilestones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $milestone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 <?php echo e($milestone->status === 'completed' ? 'border-success' : ($milestone->status === 'overdue' ? 'border-danger' : 'border-warning')); ?>">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title mb-0"><?php echo e($milestone->milestone_name); ?></h6>
                                            <span class="badge bg-<?php echo e($milestone->status === 'completed' ? 'success' : ($milestone->status === 'overdue' ? 'danger' : 'warning')); ?>">
                                                <?php echo e(ucfirst($milestone->status)); ?>

                                            </span>
                                        </div>

                                        <?php if($milestone->description): ?>
                                        <p class="card-text text-muted small mb-3"><?php echo e($milestone->description); ?></p>
                                        <?php endif; ?>

                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <small class="text-muted">Progress</small>
                                                <small class="text-muted"><?php echo e($milestone->progress_percentage); ?>%</small>
                                            </div>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar <?php echo e($milestone->progress_percentage >= 100 ? 'bg-success' : 'bg-primary'); ?>"
                                                     style="width: <?php echo e($milestone->progress_percentage); ?>%"></div>
                                            </div>
                                        </div>

                                        <div class="row text-center">
                                            <div class="col-6">
                                                <small class="text-muted d-block">Target Date</small>
                                                <strong class="<?php echo e($milestone->target_date->isPast() && $milestone->status !== 'completed' ? 'text-danger' : 'text-dark'); ?>">
                                                    <?php echo e($milestone->target_date->format('M d, Y')); ?>

                                                </strong>
                                            </div>
                                            <div class="col-6">
                                                <?php if($milestone->completed_date): ?>
                                                <small class="text-muted d-block">Completed</small>
                                                <strong class="text-success"><?php echo e($milestone->completed_date->format('M d, Y')); ?></strong>
                                                <?php else: ?>
                                                <small class="text-muted d-block">Days Left</small>
                                                <strong class="<?php echo e($milestone->target_date->isPast() ? 'text-danger' : 'text-primary'); ?>">
                                                    <?php if($milestone->target_date->isPast()): ?>
                                                        Overdue
                                                    <?php else: ?>
                                                        <?php echo e($milestone->target_date->diffInDays(now())); ?> days
                                                    <?php endif; ?>
                                                </strong>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <?php if($milestone->checklist_items): ?>
                                        <div class="mt-3">
                                            <small class="text-muted d-block mb-2">Checklist:</small>
                                            <ul class="list-unstyled small">
                                                <?php $__currentLoopData = json_decode($milestone->checklist_items); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li><i class="fas fa-check-circle text-success me-1"></i><?php echo e($item); ?></li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php else: ?>
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-tasks text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">No Milestones Set</h5>
                    <p class="text-muted">Your thesis milestones will be set once you have a supervisor assigned.</p>
                    <a href="<?php echo e(route('thesis.dashboard')); ?>" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Progress Summary -->
    <?php if($milestones->count() > 0): ?>
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Progress Summary</h5>
                </div>
                <div class="card-body">
                    <?php
                        $totalMilestones = $milestones->flatten()->count();
                        $completedMilestones = $milestones->flatten()->where('status', 'completed')->count();
                        $overdueMilestones = $milestones->flatten()->where('status', 'overdue')->count();
                        $upcomingMilestones = $milestones->flatten()->where('status', 'pending')->where('target_date', '>', now())->count();
                        $overallProgress = $totalMilestones > 0 ? round(($completedMilestones / $totalMilestones) * 100, 1) : 0;
                    ?>

                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="p-3">
                                <h3 class="text-primary"><?php echo e($completedMilestones); ?></h3>
                                <p class="text-muted mb-0">Completed</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3">
                                <h3 class="text-warning"><?php echo e($totalMilestones - $completedMilestones - $overdueMilestones); ?></h3>
                                <p class="text-muted mb-0">In Progress</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3">
                                <h3 class="text-info"><?php echo e($upcomingMilestones); ?></h3>
                                <p class="text-muted mb-0">Upcoming</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3">
                                <h3 class="text-danger"><?php echo e($overdueMilestones); ?></h3>
                                <p class="text-muted mb-0">Overdue</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Overall Progress</span>
                            <span><?php echo e($overallProgress); ?>%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" style="width: <?php echo e($overallProgress); ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\thesis\milestones.blade.php ENDPATH**/ ?>