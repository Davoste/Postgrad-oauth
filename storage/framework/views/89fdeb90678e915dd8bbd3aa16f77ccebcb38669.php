
<?php $__env->startSection('title', $title); ?>
<?php $__env->startSection('page-title', $title); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center gap-3">
        <span class="fw-semibold text-truncate"><?php echo e($fileName); ?></span>
        <?php if($downloadUrl): ?>
            <a href="<?php echo e($downloadUrl); ?>" class="btn btn-sm btn-primary flex-shrink-0">
                <i class="fas fa-download me-1"></i>Download
            </a>
        <?php endif; ?>
    </div>
    <div class="card-body p-0" style="height: calc(100vh - 190px); min-height: 520px;">
        <iframe src="<?php echo e($previewUrl); ?>" title="<?php echo e($title); ?>" class="w-100 h-100 border-0"></iframe>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\admissions\document-preview.blade.php ENDPATH**/ ?>