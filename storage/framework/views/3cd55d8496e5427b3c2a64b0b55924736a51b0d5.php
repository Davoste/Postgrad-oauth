<?php $__env->startSection('title', 'Invoices'); ?>
<?php $__env->startSection('page-title', 'Fee Invoices'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Billed</div><div class="fs-5 fw-bold">KES <?php echo e(number_format($summary['billed'])); ?></div></div></div></div>
    <div class="col-md-4"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Collected</div><div class="fs-5 fw-bold text-success">KES <?php echo e(number_format($summary['collected'])); ?></div></div></div></div>
    <div class="col-md-4"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Outstanding</div><div class="fs-5 fw-bold text-danger">KES <?php echo e(number_format($summary['outstanding'])); ?></div></div></div></div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
        <form method="GET" class="d-flex gap-2">
            <input name="q" value="<?php echo e(request('q')); ?>" class="form-control form-control-sm" placeholder="Search student / invoice…">
            <select name="status" class="form-select form-select-sm" style="width:auto">
                <option value="">All statuses</option>
                <?php $__currentLoopData = ['unpaid','partial','paid','waived']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($s); ?>" @selected(request('status')===$s)><?php echo e(ucfirst($s)); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button class="btn btn-sm btn-primary">Filter</button>
        </form>
        <a href="<?php echo e(route('finance.invoices.create')); ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-plus me-1"></i> New</a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light"><tr><th class="ps-3">Invoice</th><th>Student</th><th>Description</th><th>Amount</th><th>Balance</th><th>Status</th><th></th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-3 font-monospace small"><?php echo e($inv->invoice_no); ?></td>
                        <td><?php echo e($inv->student->user->name ?? '—'); ?></td>
                        <td class="text-muted"><?php echo e($inv->description); ?></td>
                        <td>KES <?php echo e(number_format($inv->amount)); ?></td>
                        <td class="<?php echo e($inv->balance > 0 ? 'text-danger fw-semibold' : 'text-muted'); ?>">KES <?php echo e(number_format($inv->balance)); ?></td>
                        <td><span class="badge <?php echo e($inv->status === 'paid' ? 'bg-success' : ($inv->status === 'waived' ? 'bg-secondary' : 'bg-warning text-dark')); ?>"><?php echo e($inv->status_label); ?></span></td>
                        <td class="text-end pe-3"><a href="<?php echo e(route('finance.invoices.show', $inv)); ?>" class="btn btn-sm btn-link">Open</a></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">No invoices found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white"><?php echo e($invoices->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\finance\invoices.blade.php ENDPATH**/ ?>