<?php $__env->startSection('title', 'Change Password'); ?>
<?php $__env->startSection('page-title', 'Change Password'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-semibold mb-1">Change password</h5>
                <p class="text-muted mb-4">Choose a new password for your account.</p>

                <form method="POST" action="<?php echo e(route('password.change')); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current password</label>
                        <input id="current_password" name="current_password" type="password" class="form-control" required autocomplete="current-password">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">New password</label>
                        <input id="password" name="password" type="password" class="form-control" required autocomplete="new-password">
                    </div>
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirm new password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" required autocomplete="new-password">
                    </div>
                    <button type="submit" class="btn btn-primary">Change password</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\account\password.blade.php ENDPATH**/ ?>