
<?php $__env->startSection('title', 'Log Meeting'); ?>
<?php $__env->startSection('page-title', 'Log Supervision Meeting'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
<div class="col-lg-7">

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
        <a href="<?php echo e(route('supervision.meetings')); ?>" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i></a>
        <h6 class="mb-0 fw-semibold">Log a Supervision Meeting</h6>
    </div>
    <div class="card-body p-4">
        <form action="<?php echo e(route('meetings.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <div class="mb-3">
                <label class="form-label fw-medium">Student <span class="text-danger">*</span></label>
                <select name="student_id" class="form-select <?php $__errorArgs = ['student_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <option value="">Select student…</option>
                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($student->id); ?>" <?php echo e(old('student_id') == $student->id ? 'selected' : ''); ?>>
                            <?php echo e($student->user->name); ?> (<?php echo e($student->reg_no); ?>)
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['student_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-5">
                    <label class="form-label fw-medium">Meeting Date <span class="text-danger">*</span></label>
                    <input type="date" name="meeting_date" value="<?php echo e(old('meeting_date')); ?>"
                           class="form-control <?php $__errorArgs = ['meeting_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           max="<?php echo e(now()->format('Y-m-d')); ?>" required>
                    <?php $__errorArgs = ['meeting_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-7">
                    <label class="form-label fw-medium">Agenda <span class="text-danger">*</span></label>
                    <input type="text" name="agenda" value="<?php echo e(old('agenda')); ?>"
                           class="form-control <?php $__errorArgs = ['agenda'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           placeholder="Primary discussion focus" required maxlength="500">
                    <?php $__errorArgs = ['agenda'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-medium">Minutes &amp; Action Items <span class="text-danger">*</span></label>
                <textarea name="minutes" rows="6"
                          class="form-control <?php $__errorArgs = ['minutes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                          placeholder="Detailed notes, decisions made, and next steps…" required><?php echo e(old('minutes')); ?></textarea>
                <?php $__errorArgs = ['minutes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="d-flex align-items-center justify-content-between">
                <small class="text-muted"><i class="fas fa-info-circle me-1"></i>This meeting will be automatically signed by you. The student will be notified to sign off.</small>
                <button type="submit" class="btn btn-success"><i class="fas fa-save me-2"></i>Log &amp; Sign Meeting</button>
            </div>
        </form>
    </div>
</div>

</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\supervision\log-meeting.blade.php ENDPATH**/ ?>