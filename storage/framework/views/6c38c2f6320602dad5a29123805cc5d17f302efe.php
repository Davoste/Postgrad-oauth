
<?php $__env->startSection('title', 'Edit User'); ?>
<?php $__env->startSection('page-title', 'Edit User — ' . $user->name); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
<div class="col-lg-8">

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
        <a href="<?php echo e(route('admin.users')); ?>" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i></a>
        <h6 class="mb-0 fw-semibold">Edit: <?php echo e($user->name); ?></h6>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="<?php echo e(route('admin.users.update', $user)); ?>">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-medium">Full Name</label>
                    <input type="text" name="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('name', $user->name)); ?>" required>
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Email Address</label>
                    <input type="email" name="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('email', $user->email)); ?>" required>
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">New Password <small class="text-muted">(leave blank to keep)</small></label>
                    <input type="password" name="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" minlength="8">
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Role</label>
                    <select name="role" id="roleSelect" class="form-select" onchange="toggleRoleFields()">
                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($role->name); ?>" <?php echo e(old('role', optional($userRole)->name) == $role->name ? 'selected' : ''); ?>><?php echo e(ucfirst($role->name)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            
            <div id="studentFields" class="<?php echo e(old('role', optional($userRole)->name) !== 'student' ? 'd-none' : ''); ?> border rounded p-3 bg-light mb-4">
                <h6 class="fw-semibold mb-3"><i class="fas fa-user-graduate me-2 text-success"></i>Student Details</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Registration No.</label>
                        <input type="text" name="reg_no" class="form-control" value="<?php echo e(old('reg_no', optional($student)->reg_no)); ?>">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-medium">Programme</label>
                        <input type="text" name="program" class="form-control" value="<?php echo e(old('program', optional($student)->program)); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-medium">Admission Year</label>
                        <input type="number" name="admission_year" class="form-control" value="<?php echo e(old('admission_year', optional($student)->admission_year)); ?>" min="2000" max="<?php echo e(date('Y') + 1); ?>">
                    </div>
                </div>
            </div>

            
            <div id="supervisorFields" class="<?php echo e(old('role', optional($userRole)->name) !== 'supervisor' ? 'd-none' : ''); ?> border rounded p-3 bg-light mb-4">
                <h6 class="fw-semibold mb-3"><i class="fas fa-chalkboard-teacher me-2 text-info"></i>Supervisor Details</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Department</label>
                        <input type="text" name="department" class="form-control" value="<?php echo e(old('department', optional($supervisor)->department)); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Max Student Capacity</label>
                        <input type="number" name="max_capacity" class="form-control" value="<?php echo e(old('max_capacity', optional($supervisor)->max_capacity ?? 5)); ?>" min="1" max="20">
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Save Changes</button>
                <a href="<?php echo e(route('admin.users')); ?>" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

</div>
</div>

<?php $__env->startSection('scripts'); ?>
<script>
function toggleRoleFields() {
    const role = document.getElementById('roleSelect').value;
    document.getElementById('studentFields').classList.toggle('d-none', role !== 'student');
    document.getElementById('supervisorFields').classList.toggle('d-none', role !== 'supervisor');
}
</script>
<?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\admin\edit-user.blade.php ENDPATH**/ ?>