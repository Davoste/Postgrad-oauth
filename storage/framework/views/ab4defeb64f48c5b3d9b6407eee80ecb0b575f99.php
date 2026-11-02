<?php $__env->startSection('title', 'My Thesis'); ?>
<?php $__env->startSection('page-title', 'My Thesis'); ?>

<?php $__env->startSection('content'); ?>
<?php
$stageDescriptions = [
    'concept_development'  => 'Develop and submit your thesis concept for approval.',
    'proposal_development' => 'Create a detailed research proposal.',
    'ethical_approval'     => 'Obtain ethical clearance for your research.',
    'data_collection'      => 'Collect and analyse research data.',
    'writing_thesis'       => 'Write and finalise the thesis document.',
    'intent_to_submit'     => 'Declare intent to submit before the final deadline.',
    'submitted'            => 'Thesis submitted for examination.',
    'examination'          => 'Thesis is under examination.',
    'graduated'            => 'Congratulations — thesis completed!',
];
$stageColors = [
    'concept_development'=>'secondary','proposal_development'=>'info',
    'ethical_approval'=>'warning','data_collection'=>'primary',
    'writing_thesis'=>'info','intent_to_submit'=>'warning',
    'submitted'=>'primary','examination'=>'warning','graduated'=>'success',
];
?>


<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h5 class="fw-semibold mb-1"><?php echo e($thesis->title); ?></h5>
                <p class="text-muted mb-0 small"><?php echo e($stageDescriptions[$thesis->current_stage] ?? ''); ?></p>
            </div>
            <div class="text-end">
                <span class="badge fs-6 px-3 py-2 bg-<?php echo e($stageColors[$thesis->current_stage] ?? 'secondary'); ?>">
                    <?php echo e($thesis->stage_label); ?>

                </span>
                <div class="d-flex align-items-center gap-2 mt-2">
                    <div class="progress" style="width:130px;height:6px;">
                        <div class="progress-bar bg-success" style="width:<?php echo e($thesis->progress_percentage); ?>%;"></div>
                    </div>
                    <small class="text-muted"><?php echo e(number_format($thesis->progress_percentage, 0)); ?>%</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">

    
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold">Thesis Progress Tracker</h6>
            </div>
            <div class="card-body" style="padding-left:44px;position:relative;">
                
                <div style="position:absolute;left:28px;top:24px;bottom:24px;width:2px;background:#e9ecef;"></div>

                <?php $__currentLoopData = $stageProgress; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage => $progress): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $sub      = $progress['submission'];
                    $feedback = $progress['feedback'];
                    $status   = $progress['status']; // completed | current | pending

                    // determine dot colour
                    if ($status === 'completed') {
                        $dotBg = '#198754';
                    } elseif ($status === 'current') {
                        if ($sub && $feedback && in_array($feedback->decision, ['rejected','revision_required'])) {
                            $dotBg = '#fd7e14'; // orange = action needed
                        } elseif ($sub) {
                            $dotBg = '#0d6efd'; // blue = under review
                        } else {
                            $dotBg = '#0d6efd';
                        }
                    } else {
                        $dotBg = '#dee2e6';
                    }

                    // determine card style
                    if ($status === 'completed') {
                        $cardBg    = '#f8fff9';
                        $cardBorder = '#198754';
                    } elseif ($status === 'current') {
                        $cardBg    = '#f0f8ff';
                        $cardBorder = '#0d6efd';
                    } else {
                        $cardBg    = '#f8f9fa';
                        $cardBorder = '#dee2e6';
                    }
                ?>

                <div class="d-flex align-items-start gap-3 mb-4" style="position:relative;">
                    
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:28px;height:28px;margin-left:-14px;z-index:1;background:<?php echo e($dotBg); ?>;color:white;font-size:11px;">
                        <?php if($status === 'completed'): ?>
                            <i class="fas fa-check"></i>
                        <?php elseif($status === 'current' && $sub && $feedback && in_array($feedback->decision, ['rejected','revision_required'])): ?>
                            <i class="fas fa-exclamation" style="font-size:10px;"></i>
                        <?php elseif($status === 'current'): ?>
                            <i class="fas fa-circle" style="font-size:8px;"></i>
                        <?php else: ?>
                            <i class="far fa-circle" style="font-size:8px;color:#adb5bd;"></i>
                        <?php endif; ?>
                    </div>

                    
                    <div class="border rounded p-3 flex-grow-1"
                         style="background:<?php echo e($cardBg); ?>;border-color:<?php echo e($cardBorder); ?> !important;">

                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-1 mb-1">
                            <div class="fw-semibold" style="font-size:13px;"><?php echo e($progress['name']); ?></div>
                            
                            <?php if($status === 'completed'): ?>
                                <span class="badge bg-success"><i class="fas fa-check me-1"></i>Approved</span>
                            <?php elseif($status === 'current'): ?>
                                <?php if(!$sub): ?>
                                    <span class="badge bg-primary">Action Required</span>
                                <?php elseif($feedback && $feedback->decision === 'approved'): ?>
                                    <span class="badge bg-success">Approved</span>
                                <?php elseif($feedback && $feedback->decision === 'rejected'): ?>
                                    <span class="badge bg-danger">Rejected</span>
                                <?php elseif($feedback && $feedback->decision === 'revision_required'): ?>
                                    <span class="badge bg-warning text-dark">Revision Required</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Under Review</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="badge bg-light text-muted border">Upcoming</span>
                            <?php endif; ?>
                        </div>

                        <div class="text-muted mb-2" style="font-size:11px;"><?php echo e($stageDescriptions[$stage] ?? ''); ?></div>

                        
                        <?php if($sub): ?>
                        <div class="d-flex flex-wrap gap-2 align-items-center mb-2" style="font-size:11px;">
                            <span class="text-muted"><i class="fas fa-upload me-1"></i>Submitted <?php echo e($sub->submitted_at->format('d M Y')); ?></span>
                            <?php if($sub->documents && count($sub->documents)): ?>
                                <span class="text-muted"><i class="fas fa-paperclip me-1"></i><?php echo e(count($sub->documents)); ?> file(s)</span>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        
                        <?php if($feedback): ?>
                        <div class="p-2 rounded mb-2" style="background:rgba(0,0,0,.04);font-size:12px;">
                            <span class="fw-medium">Feedback:</span> <?php echo e(Str::limit($feedback->comments, 120)); ?>

                        </div>
                        <?php endif; ?>

                        
                        <?php if($status === 'current'): ?>
                            <?php if($stage === 'concept_development'): ?>
                                <button class="btn btn-sm btn-primary mt-1" data-bs-toggle="modal" data-bs-target="#submitConceptModal">
                                    <i class="fas fa-lightbulb me-1"></i><?php echo e($sub ? 'Resubmit Concept' : 'Submit Concept'); ?>

                                </button>
                            <?php elseif($stage === 'proposal_development'): ?>
                                <button class="btn btn-sm btn-success mt-1" data-bs-toggle="modal" data-bs-target="#submitProposalModal">
                                    <i class="fas fa-file-alt me-1"></i><?php echo e($sub ? 'Resubmit Proposal' : 'Submit Proposal'); ?>

                                </button>
                            <?php elseif($stage === 'ethical_approval'): ?>
                                <button class="btn btn-sm btn-warning mt-1" data-bs-toggle="modal" data-bs-target="#submitEthicsModal">
                                    <i class="fas fa-shield-alt me-1"></i><?php echo e($sub ? 'Resubmit Ethics Form' : 'Submit Ethics Form'); ?>

                                </button>
                            <?php elseif($stage === 'writing_thesis'): ?>
                                <?php if(!$thesis->intent_to_submit_date): ?>
                                <button class="btn btn-sm btn-warning mt-1 me-1" data-bs-toggle="modal" data-bs-target="#submitIntentModal">
                                    <i class="fas fa-calendar-check me-1"></i>Declare Intent
                                </button>
                                <?php else: ?>
                                <span class="badge bg-success mt-1 me-1"><i class="fas fa-calendar-check me-1"></i>Intent: <?php echo e($thesis->intent_to_submit_date->format('d M Y')); ?></span>
                                <?php endif; ?>
                                <button class="btn btn-sm btn-info mt-1" data-bs-toggle="modal" data-bs-target="#submitThesisModal">
                                    <i class="fas fa-book me-1"></i><?php echo e($sub ? 'Resubmit Thesis' : 'Upload Thesis'); ?>

                                </button>
                            <?php elseif($stage === 'intent_to_submit'): ?>
                                <button class="btn btn-sm btn-info mt-1" data-bs-toggle="modal" data-bs-target="#submitThesisModal">
                                    <i class="fas fa-book me-1"></i><?php echo e($sub ? 'Resubmit Thesis' : 'Upload Final Thesis'); ?>

                                </button>
                            <?php elseif($stage === 'data_collection'): ?>
                                <span class="text-muted" style="font-size:11px;"><i class="fas fa-info-circle me-1"></i>Await supervisor progress reports and meeting sign-offs.</span>
                            <?php elseif(in_array($stage, ['submitted','examination'])): ?>
                                <span class="text-muted" style="font-size:11px;"><i class="fas fa-clock me-1"></i>Awaiting examiner decision.</span>
                            <?php endif; ?>
                        <?php endif; ?>

                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    
    <div class="col-lg-5">

        
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold">Current Status</h6>
            </div>
            <div class="card-body" style="font-size:13px;">
                <?php if($currentSubmission): ?>
                <div class="mb-3">
                    <div class="text-muted small">Latest Submission</div>
                    <div class="fw-medium"><?php echo e($currentSubmission->title); ?></div>
                    <span class="badge bg-<?php echo e($currentSubmission->status === 'approved' ? 'success' : ($currentSubmission->status === 'rejected' ? 'danger' : ($currentSubmission->status === 'revision_required' ? 'warning' : 'secondary'))); ?>">
                        <?php echo e(ucfirst(str_replace('_', ' ', $currentSubmission->status))); ?>

                    </span>
                </div>
                <?php endif; ?>

                <?php if($latestFeedback): ?>
                <div class="mb-3">
                    <div class="text-muted small">Latest Feedback</div>
                    <div><?php echo e(Str::limit($latestFeedback->comments, 120)); ?></div>
                    <small class="text-primary">By: <?php echo e($latestFeedback->reviewer->name); ?></small>
                </div>
                <?php endif; ?>

                <?php if($thesis->supervisor): ?>
                <div class="mb-2">
                    <div class="text-muted small">Supervisor</div>
                    <div class="fw-medium"><?php echo e($thesis->supervisor->user->name); ?></div>
                    <div class="text-muted small"><?php echo e($thesis->supervisor->department); ?></div>
                </div>
                <?php if($thesis->supervisor_id): ?>
                <button class="btn btn-sm btn-outline-secondary mt-1" data-bs-toggle="modal" data-bs-target="#requestMeetingModal">
                    <i class="fas fa-calendar-plus me-1"></i>Request Meeting
                </button>
                <?php endif; ?>
                <?php else: ?>
                <div class="alert alert-warning py-2 small mb-0">
                    <i class="fas fa-exclamation-triangle me-1"></i>No supervisor assigned yet.
                </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold">Quick Links</h6>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="<?php echo e(route('thesis.submissions')); ?>" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-list me-2"></i>All Submissions
                </a>
                <a href="<?php echo e(route('thesis.feedback')); ?>" class="btn btn-outline-info btn-sm">
                    <i class="fas fa-comments me-2"></i>All Feedback
                </a>
                <a href="<?php echo e(route('thesis.milestones')); ?>" class="btn btn-outline-warning btn-sm">
                    <i class="fas fa-tasks me-2"></i>Milestones
                </a>
                <a href="<?php echo e(route('supervision.meetings')); ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-calendar-alt me-2"></i>Supervision Meetings
                </a>
            </div>
        </div>

        
        <?php if($overdueMilestones->count() > 0): ?>
        <div class="card border-0 shadow-sm mb-4" style="border-color:#dc3545 !important;">
            <div class="card-header bg-danger text-white py-3">
                <h6 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Overdue Milestones</h6>
            </div>
            <div class="card-body p-0">
                <?php $__currentLoopData = $overdueMilestones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="d-flex align-items-start gap-2 p-3 border-bottom">
                    <i class="fas fa-flag text-danger mt-1"></i>
                    <div>
                        <div class="fw-medium" style="font-size:13px;"><?php echo e($m->milestone_name); ?></div>
                        <small class="text-danger">Due: <?php echo e($m->target_date->format('d M Y')); ?></small>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>

        
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">Recent Meetings</h6>
                <a href="<?php echo e(route('supervision.meetings')); ?>" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <?php $__empty_1 = true; $__currentLoopData = $recentMeetings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meeting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="d-flex align-items-start gap-2 p-3 border-bottom">
                    <i class="fas fa-calendar mt-1 text-primary"></i>
                    <div class="flex-grow-1">
                        <div style="font-size:13px;" class="text-truncate" title="<?php echo e($meeting->agenda); ?>"><?php echo e($meeting->agenda); ?></div>
                        <small class="text-muted"><?php echo e(\Carbon\Carbon::parse($meeting->meeting_date)->format('d M Y')); ?></small>
                        <?php if(!$meeting->signed_student): ?>
                        <div>
                            <form method="POST" action="<?php echo e(route('meetings.sign', $meeting)); ?>" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-xs btn-warning" style="font-size:11px;padding:2px 8px;">
                                    Sign Off
                                </button>
                            </form>
                        </div>
                        <?php else: ?>
                        <small class="text-success d-block"><i class="fas fa-check me-1"></i>Signed</small>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="p-3 text-muted text-center small">No meetings yet.</div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>


<?php echo $__env->make('thesis.modals.submit-concept', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('thesis.modals.submit-proposal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('thesis.modals.submit-ethics', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('thesis.modals.submit-intent', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('thesis.modals.submit-thesis', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('thesis.modals.request-meeting', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\thesis\dashboard.blade.php ENDPATH**/ ?>