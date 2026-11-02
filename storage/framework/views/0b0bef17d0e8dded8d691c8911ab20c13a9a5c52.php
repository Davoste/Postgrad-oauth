<?php $__env->startSection('title', 'Executive Dashboard'); ?>
<?php $__env->startSection('page-title', 'Executive Overview'); ?>

<?php $__env->startSection('content'); ?>
<p class="text-muted">Institution-wide postgraduate indicators. This view is read-only and shows aggregates only — no individual applicant or student records.</p>


<div class="row g-3 mb-4">
    <?php $__currentLoopData = [
        ['Applications', $kpis['applications'], 'fa-file-lines', 'primary'],
        ['Students', $kpis['students'], 'fa-user-graduate', 'success'],
        ['Active programmes', $kpis['programmes'], 'fa-book', 'info'],
        ['Schools', $kpis['schools'], 'fa-building-columns', 'warning'],
    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $val, $icon, $col]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100"><div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-<?php echo e($col); ?> bg-opacity-10 p-3"><i class="fas <?php echo e($icon); ?> text-<?php echo e($col); ?> fa-lg"></i></div>
                <div><div class="fs-4 fw-bold"><?php echo e(number_format($val)); ?></div><div class="text-muted small"><?php echo e($label); ?></div></div>
            </div></div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<div class="row g-4">
    
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100"><div class="card-body p-4">
            <h6 class="fw-semibold text-jooust mb-3">Admissions funnel</h6>
            <?php $max = max(1, collect($funnel)->max()); ?>
            <?php $__currentLoopData = $funnel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage => $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="mb-2">
                    <div class="d-flex justify-content-between small"><span><?php echo e($stage); ?></span><span class="fw-semibold"><?php echo e(number_format($n)); ?></span></div>
                    <div class="progress" style="height:8px;"><div class="progress-bar bg-jooust" style="width: <?php echo e(round($n / $max * 100)); ?>%"></div></div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div></div>
    </div>

    
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100"><div class="card-body p-4">
            <h6 class="fw-semibold text-jooust mb-3">Applications by status</h6>
            <?php $tot = max(1, $appsByStatus->sum()); ?>
            <?php $__empty_1 = true; $__currentLoopData = $appsByStatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="mb-2">
                    <div class="d-flex justify-content-between small"><span class="text-capitalize"><?php echo e(str_replace('_',' ',$status)); ?></span><span class="fw-semibold"><?php echo e(number_format($n)); ?></span></div>
                    <div class="progress" style="height:8px;"><div class="progress-bar bg-secondary" style="width: <?php echo e(round($n / $tot * 100)); ?>%"></div></div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-muted small">No applications yet.</div>
            <?php endif; ?>
        </div></div>
    </div>

    
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100"><div class="card-body p-4">
            <h6 class="fw-semibold text-jooust mb-3">Fees (institution-wide)</h6>
            <div class="row text-center g-2">
                <div class="col-4"><div class="fs-6 fw-bold">KES <?php echo e(number_format($finance['billed'])); ?></div><div class="text-muted small">Billed</div></div>
                <div class="col-4"><div class="fs-6 fw-bold text-success">KES <?php echo e(number_format($finance['collected'])); ?></div><div class="text-muted small">Collected</div></div>
                <div class="col-4"><div class="fs-6 fw-bold text-danger">KES <?php echo e(number_format($finance['outstanding'])); ?></div><div class="text-muted small">Outstanding</div></div>
            </div>
            <?php $collRate = $finance['billed'] > 0 ? round($finance['collected'] / $finance['billed'] * 100) : 0; ?>
            <div class="progress mt-3" style="height:8px;"><div class="progress-bar bg-success" style="width: <?php echo e($collRate); ?>%"></div></div>
            <div class="text-muted small mt-1">Collection rate: <?php echo e($collRate); ?>%</div>
        </div></div>
    </div>

    
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100"><div class="card-body p-4">
            <h6 class="fw-semibold text-jooust mb-3">Students by school</h6>
            <?php $sMax = max(1, collect($studentsBySchool)->max() ?: 1); ?>
            <?php $__empty_1 = true; $__currentLoopData = $studentsBySchool; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school => $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="mb-2">
                    <div class="d-flex justify-content-between small"><span><?php echo e($school); ?></span><span class="fw-semibold"><?php echo e($n); ?></span></div>
                    <div class="progress" style="height:8px;"><div class="progress-bar bg-info" style="width: <?php echo e(round($n / $sMax * 100)); ?>%"></div></div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-muted small">No enrolled students yet.</div>
            <?php endif; ?>
        </div></div>
    </div>

    
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm"><div class="card-body p-4">
            <h6 class="fw-semibold text-jooust mb-3">Programmes by level</h6>
            <div class="row text-center g-2">
                <?php $__currentLoopData = ['masters'=>"Master's",'phd'=>'PhD','pgd'=>'PG Diploma']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lvl=>$lab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-4"><div class="fs-5 fw-bold text-jooust"><?php echo e($progByLevel[$lvl] ?? 0); ?></div><div class="text-muted small"><?php echo e($lab); ?></div></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\executive\dashboard.blade.php ENDPATH**/ ?>