<?php $__env->startSection('title', 'Application'); ?>
<?php $__env->startSection('page-title', $application->ref_no); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4"><div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <h5 class="fw-semibold mb-0"><?php echo e($application->full_name); ?></h5>
                <span class="badge bg-warning text-dark"><?php echo e($application->status_label); ?></span>
            </div>
            <dl class="row mb-0 small">
                <dt class="col-sm-4 text-muted fw-normal">Programme</dt><dd class="col-sm-8"><?php echo e($application->programme->name); ?> (<?php echo e($application->programme->level_label); ?>)</dd>
                <dt class="col-sm-4 text-muted fw-normal">School / Dept</dt><dd class="col-sm-8"><?php echo e(optional($application->programme->department->school)->name); ?> · <?php echo e(optional($application->programme->department)->name); ?></dd>
                <dt class="col-sm-4 text-muted fw-normal">Field of study</dt><dd class="col-sm-8"><?php echo e($application->field_of_study ?? '—'); ?></dd>
                <dt class="col-sm-4 text-muted fw-normal">Mode / Place</dt><dd class="col-sm-8"><span class="text-capitalize"><?php echo e(str_replace('_',' ', $application->mode)); ?></span> · <?php echo e($application->campus); ?></dd>
                <dt class="col-sm-4 text-muted fw-normal">Commence / Complete</dt><dd class="col-sm-8"><?php echo e(optional($application->commencement_date)->format('M Y') ?? '—'); ?> → <?php echo e(optional($application->completion_date)->format('M Y') ?? '—'); ?></dd>
                <?php if($application->research_institution): ?>
                    <dt class="col-sm-4 text-muted fw-normal">Research institution</dt><dd class="col-sm-8"><?php echo e($application->research_institution); ?></dd>
                <?php endif; ?>

                <dt class="col-12 text-jooust fw-semibold pt-2">Personal</dt>
                <dt class="col-sm-4 text-muted fw-normal">Sex / DOB</dt><dd class="col-sm-8 text-capitalize"><?php echo e($application->sex); ?> · <?php echo e($application->dob->format('d M Y')); ?></dd>
                <dt class="col-sm-4 text-muted fw-normal">Nationality / ID</dt><dd class="col-sm-8"><?php echo e($application->nationality); ?> · <?php echo e($application->id_no ?? '—'); ?></dd>
                <dt class="col-sm-4 text-muted fw-normal">Marital status</dt><dd class="col-sm-8"><?php echo e($application->marital_status ?? '—'); ?></dd>
                <dt class="col-sm-4 text-muted fw-normal">Phone / Email</dt><dd class="col-sm-8"><?php echo e($application->phone); ?> · <?php echo e($application->email); ?></dd>
                <dt class="col-sm-4 text-muted fw-normal">Postal address</dt><dd class="col-sm-8"><?php echo e($application->postal_address); ?></dd>
                <?php if($application->permanent_address): ?>
                    <dt class="col-sm-4 text-muted fw-normal">Permanent address</dt><dd class="col-sm-8"><?php echo e($application->permanent_address); ?></dd>
                <?php endif; ?>

                <dt class="col-12 text-jooust fw-semibold pt-2">Academic background</dt>
                <dt class="col-sm-4 text-muted fw-normal">First degree</dt><dd class="col-sm-8"><?php echo e($application->first_degree_award); ?> — <?php echo e($application->first_degree_field); ?>, <?php echo e($application->first_degree_university); ?> (<?php echo e($application->first_degree_class ?? 'n/a'); ?><?php echo e($application->first_degree_year ? ', '.$application->first_degree_year : ''); ?>)<?php echo e($application->first_degree_dates ? ' · '.$application->first_degree_dates : ''); ?></dd>
                <?php if($application->second_degree_university): ?>
                    <dt class="col-sm-4 text-muted fw-normal">Second degree</dt><dd class="col-sm-8"><?php echo e($application->second_degree_award); ?> — <?php echo e($application->second_degree_field); ?>, <?php echo e($application->second_degree_university); ?><?php echo e($application->second_degree_year ? ' ('.$application->second_degree_year.')' : ''); ?></dd>
                <?php endif; ?>
                <?php if($application->other_qualifications): ?>
                    <dt class="col-sm-4 text-muted fw-normal">Other qualifications</dt><dd class="col-sm-8"><?php echo e($application->other_qualifications); ?></dd>
                <?php endif; ?>
                <?php if($application->research_experience): ?>
                    <dt class="col-sm-4 text-muted fw-normal">Research experience</dt><dd class="col-sm-8"><?php echo e($application->research_experience); ?></dd>
                <?php endif; ?>
                <?php if($application->languages): ?>
                    <dt class="col-sm-4 text-muted fw-normal">Other languages</dt><dd class="col-sm-8"><?php echo e($application->languages); ?></dd>
                <?php endif; ?>
                <dt class="col-sm-4 text-muted fw-normal">Funding</dt><dd class="col-sm-8"><?php echo e($application->finance_source ?? '—'); ?></dd>
            </dl>

            <?php if(!empty($application->employment)): ?>
                <div class="mt-3">
                    <div class="text-jooust fw-semibold small mb-1">Employment record</div>
                    <table class="table table-sm small mb-0">
                        <thead class="table-light"><tr><th>Position</th><th>Place</th><th>From</th><th>To</th></tr></thead>
                        <tbody>
                            <?php $__currentLoopData = $application->employment; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr><td><?php echo e($job['position'] ?? ''); ?></td><td><?php echo e($job['place'] ?? ''); ?></td><td><?php echo e($job['from'] ?? ''); ?></td><td><?php echo e($job['to'] ?? ''); ?></td></tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div></div>

        <div class="card border-0 shadow-sm mb-4"><div class="card-header bg-white fw-semibold">Referees</div>
        <div class="card-body">
            <?php $__empty_1 = true; $__currentLoopData = $application->applicationReferees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="border-bottom py-2 small">
                    <div class="d-flex justify-content-between"><span class="fw-semibold"><?php echo e($r->name); ?></span><span class="text-muted"><?php echo e($r->email); ?></span></div>
                    <?php if($r->address): ?><div class="text-muted"><?php echo e($r->address); ?></div><?php endif; ?>
                    <?php if($r->submitted_at): ?>
                        <div class="text-success mt-1"><i class="fas fa-check me-1"></i>Submitted <?php echo e($r->submitted_at->format('d M Y H:i')); ?></div>
                        <?php
                            $ratingLabels = [
                                'excellent' => 'Excellent', 'very_good' => 'V. Good', 'good' => 'Good',
                                'average' => 'Average', 'below_average' => 'Below Average', 'unable_to_assess' => 'Unable to Assess',
                            ];
                            $criteria = [
                                'academic_ability' => 'Academic ability',
                                'persistent_independent_study' => 'Persistent & independent study',
                                'productive_scholarship' => 'Productive scholarship',
                                'previous_work' => 'Previous work',
                                'english_expression' => 'English expression',
                            ];
                        ?>
                        <dl class="row g-1 mt-2 mb-0 small">
                            <dt class="col-sm-4 text-muted fw-normal">Known for</dt><dd class="col-sm-8"><?php echo e($r->known_for ?: '—'); ?></dd>
                            <dt class="col-sm-4 text-muted fw-normal">Designation</dt><dd class="col-sm-8"><?php echo e($r->designation ?: '—'); ?></dd>
                            <dt class="col-sm-4 text-muted fw-normal">Address</dt><dd class="col-sm-8"><?php echo e($r->referee_address ?: '—'); ?></dd>
                            <dt class="col-sm-4 text-muted fw-normal">Contact</dt><dd class="col-sm-8"><?php echo e($r->referee_contact ?: '—'); ?></dd>
                        </dl>
                        <div class="table-responsive mt-2">
                            <table class="table table-sm table-bordered small mb-2">
                                <thead class="table-light"><tr><th>Assessment area</th><th>Response</th></tr></thead>
                                <tbody>
                                    <?php $__currentLoopData = $criteria; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr><td><?php echo e($label); ?></td><td class="fw-semibold"><?php echo e($ratingLabels[$r->ratings[$key] ?? ''] ?? '—'); ?></td></tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="p-2 bg-light rounded" style="white-space: pre-wrap;"><span class="fw-semibold d-block mb-1">Recommendation</span><?php echo e($r->recommendation ?: '—'); ?></div>
                        <?php if($r->attachment_path): ?><a href="<?php echo e(route('admissions.referee.attachment', [$application, $r])); ?>" class="d-inline-block mt-2 text-decoration-none"><i class="fas fa-eye me-1"></i>View supporting letter</a><?php endif; ?>
                    <?php else: ?>
                        <div class="text-warning mt-1"><i class="fas fa-clock me-1"></i>Awaiting response</div>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <?php $__empty_1 = true; $__currentLoopData = ($application->referees ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="border-bottom py-2 small"><div class="d-flex justify-content-between"><span class="fw-semibold"><?php echo e($r['name'] ?? '—'); ?></span><span class="text-muted"><?php echo e($r['email'] ?? ''); ?></span></div></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-muted small">No referees listed.</div>
                <?php endif; ?>
            <?php endif; ?>
        </div></div>

        <div class="card border-0 shadow-sm"><div class="card-header bg-white fw-semibold">Supporting documents</div>
        <div class="card-body">
            <?php
                $docs = [
                    'photo'        => ['Passport photo', $application->passport_photo_path],
                    'receipt'      => ['Application receipt', $application->application_receipt_path],
                    'academic'     => ['Academic certificate', $application->academic_certificate_path],
                    'transcript'   => ['Transcript', $application->transcript_path],
                    'professional' => ['Professional certificate', $application->professional_certificate_path],
                ];
                $hasAny = collect($docs)->contains(fn ($d) => !empty($d[1]));
            ?>
            <?php if($hasAny): ?>
                <div class="d-flex flex-column gap-2">
                    <?php $__currentLoopData = $docs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($doc[1]): ?>
                            <a href="<?php echo e(route('admissions.document', [$application, $key])); ?>" class="d-flex justify-content-between align-items-center text-decoration-none small border rounded px-3 py-2">
                                <span><i class="fas fa-paperclip me-2 text-jooust"></i><?php echo e($doc[0]); ?></span>
                                <span class="text-muted"><i class="fas fa-eye me-1"></i>View</span>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php if($application->application_no): ?><div class="text-muted small mt-2">Receipt / Application No: <span class="fw-semibold"><?php echo e($application->application_no); ?></span></div><?php endif; ?>
            <?php else: ?>
                <div class="text-muted small">No documents attached (this application predates document uploads).</div>
            <?php endif; ?>
        </div></div>
    </div>

    <div class="col-lg-4">
        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('review_applications')): ?>
        <?php if($application->status === 'submitted' && auth()->user()->isHod()): ?>
        <div class="card border-0 shadow-sm mb-3"><div class="card-body p-4">
            <h6 class="fw-semibold mb-3">HOD recommendation</h6>
            <form method="POST" action="<?php echo e(route('admissions.hod', $application)); ?>">
                <?php echo csrf_field(); ?>
                <textarea name="comment" rows="2" class="form-control form-control-sm mb-2" placeholder="Comment (optional)"></textarea>
                <div class="d-flex gap-2">
                    <button name="decision" value="recommended" class="btn btn-sm btn-primary flex-fill">Recommend</button>
                    <button name="decision" value="rejected" class="btn btn-sm btn-outline-secondary flex-fill">Reject</button>
                </div>
            </form>
        </div></div>
        <?php endif; ?>
        <?php endif; ?>

        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('decide_admission')): ?>
        <?php if($application->status === 'under_review' && auth()->user()->isDirector()): ?>
        <div class="card border-0 shadow-sm mb-3"><div class="card-body p-4">
            <h6 class="fw-semibold mb-3">Board decision</h6>
            <form method="POST" action="<?php echo e(route('admissions.director', $application)); ?>">
                <?php echo csrf_field(); ?>
                <textarea name="comment" rows="2" class="form-control form-control-sm mb-2" placeholder="Comment (optional)"></textarea>
                <div class="d-flex gap-2">
                    <button name="decision" value="admitted" class="btn btn-sm btn-primary flex-fill">Admit</button>
                    <button name="decision" value="rejected" class="btn btn-sm btn-outline-secondary flex-fill">Reject</button>
                </div>
            </form>
        </div></div>
        <?php endif; ?>
        <?php endif; ?>

        
        <div class="card border-0 shadow-sm"><div class="card-body p-4 small">
            <h6 class="fw-semibold mb-3">Review trail</h6>
            <div class="mb-2"><span class="text-muted">HOD:</span> <span class="text-capitalize"><?php echo e($application->hod_decision); ?></span>
                <?php if($application->hod_reviewed_at): ?><div class="text-muted"><?php echo e(optional($application->hod)->name); ?> · <?php echo e($application->hod_reviewed_at->format('d M Y')); ?></div><?php endif; ?>
                <?php if($application->hod_comment): ?><div class="fst-italic">"<?php echo e($application->hod_comment); ?>"</div><?php endif; ?>
            </div>
            <div><span class="text-muted">Director:</span> <span class="text-capitalize"><?php echo e($application->director_decision); ?></span>
                <?php if($application->director_reviewed_at): ?><div class="text-muted"><?php echo e(optional($application->director)->name); ?> · <?php echo e($application->director_reviewed_at->format('d M Y')); ?></div><?php endif; ?>
                <?php if($application->director_comment): ?><div class="fst-italic">"<?php echo e($application->director_comment); ?>"</div><?php endif; ?>
            </div>
        </div></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\admissions\show.blade.php ENDPATH**/ ?>