<?php $__env->startSection('title', $programme->exists ? 'Edit Programme' : 'New Programme'); ?>
<?php $__env->startSection('page-title', $programme->exists ? 'Edit Programme' : 'New Programme'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center"><div class="col-lg-9">
<?php if($errors->any()): ?>
    <div class="alert alert-danger"><ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul></div>
<?php endif; ?>

<div class="card border-0 shadow-sm"><div class="card-body p-4">
    <form method="POST" action="<?php echo e($programme->exists ? route('admin.programmes.update', $programme) : route('admin.programmes.store')); ?>">
        <?php echo csrf_field(); ?>
        <?php if($programme->exists): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
        <?php $modes = old('modes', $programme->modes ?? []); ?>

        <div class="row g-3">
            <div class="col-md-8"><label class="form-label small fw-semibold">Programme name *</label>
                <input name="name" value="<?php echo e(old('name', $programme->name)); ?>" required class="form-control"></div>
            <div class="col-md-4"><label class="form-label small fw-semibold">Code *</label>
                <input name="code" value="<?php echo e(old('code', $programme->code)); ?>" required class="form-control font-monospace" placeholder="e.g. MSC-CS"></div>

            <div class="col-md-6"><label class="form-label small fw-semibold">Department *</label>
                <select name="department_id" required class="form-select">
                    <option value="">Select…</option>
                    <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($d->id); ?>" <?php echo e((old('department_id', $programme->department_id) == $d->id) ? 'selected' : ''); ?>><?php echo e(optional($d->school)->code); ?> — <?php echo e($d->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3"><label class="form-label small fw-semibold">Level *</label>
                <select name="level" required class="form-select">
                    <?php $__currentLoopData = ['masters'=>"Master's", 'phd'=>'PhD', 'pgd'=>'Postgraduate Diploma']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val=>$lab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($val); ?>" <?php echo e((old('level', $programme->level) === $val) ? 'select' : ''); ?>><?php echo e($lab); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3"><label class="form-label small fw-semibold">Duration (years) *</label>
                <input type="number" min="1" max="8" name="duration_years" value="<?php echo e(old('duration_years', $programme->duration_years)); ?>" required class="form-control"></div>

            <div class="col-md-4"><label class="form-label small fw-semibold">Tuition per year (KES) *</label>
                <input type="number" step="0.01" min="0" name="tuition_per_year" value="<?php echo e(old('tuition_per_year', $programme->tuition_per_year)); ?>" required class="form-control"></div>

            <div class="col-md-8"><label class="form-label small fw-semibold">Modes of study</label>
                <div class="d-flex flex-wrap gap-3 pt-2">
                    <?php $__currentLoopData = ['full_time'=>'Full time','part_time'=>'Part time','school_based'=>'School based','weekend_intensive'=>'Weekend / intensive']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val=>$lab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-check">
                            <input type="checkbox" name="modes[]" value="<?php echo e($val); ?>" id="mode_<?php echo e($val); ?>" class="form-check-input" <?php echo e(in_array($val, $modes) ? 'checked' : ''); ?>>
                            <label for="mode_<?php echo e($val); ?>" class="form-check-label small"><?php echo e($lab); ?></label>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <div class="col-12"><label class="form-label small fw-semibold">Overview</label>
                <textarea name="overview" rows="3" class="form-control"><?php echo e(old('overview', $programme->overview)); ?></textarea></div>
            <div class="col-12"><label class="form-label small fw-semibold">Career prospects</label>
                <textarea name="career_prospects" rows="2" class="form-control"><?php echo e(old('career_prospects', $programme->career_prospects)); ?></textarea></div>

            <div class="col-md-6">
                <div class="form-check form-switch">
                    <input type="checkbox" name="is_active" value="1" id="is_active" class="form-check-input" <?php echo e(old('is_active', $programme->is_active) ? 'checked' : ''); ?>>
                    <label for="is_active" class="form-check-label">Active (visible in catalogue)</label>
                </div>
                <div class="form-check form-switch">
                    <input type="checkbox" name="applications_open" value="1" id="applications_open" class="form-check-input" <?php echo e(old('applications_open', $programme->applications_open) ? 'checked' : ''); ?>>
                    <label for="applications_open" class="form-check-label">Applications open</label>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button class="btn btn-primary"><i class="fas fa-save me-1"></i> <?php echo e($programme->exists ? 'Save changes' : 'Create programme'); ?></button>
            <a href="<?php echo e(route('admin.programmes.index')); ?>" class="btn btn-link">Cancel</a>
        </div>
    </form>
</div></div>
</div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\admin\programmes\form.blade.php ENDPATH**/ ?>