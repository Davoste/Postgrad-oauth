

<?php $__env->startSection('title', 'Thesis Reviews - Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1">Thesis Reviews</h2>
                            <p class="text-muted mb-0">Review and manage thesis submissions</p>
                        </div>
                        <div class="d-flex gap-2">
                            <select class="form-select" id="statusFilter" style="width: auto;">
                                <option value="">All Status</option>
                                <option value="submitted">Submitted</option>
                                <option value="under_review">Under Review</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                                <option value="revision_required">Revision Required</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Submissions List -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Pending Reviews</h5>
                </div>
                <div class="card-body">
                    <?php if($pendingSubmissions->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Thesis Title</th>
                                    <th>Stage</th>
                                    <th>Type</th>
                                    <th>Submitted</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $pendingSubmissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <div>
                                            <strong><?php echo e($submission->thesis->student->user->name); ?></strong><br>
                                            <small class="text-muted"><?php echo e($submission->thesis->student->student_id); ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong><?php echo e(Str::limit($submission->title, 50)); ?></strong><br>
                                            <small class="text-muted"><?php echo e($submission->thesis->supervisor ? $submission->thesis->supervisor->user->name : 'No Supervisor'); ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary"><?php echo e(ucfirst(str_replace('_', ' ', $submission->stage))); ?></span>
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
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                    data-bs-target="#reviewModal<?php echo e($submission->id); ?>">
                                                <i class="fas fa-eye"></i> Review
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
                    <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">No Pending Reviews</h5>
                        <p class="text-muted">All thesis submissions have been reviewed.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Review Modals -->
<?php $__currentLoopData = $pendingSubmissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="reviewModal<?php echo e($submission->id); ?>" tabindex="-1" aria-labelledby="reviewModalLabel<?php echo e($submission->id); ?>" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel<?php echo e($submission->id); ?>">Review Submission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('admin.thesis.review', $submission)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <!-- Submission Details -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Submission Details</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Student:</strong></td>
                                    <td><?php echo e($submission->thesis->student->user->name); ?></td>
                                </tr>
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
                                    <td><strong>Submitted:</strong></td>
                                    <td><?php echo e($submission->submitted_at->format('M d, Y H:i')); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Description</h6>
                            <div class="border p-3 rounded" style="max-height: 200px; overflow-y: auto;">
                                <?php echo e($submission->description); ?>

                            </div>

                            <?php if($submission->documents): ?>
                            <h6 class="mt-3">Documents</h6>
                            <ul class="list-group list-group-flush">
                                <?php $__currentLoopData = $submission->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <i class="fas fa-file me-1 text-muted"></i>
                                        <span class="small"><?php echo e($doc['original_name']); ?></span>
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

                    <!-- Review Form -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Decision <span class="text-danger">*</span></label>
                                <select class="form-select" name="decision" required>
                                    <option value="">Select decision</option>
                                    <option value="approved">Approve</option>
                                    <option value="rejected">Reject</option>
                                    <option value="revision_required">Revision Required</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Rating (1-5)</label>
                                <select class="form-select" name="rating">
                                    <option value="">Select rating</option>
                                    <option value="1">1 - Poor</option>
                                    <option value="2">2 - Below Average</option>
                                    <option value="3">3 - Average</option>
                                    <option value="4">4 - Good</option>
                                    <option value="5">5 - Excellent</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="requires_meeting" id="requiresMeeting<?php echo e($submission->id); ?>">
                                    <label class="form-check-label" for="requiresMeeting<?php echo e($submission->id); ?>">
                                        Requires follow-up meeting
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Comments <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="comments" rows="4" required
                                          placeholder="Provide detailed feedback on the submission"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Strengths</label>
                                <textarea class="form-control" name="strengths" rows="2"
                                          placeholder="What are the strengths of this submission?"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Areas for Improvement</label>
                                <textarea class="form-control" name="weaknesses" rows="2"
                                          placeholder="What areas need improvement?"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Recommendations <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="recommendations" rows="3" required
                                          placeholder="Provide specific recommendations for the student"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Review</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php $__env->stopSection(); ?>

<script>
document.getElementById('statusFilter').addEventListener('change', function() {
    const status = this.value;
    const rows = document.querySelectorAll('tbody tr');

    rows.forEach(row => {
        if (!status || row.querySelector('.badge').textContent.toLowerCase().includes(status.toLowerCase())) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\admin\thesis-reviews.blade.php ENDPATH**/ ?>