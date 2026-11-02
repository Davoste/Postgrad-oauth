<?php $__env->startSection('title', 'Edit Profile'); ?>
<?php $__env->startSection('page-title', 'Edit Profile'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-semibold mb-1">Edit profile</h5>
                <p class="text-muted mb-4">Update your account details.</p>

                <form method="POST" action="<?php echo e(route('profile.update')); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="surname" class="form-label">Surname</label>
                            <input id="surname" name="surname" type="text" class="form-control" value="<?php echo e(old('surname', $user->surname)); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">First Name</label>
                            <input id="first_name" name="first_name" type="text" class="form-control" value="<?php echo e(old('first_name', $user->first_name)); ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Display Name</label>
                        <input id="name" name="name" type="text" class="form-control" value="<?php echo e(old('name', $user->name)); ?>" required>
                        <small class="text-muted">Automatically updated when surname/first name above are changed.</small>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input id="email" name="email" type="email" class="form-control" value="<?php echo e(old('email', $user->email)); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input id="phone" name="phone" type="text" class="form-control" value="<?php echo e(old('phone', $user->phone)); ?>">
                    </div>
                    <div class="mb-4">
                        <label for="id_no" class="form-label">ID / Passport Number</label>
                        <input id="id_no" name="id_no" type="text" class="form-control" value="<?php echo e(old('id_no', $user->id_no)); ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\account\profile.blade.php ENDPATH**/ ?>