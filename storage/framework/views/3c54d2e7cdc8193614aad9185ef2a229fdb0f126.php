<?php $__env->startSection('title', 'Ethics Review'); ?>
<?php $__env->startSection('page-title', 'Ethics Review'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
<div class="col-xl-10">


<div class="d-flex align-items-center gap-2 mb-4">
    <a href="<?php echo e(route('ethics.pending')); ?>" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i></a>
    <h6 class="mb-0 fw-semibold">Ethics Application Review</h6>
</div>

<div class="row g-4">

    
    <div class="col-lg-6">

        
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-user-graduate me-2 text-primary"></i>Student &amp; Thesis</h6>
            </div>
            <div class="card-body">
                <dl class="row mb-0" style="font-size:13px;">
                    <dt class="col-sm-4 text-muted">Student</dt>
                    <dd class="col-sm-8 fw-medium"><?php echo e($submission->thesis->student->user->name); ?></dd>

                    <dt class="col-sm-4 text-muted">Reg. No.</dt>
                    <dd class="col-sm-8"><?php echo e($submission->thesis->student->reg_no ?? $submission->thesis->student->student_id ?? '—'); ?></dd>

                    <dt class="col-sm-4 text-muted">Supervisor</dt>
                    <dd class="col-sm-8"><?php echo e(optional($submission->thesis->supervisor)->user ? $submission->thesis->supervisor->user->name : 'Not assigned'); ?></dd>

                    <dt class="col-sm-4 text-muted">Thesis Title</dt>
                    <dd class="col-sm-8"><?php echo e($submission->thesis->title); ?></dd>

                    <dt class="col-sm-4 text-muted">Submitted</dt>
                    <dd class="col-sm-8"><?php echo e($submission->submitted_at->format('d M Y, H:i')); ?></dd>

                    <dt class="col-sm-4 text-muted">Status</dt>
                    <dd class="col-sm-8">
                        <span class="badge bg-<?php echo e($submission->status === 'approved' ? 'success' : ($submission->status === 'rejected' ? 'danger' : 'warning')); ?> text-<?php echo e(in_array($submission->status, ['submitted','under_review']) ? 'dark' : ''); ?>">
                            <?php echo e(ucfirst(str_replace('_', ' ', $submission->status))); ?>

                        </span>
                    </dd>
                </dl>
            </div>
        </div>

        
        <?php if($submission->description): ?>
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-file-alt me-2 text-secondary"></i>Application Description</h6>
            </div>
            <div class="card-body">
                <p class="mb-0" style="font-size:13px; white-space:pre-wrap;"><?php echo e($submission->description); ?></p>
            </div>
        </div>
        <?php endif; ?>

        
        <?php if($submission->documents): ?>
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-paperclip me-2 text-secondary"></i>Attached Documents</h6>
            </div>
            <div class="card-body p-0">
                <?php $__currentLoopData = $submission->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="d-flex align-items-center gap-2 px-3 py-2 border-bottom">
                    <i class="fas fa-file-pdf text-danger"></i>
                    <span style="font-size:13px;" class="flex-grow-1"><?php echo e($doc['original_name']); ?></span>
                    <a href="<?php echo e(route('documents.download', [$submission, $i])); ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-download"></i>
                    </a>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>

        
        <?php if($submission->reviews->count()): ?>
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-history me-2 text-secondary"></i>Previous Reviews</h6>
            </div>
            <div class="card-body p-0">
                <?php $__currentLoopData = $submission->reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="px-3 py-2 border-bottom" style="font-size:13px;">
                    <div class="d-flex justify-content-between">
                        <span class="fw-medium"><?php echo e($review->reviewer->name); ?></span>
                        <span class="badge bg-<?php echo e($review->decision === 'approved' ? 'success' : ($review->decision === 'rejected' ? 'danger' : 'warning')); ?>">
                            <?php echo e(ucfirst(str_replace('_', ' ', $review->decision))); ?>

                        </span>
                    </div>
                    <div class="text-muted small mt-1"><?php echo e($review->recommendations); ?></div>
                    <div class="text-muted small"><?php echo e(optional($review->reviewed_at)->format('d M Y')); ?></div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>

    </div>

    
    <div class="col-lg-6">
        <?php if(in_array($submission->status, ['submitted', 'under_review'])): ?>
        
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-clipboard-check me-2 text-success"></i>Submit Review Decision</h6>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('ethics.review', $submission)); ?>" method="POST">
                    <?php echo csrf_field(); ?>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Decision <span class="text-danger">*</span></label>
                        <select name="decision" class="form-select <?php $__errorArgs = ['decision'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="">Select decision…</option>
                            <option value="approved"          <?php echo e(old('decision') === 'approved'          ? 'selected' : ''); ?>>Approve</option>
                            <option value="rejected"          <?php echo e(old('decision') === 'rejected'          ? 'selected' : ''); ?>>Reject</option>
                            <option value="revision_required" <?php echo e(old('decision') === 'revision_required' ? 'selected' : ''); ?>>Revision Required</option>
                        </select>
                        <?php $__errorArgs = ['decision'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Comments <span class="text-danger">*</span></label>
                        <textarea name="comments" rows="5"
                                  class="form-control <?php $__errorArgs = ['comments'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                  placeholder="Provide detailed feedback on the ethics application…" required><?php echo e(old('comments')); ?></textarea>
                        <?php $__errorArgs = ['comments'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Recommendations <span class="text-danger">*</span></label>
                        <textarea name="recommendations" rows="4"
                                  class="form-control <?php $__errorArgs = ['recommendations'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                  placeholder="List specific recommendations or conditions for approval…" required><?php echo e(old('recommendations')); ?></textarea>
                        <?php $__errorArgs = ['recommendations'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="alert alert-info py-2 small">
                        <i class="fas fa-info-circle me-1"></i>
                        If approved, the thesis will automatically advance to the Data Collection stage. The student will be notified by email.
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?php echo e(route('ethics.pending')); ?>" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-paper-plane me-2"></i>Submit Decision
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php else: ?>
        
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-clipboard-check me-2 text-secondary"></i>Review Decision</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <?php if($submission->status === 'approved'): ?>
                        <span class="badge bg-success fs-6">Approved</span>
                    <?php elseif($submission->status === 'rejected'): ?>
                        <span class="badge bg-danger fs-6">Rejected</span>
                    <?php elseif($submission->status === 'revision_required'): ?>
                        <span class="badge bg-warning text-dark fs-6">Revision Required</span>
                    <?php endif; ?>
                    <small class="text-muted">Reviewed <?php echo e(optional($submission->reviewed_at)->format('d M Y, H:i')); ?></small>
                </div>

                <?php $__currentLoopData = $submission->feedbacks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="mb-3">
                    <p class="small fw-medium text-muted mb-1">Comments by <?php echo e($fb->reviewer->name); ?></p>
                    <p class="mb-0" style="font-size:13px; white-space:pre-wrap;"><?php echo e($fb->comments); ?></p>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php $__currentLoopData = $submission->reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($rv->recommendations): ?>
                <div class="border-top pt-3">
                    <p class="small fw-medium text-muted mb-1">Recommendations</p>
                    <p class="mb-0" style="font-size:13px; white-space:pre-wrap;"><?php echo e($rv->recommendations); ?></p>
                </div>
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <a href="<?php echo e(route('ethics.pending')); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Queue
        </a>
        <?php endif; ?>
    </div>

</div>
</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\ethics\review.blade.php ENDPATH**/ ?>