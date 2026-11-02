<?php $__env->startSection('title', 'New Progress Report'); ?>
<?php $__env->startSection('page-title', 'Submit Progress Report'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
<div class="col-lg-8">

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
        <a href="<?php echo e(route('progress-reports.index', $thesis)); ?>" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i></a>
        <div>
            <h6 class="mb-0 fw-semibold">New Progress Report</h6>
            <small class="text-muted"><?php echo e($thesis->title); ?></small>
        </div>
    </div>
    <div class="card-body p-4">
        <form action="<?php echo e(route('progress-reports.store', $thesis)); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-medium">Report Type <span class="text-danger">*</span></label>
                    <select name="report_type" class="form-select <?php $__errorArgs = ['report_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="">Select…</option>
                        <option value="quarterly"  <?php echo e(old('report_type') === 'quarterly'  ? 'selected' : ''); ?>>Quarterly</option>
                        <option value="biannual"   <?php echo e(old('report_type') === 'biannual'   ? 'selected' : ''); ?>>Biannual</option>
                    </select>
                    <?php $__errorArgs = ['report_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-medium">Period Start <span class="text-danger">*</span></label>
                    <input type="date" name="period_start" value="<?php echo e(old('period_start')); ?>"
                           class="form-control <?php $__errorArgs = ['period_start'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           max="<?php echo e(now()->format('Y-m-d')); ?>" required>
                    <?php $__errorArgs = ['period_start'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-medium">Period End <span class="text-danger">*</span></label>
                    <input type="date" name="period_end" value="<?php echo e(old('period_end')); ?>"
                           class="form-control <?php $__errorArgs = ['period_end'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           max="<?php echo e(now()->format('Y-m-d')); ?>" required>
                    <?php $__errorArgs = ['period_end'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium">Student Progress Rating <span class="text-danger">*</span></label>
                <div class="d-flex gap-3">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="student_progress_rating"
                               id="rating<?php echo e($i); ?>" value="<?php echo e($i); ?>"
                               <?php echo e(old('student_progress_rating') == $i ? 'checked' : ''); ?> required>
                        <label class="form-check-label" for="rating<?php echo e($i); ?>">
                            <?php echo e($i); ?>

                            <small class="text-muted d-block" style="font-size:10px;">
                                <?php if($i===1): ?> Poor <?php elseif($i===2): ?> Below Avg <?php elseif($i===3): ?> Average <?php elseif($i===4): ?> Good <?php else: ?> Excellent <?php endif; ?>
                            </small>
                        </label>
                    </div>
                    <?php endfor; ?>
                </div>
                <?php $__errorArgs = ['student_progress_rating'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium">Report Content <span class="text-danger">*</span></label>
                <textarea name="content" rows="8"
                          class="form-control <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                          placeholder="Describe the student's progress during this period, activities completed, milestones reached, and overall assessment. (Minimum 100 characters)" required><?php echo e(old('content')); ?></textarea>
                <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium">Challenges / Concerns</label>
                <textarea name="challenges" rows="3"
                          class="form-control <?php $__errorArgs = ['challenges'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                          placeholder="Any challenges the student is facing or concerns you have noticed…"><?php echo e(old('challenges')); ?></textarea>
                <?php $__errorArgs = ['challenges'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-4">
                <label class="form-label fw-medium">Recommendations</label>
                <textarea name="recommendations" rows="3"
                          class="form-control <?php $__errorArgs = ['recommendations'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                          placeholder="Recommendations for the student's next steps…"><?php echo e(old('recommendations')); ?></textarea>
                <?php $__errorArgs = ['recommendations'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="d-flex align-items-center justify-content-between">
                <small class="text-muted"><i class="fas fa-info-circle me-1"></i>The student will be notified to acknowledge this report.</small>
                <button type="submit" class="btn btn-success"><i class="fas fa-save me-2"></i>Submit Report</button>
            </div>
        </form>
    </div>
</div>

</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\progress-reports\create.blade.php ENDPATH**/ ?>