<?php $__env->startSection('title', 'Application submitted'); ?>

<?php $__env->startSection('content'); ?>
<section class="py-5">
    <div class="container text-center" style="max-width:640px;">
        <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-4" style="width:88px;height:88px;">
            <i class="fas fa-check text-success fa-3x"></i>
        </div>
        <h1 class="fw-bold text-jooust">Application received</h1>
        <p class="text-muted">Thank you, <?php echo e($application->first_name); ?>. Your application has been submitted and a confirmation email is on its way. If not received, please check your spam folder.</p>

        <div class="card border-0 shadow-sm text-start mt-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted small">Reference number</span>
                    <span class="fw-semibold text-jooust font-monospace"><?php echo e($application->ref_no); ?></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted small">Programme</span>
                    <span class="fw-semibold"><?php echo e($application->programme->name); ?></span>
                </div>
            </div>
        </div>

        <p class="text-muted small mt-4 mb-1">Save this link to track your application anytime:</p>
        <a href="<?php echo e(route('track', $application->tracking_token)); ?>" class="d-inline-block bg-light border rounded px-3 py-2 font-monospace small text-break"><?php echo e(route('track', $application->tracking_token)); ?></a>

        <div class="mt-4">
            <a href="<?php echo e(route('track', $application->tracking_token)); ?>" class="btn btn-blue btn-lg px-4">Track my application <i class="fas fa-arrow-right ms-2"></i></a>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\admissions\apply-success.blade.php ENDPATH**/ ?>