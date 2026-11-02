<?php $__env->startSection('title', 'Track application'); ?>

<?php $__env->startSection('content'); ?>
<section class="py-5">
    <div class="container" style="max-width:760px;">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                    <div>
                        <div class="text-muted small">Application</div>
                        <h3 class="fw-bold text-jooust mb-1"><?php echo e($application->ref_no); ?></h3>
                        <div class="text-muted"><?php echo e($application->programme->name); ?></div>
                    </div>
                    <span class="badge bg-warning text-dark fs-6"><?php echo e($application->status_label); ?></span>
                </div>

                <?php
                    $steps = ['submitted'=>'Submitted','under_review'=>'Under review','offer_made'=>'Offer made','accepted'=>'Enrolled'];
                    $order = array_keys($steps);
                    $currentIdx = array_search($application->status, $order);
                    if ($currentIdx === false) $currentIdx = in_array($application->status,['rejected','declined']) ? 99 : 0;
                ?>
                <div class="d-flex align-items-center mt-4">
                    <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $title): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="text-center flex-fill">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center fw-semibold <?php echo e($loop->index <= $currentIdx ? 'bg-jooust text-white' : 'bg-light text-muted'); ?>" style="width:38px;height:38px;"><?php echo e($loop->iteration); ?></div>
                            <div class="small mt-1 <?php echo e($loop->index <= $currentIdx ? 'fw-semibold text-jooust' : 'text-muted'); ?>"><?php echo e($title); ?></div>
                        </div>
                        <?php if(!$loop->last): ?><div class="flex-fill" style="height:3px;background:<?php echo e($loop->index < $currentIdx ? '#1a3c6e' : '#e5e7eb'); ?>;"></div><?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        <?php if($application->status === 'offer_made'): ?>
            <div class="card border-warning shadow-sm mt-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold text-jooust"><i class="fas fa-graduation-cap me-2"></i>You have an offer of admission!</h4>
                    <p class="text-muted">Congratulations. Accept or decline below. On acceptance, your student account is created and login details emailed to you.</p>
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger"><?php echo e($errors->first()); ?></div>
                    <?php endif; ?>
                    <form method="POST" action="<?php echo e(route('track.respond', $application->tracking_token)); ?>" class="mt-3">
                        <?php echo csrf_field(); ?>
                        <!-- <div class="row g-3">
                            <div class="col-md-4"><label class="form-label small fw-semibold">Next of kin name</label><input name="next_of_kin_name" class="form-control"></div>
                            <div class="col-md-4"><label class="form-label small fw-semibold">Next of kin phone</label><input name="next_of_kin_phone" class="form-control"></div>
                            <div class="col-md-4"><label class="form-label small fw-semibold">County</label><input name="county" class="form-control"></div>
                        </div> -->
                        <input type="hidden" name="decline_reason" value="Declined by applicant">
                        <div class="d-flex gap-2 mt-3">
                            <button name="decision" value="accept" class="btn btn-blue px-4">Accept offer</button>
                            <button name="decision" value="decline" onclick="return confirm('Are you sure you want to decline?')" class="btn btn-outline-secondary px-4">Decline</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <?php if($application->status === 'accepted'): ?>
            <div class="alert alert-success mt-4">
                You have accepted your offer. Check your email for your admission number and login details, then
                <a href="<?php echo e(route('login')); ?>" class="fw-semibold">sign in to the portal</a>.
            </div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\admissions\track.blade.php ENDPATH**/ ?>