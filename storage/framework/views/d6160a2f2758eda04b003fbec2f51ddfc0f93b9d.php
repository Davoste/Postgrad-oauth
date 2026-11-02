<?php $__env->startSection('title', 'My Fee Statement'); ?>
<?php $__env->startSection('page-title', 'Fee Statement'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $billed = $student->feeInvoices->sum('amount');
    $paid = $student->feeInvoices->sum('amount_paid');
    $balance = $student->fee_balance;
?>
<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Total billed</div><div class="fs-5 fw-bold">KES <?php echo e(number_format($billed)); ?></div></div></div></div>
    <div class="col-md-4"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Total paid</div><div class="fs-5 fw-bold text-success">KES <?php echo e(number_format($paid)); ?></div></div></div></div>
    <div class="col-md-4"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Outstanding</div><div class="fs-5 fw-bold <?php echo e($balance > 0 ? 'text-danger' : 'text-success'); ?>">KES <?php echo e(number_format($balance)); ?></div></div></div></div>
</div>

<div class="card border-0 shadow-sm mb-4"><div class="card-header bg-white fw-semibold">Invoices</div>
<div class="card-body p-0">
    <table class="table mb-0 align-middle">
        <thead class="table-light"><tr><th class="ps-3">Invoice</th><th>Description</th><th>Year/Sem</th><th>Amount</th><th>Paid</th><th>Balance</th><th>Status</th></tr></thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $student->feeInvoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="ps-3 font-monospace small"><?php echo e($inv->invoice_no); ?></td>
                    <td><?php echo e($inv->description); ?></td>
                    <td class="text-muted"><?php echo e($inv->academic_year); ?> S<?php echo e($inv->semester); ?></td>
                    <td>KES <?php echo e(number_format($inv->amount)); ?></td>
                    <td class="text-success">KES <?php echo e(number_format($inv->amount_paid)); ?></td>
                    <td class="<?php echo e($inv->balance > 0 ? 'text-danger fw-semibold' : 'text-muted'); ?>">KES <?php echo e(number_format($inv->balance)); ?></td>
                    <td><span class="badge <?php echo e($inv->status === 'paid' ? 'bg-success' : ($inv->status === 'waived' ? 'bg-secondary' : 'bg-warning text-dark')); ?>"><?php echo e($inv->status_label); ?></span></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7" class="text-center text-muted py-4">No invoices on your account.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div></div>

<div class="card border-0 shadow-sm"><div class="card-header bg-white fw-semibold">Payment history</div>
<div class="card-body p-0">
    <table class="table mb-0 align-middle">
        <thead class="table-light"><tr><th class="ps-3">Receipt</th><th>Date</th><th>Method</th><th>Reference</th><th class="text-end pe-3">Amount</th></tr></thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $student->feePayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr><td class="ps-3 font-monospace small"><?php echo e($p->receipt_no); ?></td><td><?php echo e($p->paid_on->format('d M Y')); ?></td><td class="text-capitalize"><?php echo e($p->method); ?></td><td class="text-muted"><?php echo e($p->reference ?? '—'); ?></td><td class="text-end pe-3 fw-semibold text-success">KES <?php echo e(number_format($p->amount)); ?></td></tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" class="text-center text-muted py-4">No payments recorded.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\finance\my-fees.blade.php ENDPATH**/ ?>