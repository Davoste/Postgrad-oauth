

<?php $__env->startSection('title', 'My Feedback'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1">My Feedback</h2>
                            <p class="text-muted mb-0">View all feedback and reviews on your thesis work</p>
                        </div>
                        <a href="<?php echo e(route('thesis.dashboard')); ?>" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback by Stage -->
    <?php if($feedbacks->count() > 0): ?>
        <?php $__currentLoopData = $feedbacks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage => $stageFeedbacks): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0"><?php echo e(ucfirst(str_replace('_', ' ', $stage))); ?></h5>
                    </div>
                    <div class="card-body">
                        <?php $__currentLoopData = $stageFeedbacks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feedback): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="card mb-3 <?php echo e($feedback->is_final ? 'border-primary' : ''); ?>">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <h6 class="mb-1">
                                                    Feedback from <?php echo e($feedback->reviewer->name); ?>

                                                    <?php if($feedback->is_final): ?>
                                                        <span class="badge bg-primary ms-2">Final Review</span>
                                                    <?php endif; ?>
                                                </h6>
                                                <small class="text-muted"><?php echo e($feedback->created_at->format('M d, Y H:i')); ?></small>
                                            </div>
                                            <span class="badge bg-<?php echo e($feedback->decision === 'approved' ? 'success' : ($feedback->decision === 'rejected' ? 'danger' : 'warning')); ?>">
                                                <?php echo e(ucfirst($feedback->decision)); ?>

                                            </span>
                                        </div>

                                        <div class="mb-3">
                                            <strong>Comments:</strong>
                                            <p class="mt-2"><?php echo e($feedback->comments); ?></p>
                                        </div>

                                        <?php if($feedback->action_items): ?>
                                        <div class="alert alert-info">
                                            <strong>Action Items:</strong>
                                            <ul class="mb-0 mt-2">
                                                <?php $__currentLoopData = json_decode($feedback->action_items); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li><?php echo e($item); ?></li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        </div>
                                        <?php endif; ?>

                                        <?php if($feedback->deadline): ?>
                                        <div class="alert alert-warning">
                                            <strong>Deadline:</strong> <?php echo e($feedback->deadline->format('M d, Y')); ?>

                                            <?php if($feedback->deadline->isPast()): ?>
                                                <span class="text-danger ms-2">(Overdue)</span>
                                            <?php else: ?>
                                                <span class="text-success ms-2">(<?php echo e($feedback->deadline->diffForHumans()); ?>)</span>
                                            <?php endif; ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="col-md-4">
                                        <?php if($feedback->submission): ?>
                                        <div class="border-start ps-3">
                                            <h6>Related Submission</h6>
                                            <p class="mb-1"><strong><?php echo e($feedback->submission->title); ?></strong></p>
                                            <small class="text-muted"><?php echo e(ucfirst(str_replace('_', ' ', $feedback->submission->submission_type))); ?></small><br>
                                            <small class="text-muted">Submitted: <?php echo e($feedback->submission->submitted_at->format('M d, Y')); ?></small>

                                            <?php if($feedback->submission->documents): ?>
                                            <div class="mt-2">
                                                <strong>Documents:</strong>
                                                <ul class="list-unstyled mt-1">
                                                    <?php $__currentLoopData = $feedback->submission->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li class="small mb-1">
                                                        <a href="<?php echo e(route('documents.download', [$feedback->submission, $i])); ?>" class="text-decoration-none">
                                                            <i class="fas fa-file-download me-1"></i><?php echo e($doc['original_name']); ?>

                                                        </a>
                                                    </li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                    <i class="fas fa-comments text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">No Feedback Yet</h5>
                    <p class="text-muted">You haven't received any feedback on your submissions yet.</p>
                    <a href="<?php echo e(route('thesis.dashboard')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Make a Submission
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\thesis\feedback.blade.php ENDPATH**/ ?>