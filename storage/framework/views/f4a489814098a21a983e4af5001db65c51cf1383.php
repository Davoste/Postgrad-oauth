<?php $__env->startSection('title', 'Allocate Supervisor'); ?>
<?php $__env->startSection('page-title', 'Allocate Supervisor'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
<div class="col-lg-7">

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
        <a href="<?php echo e(route('admin.theses')); ?>" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i></a>
        <h6 class="mb-0 fw-semibold">Assign Supervisor</h6>
    </div>
    <div class="card-body p-4">

        
        <div class="border rounded p-3 bg-light mb-4">
            <div class="fw-semibold mb-1"><?php echo e($thesis->title); ?></div>
            <div class="text-muted small">
                Student: <?php echo e($thesis->student->user->name); ?> &bull; <?php echo e($thesis->student->reg_no); ?>

            </div>
            <?php
            $stageColors=['concept_development'=>'secondary','proposal_development'=>'info','ethical_approval'=>'warning','data_collection'=>'primary','writing_thesis'=>'info','intent_to_submit'=>'warning','submitted'=>'primary','examination'=>'warning','graduated'=>'success'];
            ?>
            <span class="badge bg-<?php echo e($stageColors[$thesis->current_stage] ?? 'secondary'); ?> mt-1"><?php echo e($thesis->stage_label); ?></span>
        </div>

        <form method="POST" action="<?php echo e(route('admin.thesis.allocate-supervisor', $thesis)); ?>">
            <?php echo csrf_field(); ?>
            <div class="mb-4">
                <label class="form-label fw-medium">Select Supervisor <span class="text-danger">*</span></label>
                <select name="supervisor_id" class="form-select <?php $__errorArgs = ['supervisor_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <option value="">Choose a supervisor…</option>
                    <?php $__currentLoopData = $supervisors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($sup->id); ?>" <?php echo e(old('supervisor_id') == $sup->id ? 'selected' : ''); ?>>
                        <?php echo e($sup->user->name); ?> — <?php echo e($sup->department); ?>

                        (<?php echo e($sup->theses()->count()); ?>/<?php echo e($sup->max_capacity); ?> students)
                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['supervisor_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <div class="form-text">Supervisors at or above capacity are shown for reference. Verify availability before assigning.</div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-user-plus me-2"></i>Assign Supervisor</button>
                <a href="<?php echo e(route('admin.theses')); ?>" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\admin\allocate-supervisor.blade.php ENDPATH**/ ?>