<?php $__env->startSection('title', 'Admissions'); ?>
<?php $__env->startSection('page-title', 'Admissions'); ?>

<?php $__env->startSection('styles'); ?>
<style>
    @media (max-width: 768px) {
        .applications-table thead { display: none; }
        .applications-table,
        .applications-table tbody,
        .applications-table tr,
        .applications-table td { display: block; width: 100%; }
        .applications-table tr {
            padding: 12px 14px;
            border-bottom: 1px solid #e5e7eb;
        }
        .applications-table tr:last-child { border-bottom: 0; }
        .applications-table td {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            gap: 12px;
            padding: 4px 0 !important;
            border: 0;
            text-align: right;
            overflow-wrap: anywhere;
        }
        .applications-table td::before {
            content: attr(data-label);
            flex: 0 0 82px;
            color: #6b7280;
            font-size: .72rem;
            font-weight: 600;
            letter-spacing: .04em;
            text-align: left;
            text-transform: uppercase;
        }
        .applications-table td:last-child { justify-content: flex-end; padding-top: 8px !important; }
        .applications-table td:last-child::before { display: none; }
        .applications-table .status-cell { align-items: center; }
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
        <span class="fw-semibold">Applications</span>
        <form method="GET" class="d-flex gap-2">
            <select name="status" class="form-select form-select-sm" style="width:auto" onchange="this.form.submit()">
                <option value="">All statuses</option>
                <?php $__currentLoopData = ['submitted','under_review','offer_made','accepted','declined','rejected']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($s); ?>" @selected(request('status')===$s)><?php echo e(ucfirst(str_replace('_',' ',$s))); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </form>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle applications-table">
            <thead class="table-light"><tr><th class="ps-3">Ref</th><th>Applicant</th><th>Programme</th><th>Submitted</th><th>Status</th><th></th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td data-label="Ref" class="ps-3 font-monospace small"><?php echo e($a->ref_no); ?></td>
                        <td data-label="Applicant"><?php echo e($a->full_name); ?></td>
                        <td data-label="Programme" class="text-muted"><?php echo e($a->programme->name); ?></td>
                        <td data-label="Submitted" class="text-muted small"><?php echo e($a->created_at->format('d M Y')); ?></td>
                        <td data-label="Status" class="status-cell"><span class="badge <?php echo e($a->status === 'accepted' ? 'bg-success' : ($a->status === 'rejected' || $a->status==='declined' ? 'bg-secondary' : 'bg-warning text-dark')); ?>"><?php echo e($a->status_label); ?></span></td>
                        <td data-label="" class="text-end pe-3"><a href="<?php echo e(route('admissions.show', $a)); ?>" class="btn btn-sm btn-link">Review</a></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">No applications found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white"><?php echo e($applications->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\admissions\index.blade.php ENDPATH**/ ?>