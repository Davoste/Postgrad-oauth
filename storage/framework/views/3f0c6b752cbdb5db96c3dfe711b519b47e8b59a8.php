<?php $__env->startSection('title', 'Finance Dashboard'); ?>
<?php $__env->startSection('page-title', 'Finance Office'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100"><div class="card-body d-flex align-items-center gap-3">
            <div class="rounded-circle bg-primary bg-opacity-10 p-3"><i class="fas fa-file-invoice-dollar text-primary fa-lg"></i></div>
            <div><div class="fs-5 fw-bold">KES <?php echo e(number_format($summary['billed'])); ?></div><div class="text-muted small">Total billed</div></div>
        </div></div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100"><div class="card-body d-flex align-items-center gap-3">
            <div class="rounded-circle bg-success bg-opacity-10 p-3"><i class="fas fa-coins text-success fa-lg"></i></div>
            <div><div class="fs-5 fw-bold">KES <?php echo e(number_format($summary['collected'])); ?></div><div class="text-muted small">Collected</div></div>
        </div></div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100"><div class="card-body d-flex align-items-center gap-3">
            <div class="rounded-circle bg-danger bg-opacity-10 p-3"><i class="fas fa-hand-holding-usd text-danger fa-lg"></i></div>
            <div><div class="fs-5 fw-bold">KES <?php echo e(number_format($summary['outstanding'])); ?></div><div class="text-muted small">Outstanding</div></div>
        </div></div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100"><div class="card-body d-flex align-items-center gap-3">
            <div class="rounded-circle bg-info bg-opacity-10 p-3"><i class="fas fa-receipt text-info fa-lg"></i></div>
            <div><div class="fs-4 fw-bold"><?php echo e($summary['invoices']); ?></div><div class="text-muted small">Invoices</div></div>
        </div></div>
    </div>
</div>

<div class="d-flex gap-2 mb-4">
    <a href="<?php echo e(route('finance.invoices')); ?>" class="btn btn-primary"><i class="fas fa-list me-1"></i> Manage invoices</a>
    <a href="<?php echo e(route('finance.invoices.create')); ?>" class="btn btn-outline-primary"><i class="fas fa-plus me-1"></i> New invoice</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white fw-semibold">Recent payments</div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light"><tr><th class="ps-3">Student</th><th>Receipt</th><th>Date</th><th>Method</th><th class="text-end pe-3">Amount</th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $recentPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr><td class="ps-3"><?php echo e($p->student->user->name ?? '—'); ?></td><td class="font-monospace small"><?php echo e($p->receipt_no); ?></td><td><?php echo e($p->paid_on->format('d M Y')); ?></td><td class="text-capitalize"><?php echo e($p->method); ?></td><td class="text-end pe-3 fw-semibold text-success">KES <?php echo e(number_format($p->amount)); ?></td></tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">No payments recorded yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\finance\dashboard.blade.php ENDPATH**/ ?>