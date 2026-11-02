
<?php $__env->startSection('title', 'Recommendation submitted — JOOUST'); ?>
<?php $__env->startSection('content'); ?>
<div class="section-divider"></div>
<section class="py-5"><div class="container" style="max-width:680px;"><div class="form-card p-5 text-center">
    <i class="fas fa-circle-check text-success fa-3x mb-3"></i>
    <h1 class="h3">Recommendation submitted</h1>
    <p class="text-muted mb-0">Thank you. Your confidential recommendation for <?php echo e($referee->application->full_name); ?> has been received by the Board of Postgraduate Studies.</p>
</div></div></section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\referees\thanks.blade.php ENDPATH**/ ?>