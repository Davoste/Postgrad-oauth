
<?php $__env->startSection('title', 'Student Dashboard'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<?php if(!Auth::user()->hasVerifiedEmail()): ?>
<div class="alert alert-warning d-flex align-items-center gap-2 mb-4">
    <i class="fas fa-envelope-open-text fa-lg"></i>
    <div>
        Please confirm your email address. Check your inbox for a confirmation link we sent when you registered.
        <form method="POST" action="<?php echo e(route('verification.send')); ?>" class="d-inline ms-2">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-warning btn-sm">Resend Email</button>
        </form>
    </div>
</div>
<?php endif; ?>

<?php if(!($admitted ?? false)): ?>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body text-center py-5">
        <i class="fas fa-graduation-cap text-primary" style="font-size:3.5rem;"></i>
        <h4 class="mt-3 fw-semibold">Welcome, <?php echo e(Auth::user()->name); ?>!</h4>

        <?php if(!isset($application)): ?>
            <p class="text-muted">You haven't submitted your postgraduate application yet. Complete it to begin the admission process.</p>
            <a href="<?php echo e(route('apply.portal')); ?>" class="btn btn-primary">
                <i class="fas fa-file-alt me-2"></i>Apply Now
            </a>
        <?php elseif($application && in_array($application->status, ['submitted', 'under_review'])): ?>
            <p class="text-muted">
                Your application <strong><?php echo e($application->ref_no); ?></strong> for
                <strong><?php echo e($application->programme->name); ?></strong> is being reviewed.
            </p>
            <span class="badge bg-info text-dark mb-3">Status: <?php echo e(ucwords(str_replace('_',' ', $application->status))); ?></span><br>
            <a href="<?php echo e(route('track', $application->tracking_token)); ?>" class="btn btn-outline-primary">
                <i class="fas fa-magnifying-glass me-2"></i>Track Application
            </a>
        <?php elseif($application && $application->status === 'offer_made'): ?>
            <p class="text-muted">Congratulations — you've been offered admission to <strong><?php echo e($application->programme->name); ?></strong>!</p>
            <a href="<?php echo e(route('track', $application->tracking_token)); ?>" class="btn btn-success">
                <i class="fas fa-check-circle me-2"></i>Respond to Offer
            </a>
        <?php elseif($application && $application->status === 'declined'): ?>
            <p class="text-muted">You declined your offer of admission for <strong><?php echo e($application->programme->name); ?></strong>.</p>
            <a href="<?php echo e(route('apply.portal')); ?>" class="btn btn-primary">
                <i class="fas fa-file-alt me-2"></i>Submit a New Application
            </a>
        <?php elseif($application && $application->status === 'rejected'): ?>
            <p class="text-muted">Your application <strong><?php echo e($application->ref_no); ?></strong> was not successful on this occasion.</p>
            <a href="<?php echo e(route('apply.portal')); ?>" class="btn btn-primary">
                <i class="fas fa-file-alt me-2"></i>Apply Again
            </a>
        <?php else: ?>
            <p class="text-muted">You haven't submitted your postgraduate application yet. Complete it to begin the admission process.</p>
            <a href="<?php echo e(route('apply.portal')); ?>" class="btn btn-primary">
                <i class="fas fa-file-alt me-2"></i>Apply Now
            </a>
        <?php endif; ?>

        <p class="text-muted small mt-4 mb-0">
            <i class="fas fa-lock me-1"></i>
            Thesis Progress, Submissions, Feedback, Milestones and Meetings unlock once your application is admitted.
        </p>
    </div>
</div>
<?php elseif(!$thesis): ?>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body text-center py-5">
        <i class="fas fa-graduation-cap text-primary" style="font-size:3.5rem;"></i>
        <h4 class="mt-3 fw-semibold">Welcome, <?php echo e(Auth::user()->name); ?>!</h4>
        <p class="text-muted">Your thesis record will appear here once it has been created by the administrator.</p>
        <a href="<?php echo e(route('thesis.dashboard')); ?>" class="btn btn-primary">
            <i class="fas fa-book me-2"></i>Go to Thesis Portal
        </a>
    </div>
</div>
<?php else: ?>


<?php if($pendingMeetingSign): ?>
<div class="alert alert-warning d-flex align-items-center gap-2 mb-4">
    <i class="fas fa-pen-square fa-lg"></i>
    <div>
        <strong>Action Required:</strong> A supervision meeting on <?php echo e(\Carbon\Carbon::parse($pendingMeetingSign->meeting_date)->format('d M Y')); ?> needs your sign-off.
        <form method="POST" action="<?php echo e(route('meetings.sign', $pendingMeetingSign)); ?>" class="d-inline ms-2">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-warning btn-sm">Sign Now</button>
        </form>
    </div>
</div>
<?php endif; ?>

<?php if($pendingReportAck): ?>
<div class="alert alert-info d-flex align-items-center gap-2 mb-4">
    <i class="fas fa-file-alt fa-lg"></i>
    <div>
        <strong>Action Required:</strong> A progress report (<?php echo e(ucfirst($pendingReportAck->report_type)); ?>) submitted by your supervisor needs your acknowledgement.
        <a href="<?php echo e(route('progress-reports.show', $pendingReportAck)); ?>" class="btn btn-info btn-sm ms-2">View & Acknowledge</a>
    </div>
</div>
<?php endif; ?>


<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3"><i class="fas fa-book text-primary fa-lg"></i></div>
                <div>
                    <div class="fs-5 fw-bold"><?php echo e($thesis->stage_label); ?></div>
                    <div class="text-muted small">Current Stage</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success bg-opacity-10 p-3"><i class="fas fa-tasks text-success fa-lg"></i></div>
                <div>
                    <div class="fs-4 fw-bold"><?php echo e(number_format($thesis->progress_percentage, 0)); ?>%</div>
                    <div class="text-muted small">Overall Progress</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-<?php echo e($meetingsThisMonth >= 1 ? 'success' : 'danger'); ?> bg-opacity-10 p-3">
                    <i class="fas fa-calendar-check text-<?php echo e($meetingsThisMonth >= 1 ? 'success' : 'danger'); ?> fa-lg"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold"><?php echo e($meetingsThisMonth); ?></div>
                    <div class="text-muted small">Meetings This Month</div>
                    <?php if($meetingsThisMonth < 1): ?>
                        <small class="text-danger">No meeting yet!</small>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-info bg-opacity-10 p-3"><i class="fas fa-user-tie text-info fa-lg"></i></div>
                <div>
                    <?php if($thesis->supervisor): ?>
                        <div class="fw-bold" style="font-size:13px;"><?php echo e($thesis->supervisor->user->name); ?></div>
                        <div class="text-muted small">Supervisor</div>
                    <?php else: ?>
                        <div class="fw-bold text-warning">Unassigned</div>
                        <div class="text-muted small">Supervisor</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">Thesis Progress</h6>
                <a href="<?php echo e(route('thesis.dashboard')); ?>" class="btn btn-sm btn-outline-primary">View Details</a>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="small text-muted"><?php echo e($thesis->title); ?></span>
                        <span class="small fw-medium"><?php echo e(number_format($thesis->progress_percentage, 0)); ?>%</span>
                    </div>
                    <div class="progress" style="height:10px;">
                        <div class="progress-bar bg-success" style="width:<?php echo e($thesis->progress_percentage); ?>%;"></div>
                    </div>
                </div>

                <div class="row g-2 mt-1">
                    <div class="col-6 col-md-3 text-center">
                        <a href="<?php echo e(route('thesis.submissions')); ?>" class="btn btn-outline-primary btn-sm w-100">
                            <i class="fas fa-file-upload d-block mb-1" style="font-size:1.2rem;"></i>Submissions
                        </a>
                    </div>
                    <div class="col-6 col-md-3 text-center">
                        <a href="<?php echo e(route('thesis.feedback')); ?>" class="btn btn-outline-info btn-sm w-100">
                            <i class="fas fa-comments d-block mb-1" style="font-size:1.2rem;"></i>Feedback
                        </a>
                    </div>
                    <div class="col-6 col-md-3 text-center">
                        <a href="<?php echo e(route('thesis.milestones')); ?>" class="btn btn-outline-warning btn-sm w-100">
                            <i class="fas fa-tasks d-block mb-1" style="font-size:1.2rem;"></i>Milestones
                        </a>
                    </div>
                    <div class="col-6 col-md-3 text-center">
                        <a href="<?php echo e(route('supervision.meetings')); ?>" class="btn btn-outline-success btn-sm w-100">
                            <i class="fas fa-calendar d-block mb-1" style="font-size:1.2rem;"></i>Meetings
                        </a>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">Recent Meetings</h6>
                <a href="<?php echo e(route('supervision.meetings')); ?>" class="btn btn-sm btn-outline-secondary">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="font-size:12px;">Date</th>
                            <th style="font-size:12px;">Agenda</th>
                            <th style="font-size:12px;">Status</th>
                            <th style="font-size:12px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $thesis->meetings->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meeting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><small><?php echo e(\Carbon\Carbon::parse($meeting->meeting_date)->format('d M Y')); ?></small></td>
                            <td>
                                <div style="font-size:13px; max-width:200px;" class="text-truncate" title="<?php echo e($meeting->agenda); ?>"><?php echo e($meeting->agenda); ?></div>
                            </td>
                            <td>
                                <?php if($meeting->signed_student && $meeting->signed_supervisor): ?>
                                    <span class="badge bg-success">Signed</span>
                                <?php elseif($meeting->signed_supervisor): ?>
                                    <span class="badge bg-warning text-dark">Awaiting You</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if(!$meeting->signed_student && $meeting->signed_supervisor): ?>
                                <form method="POST" action="<?php echo e(route('meetings.sign', $meeting)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm btn-warning">Sign Off</button>
                                </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="4" class="text-center text-muted py-3">No meetings yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    <div class="col-lg-4">
        
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">Progress Reports</h6>
                <a href="<?php echo e(route('progress-reports.index', $thesis)); ?>" class="btn btn-sm btn-outline-secondary">View All</a>
            </div>
            <div class="card-body p-0">
                <?php $__empty_1 = true; $__currentLoopData = $thesis->progressReports->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="d-flex align-items-start gap-2 p-3 border-bottom">
                    <i class="fas fa-file-alt mt-1 text-primary"></i>
                    <div class="flex-grow-1">
                        <div style="font-size:13px;"><?php echo e(ucfirst($report->report_type)); ?> Report</div>
                        <small class="text-muted"><?php echo e(\Carbon\Carbon::parse($report->period_start)->format('M Y')); ?> – <?php echo e(\Carbon\Carbon::parse($report->period_end)->format('M Y')); ?></small>
                        <?php if(!$report->isAcknowledged()): ?>
                            <small class="text-warning d-block"><i class="fas fa-clock me-1"></i>Needs acknowledgement</small>
                        <?php endif; ?>
                    </div>
                    <a href="<?php echo e(route('progress-reports.show', $report)); ?>" class="btn btn-sm btn-outline-primary">View</a>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="p-3 text-muted text-center small">No progress reports yet.</div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">Upcoming Milestones</h6>
                <a href="<?php echo e(route('thesis.milestones')); ?>" class="btn btn-sm btn-outline-secondary">All</a>
            </div>
            <div class="card-body p-0">
                <?php
                $upcoming = $thesis->milestones->where('status', '!=', 'completed')->sortBy('target_date')->take(4);
                ?>
                <?php $__empty_1 = true; $__currentLoopData = $upcoming; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $milestone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="d-flex align-items-start gap-2 p-3 border-bottom">
                    <i class="fas fa-flag mt-1 text-<?php echo e(optional($milestone->target_date)->isPast() ? 'danger' : 'warning'); ?>"></i>
                    <div>
                        <div style="font-size:13px;"><?php echo e($milestone->milestone_name); ?></div>
                        <small class="text-<?php echo e(optional($milestone->target_date)->isPast() ? 'danger' : 'muted'); ?>">
                            <?php echo e(optional($milestone->target_date)->isPast() ? 'Overdue: ' : 'Due: '); ?><?php echo e(optional($milestone->target_date)->format('d M Y')); ?>

                        </small>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="p-3 text-muted text-center small">No pending milestones.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\student\dashboard.blade.php ENDPATH**/ ?>