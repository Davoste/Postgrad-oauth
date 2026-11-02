<?php $__env->startSection('title', 'Programmes — JOOUST Postgraduate'); ?>

<?php $__env->startSection('content'); ?>
<div class="section-divider"></div>
<section class="hero py-5">
    <div class="container">
        <span class="section-label">Board of Postgraduate Studies</span>
        <h1 class="fw-bold mt-2 mb-1">Postgraduate programmes</h1>
        <p class="opacity-75 mb-0">All available programmes by school — with minimum entry requirements, duration and indicative fees.</p>
    </div>
</section>
<div class="section-divider"></div>
<section class="py-5">
    <div class="container" style="max-width:1120px;">
        
        <div class="d-flex flex-wrap gap-2 mb-4">
            <?php $__currentLoopData = $programmes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school => $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="#<?php echo e(\Illuminate\Support\Str::slug($school)); ?>" class="btn btn-sm btn-outline-primary"><?php echo e($school); ?> <span class="badge bg-jooust ms-1"><?php echo e($list->count()); ?></span></a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <?php $__empty_1 = true; $__currentLoopData = $programmes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school => $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div id="<?php echo e(\Illuminate\Support\Str::slug($school)); ?>" class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <span class="fw-bold text-jooust"><i class="fas fa-graduation-cap me-2"></i><?php echo e($school); ?></span>
                    <span class="badge bg-jooust ms-1"><?php echo e($list->count()); ?> programme<?php echo e($list->count() > 1 ? 's' : ''); ?></span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="small text-uppercase" style="letter-spacing:.04em;">
                                <th class="ps-3" style="width:26%">Programme</th>
                                <th style="width:10%">Level</th>
                                <th style="width:9%">Duration</th>
                                <th style="width:13%">Fees / year</th>
                                <th style="width:13%">Indicative total</th>
                                <th style="width:21%">Minimum requirements</th>
                                <th class="text-end pe-3" style="width:8%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-semibold text-jooust"><?php echo e($p->name); ?></div>
                                        <div class="text-muted small"><?php echo e($p->department->name ?? ''); ?></div>
                                    </td>
                                    <td><span class="badge bg-secondary"><?php echo e($p->level_label); ?></span></td>
                                    <td class="text-nowrap"><?php echo e($p->duration_years); ?> yr<?php echo e($p->duration_years > 1 ? 's' : ''); ?></td>
                                    <td class="text-nowrap">KES <?php echo e(number_format($p->tuition_per_year)); ?></td>
                                    <td class="text-nowrap"><?php echo e($p->tuition_per_year > 0 ? 'KES ' . number_format($p->total_cost) : '—'); ?></td>
                                    <td>
                                        <ul class="small text-muted mb-0 ps-3">
                                            <?php $__currentLoopData = $p->min_requirements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li><?php echo e($req); ?></li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    </td>
                                    <td class="text-end pe-3">
                                        <a href="<?php echo e(route('apply', ['programme' => $p->id])); ?>" class="btn btn-sm btn-blue text-nowrap">Apply</a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center text-muted py-5">Programmes will be published shortly.</div>
        <?php endif; ?>

        <div class="alert alert-light border small text-muted mt-2">
            <i class="fas fa-circle-info me-1"></i>
            Fees shown are indicative tuition per academic year and exclude statutory and other charges.
            Confirm current fees and specific entry requirements with the Board of Postgraduate Studies before applying.
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views/programmes/index.blade.php ENDPATH**/ ?>