

<?php $__env->startSection('title', 'My Submissions'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1">My Submissions</h2>
                            <p class="text-muted mb-0">Track all your thesis submissions and their status</p>
                        </div>
                        <a href="<?php echo e(route('thesis.dashboard')); ?>" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Submissions by Stage -->
    <?php if($submissions->count() > 0): ?>
        <?php $__currentLoopData = $submissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage => $stageSubmissions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0"><?php echo e(ucfirst(str_replace('_', ' ', $stage))); ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Submitted</th>
                                        <th>Status</th>
                                        <th>Reviewed</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $stageSubmissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo e(Str::limit($submission->title, 50)); ?></strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-info"><?php echo e(ucfirst(str_replace('_', ' ', $submission->submission_type))); ?></span>
                                        </td>
                                        <td><?php echo e($submission->submitted_at->format('M d, Y')); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo e($submission->status === 'approved' ? 'success' : ($submission->status === 'rejected' ? 'danger' : 'warning')); ?>">
                                                <?php echo e(ucfirst(str_replace('_', ' ', $submission->status))); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <?php if($submission->reviewed_at): ?>
                                                <?php echo e($submission->reviewed_at->format('M d, Y')); ?>

                                            <?php else: ?>
                                                <span class="text-muted">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                        data-bs-target="#detailsModal<?php echo e($submission->id); ?>">
                                                    <i class="fas fa-eye"></i> Details
                                                </button>
                                                <?php if($submission->documents): ?>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                        <i class="fas fa-download"></i> Files
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <?php $__currentLoopData = $submission->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li>
                                                            <a class="dropdown-item" href="<?php echo e(route('documents.download', [$submission, $i])); ?>">
                                                                <i class="fas fa-file me-1"></i><?php echo e($doc['original_name']); ?>

                                                            </a>
                                                        </li>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </ul>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
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
                    <i class="fas fa-file-alt text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">No Submissions Yet</h5>
                    <p class="text-muted">You haven't made any thesis submissions yet.</p>
                    <a href="<?php echo e(route('thesis.dashboard')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Make Your First Submission
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Details Modals -->
<?php $__currentLoopData = $submissions->flatten(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="detailsModal<?php echo e($submission->id); ?>" tabindex="-1" aria-labelledby="detailsModalLabel<?php echo e($submission->id); ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalLabel<?php echo e($submission->id); ?>">Submission Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Submission Information</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Title:</strong></td>
                                <td><?php echo e($submission->title); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Stage:</strong></td>
                                <td><?php echo e(ucfirst(str_replace('_', ' ', $submission->stage))); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Type:</strong></td>
                                <td><?php echo e(ucfirst(str_replace('_', ' ', $submission->submission_type))); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="badge bg-<?php echo e($submission->status === 'approved' ? 'success' : ($submission->status === 'rejected' ? 'danger' : 'warning')); ?>">
                                        <?php echo e(ucfirst(str_replace('_', ' ', $submission->status))); ?>

                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Submitted:</strong></td>
                                <td><?php echo e($submission->submitted_at->format('M d, Y H:i')); ?></td>
                            </tr>
                            <?php if($submission->reviewed_at): ?>
                            <tr>
                                <td><strong>Reviewed:</strong></td>
                                <td><?php echo e($submission->reviewed_at->format('M d, Y H:i')); ?></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Description</h6>
                        <div class="border p-3 rounded" style="max-height: 200px; overflow-y: auto;">
                            <?php echo e($submission->description ?? 'No description provided.'); ?>

                        </div>

                        <?php if($submission->documents): ?>
                        <h6 class="mt-3">Documents</h6>
                        <ul class="list-group">
                            <?php $__currentLoopData = $submission->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-file me-1 text-muted"></i><?php echo e($doc['original_name']); ?>

                                    <small class="text-muted ms-2"><?php echo e(number_format($doc['size'] / 1024, 1)); ?> KB</small>
                                </div>
                                <a href="<?php echo e(route('documents.download', [$submission, $i])); ?>" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-download"></i>
                                </a>
                            </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Feedback Section -->
                <?php if($submission->feedbacks->count() > 0): ?>
                <div class="row mt-4">
                    <div class="col-12">
                        <h6>Feedback & Reviews</h6>
                        <?php $__currentLoopData = $submission->feedbacks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feedback): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <strong><?php echo e($feedback->reviewer->name); ?></strong>
                                        <small class="text-muted ms-2"><?php echo e($feedback->created_at->format('M d, Y')); ?></small>
                                    </div>
                                    <span class="badge bg-<?php echo e($feedback->decision === 'approved' ? 'success' : ($feedback->decision === 'rejected' ? 'danger' : 'warning')); ?>">
                                        <?php echo e(ucfirst($feedback->decision)); ?>

                                    </span>
                                </div>
                                <p class="mb-2"><?php echo e($feedback->comments); ?></p>
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
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Reviews Section -->
                <?php if($submission->reviews->count() > 0): ?>
                <div class="row mt-4">
                    <div class="col-12">
                        <h6>Formal Reviews</h6>
                        <?php $__currentLoopData = $submission->reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <strong><?php echo e($review->reviewer->name); ?></strong>
                                        <small class="text-muted ms-2"><?php echo e($review->reviewed_at->format('M d, Y')); ?></small>
                                        <span class="badge bg-secondary ms-2"><?php echo e(ucfirst(str_replace('_', ' ', $review->review_type))); ?></span>
                                    </div>
                                    <?php if($review->rating): ?>
                                    <div class="text-end">
                                        <div class="rating">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-star <?php echo e($i <= $review->rating ? 'text-warning' : 'text-muted'); ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <small class="text-muted">Rating: <?php echo e($review->rating); ?>/5</small>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <?php if($review->strengths): ?>
                                <div class="mb-2">
                                    <strong>Strengths:</strong>
                                    <p class="mb-1"><?php echo e($review->strengths); ?></p>
                                </div>
                                <?php endif; ?>

                                <?php if($review->weaknesses): ?>
                                <div class="mb-2">
                                    <strong>Areas for Improvement:</strong>
                                    <p class="mb-1"><?php echo e($review->weaknesses); ?></p>
                                </div>
                                <?php endif; ?>

                                <div class="mb-2">
                                    <strong>Recommendations:</strong>
                                    <p class="mb-1"><?php echo e($review->recommendations); ?></p>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-<?php echo e($review->decision === 'approved' ? 'success' : ($review->decision === 'rejected' ? 'danger' : 'warning')); ?>">
                                        <?php echo e(ucfirst(str_replace('_', ' ', $review->decision))); ?>

                                    </span>
                                    <?php if($review->requires_meeting): ?>
                                    <small class="text-info"><i class="fas fa-calendar me-1"></i>Meeting Required</small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\thesis\submissions.blade.php ENDPATH**/ ?>