<?php $__env->startSection('title', 'Thesis Detail'); ?>
<?php $__env->startSection('page-title', 'Thesis Detail'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="<?php echo e(route('admin.theses')); ?>" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i></a>
    <h5 class="mb-0 fw-semibold"><?php echo e($thesis->title); ?></h5>
</div>

<div class="row g-4">
    
    <div class="col-lg-8">

        
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">Overview</h6>
                <?php
                $stageColors=['concept_development'=>'secondary','proposal_development'=>'info','ethical_approval'=>'warning','data_collection'=>'primary','writing_thesis'=>'info','intent_to_submit'=>'warning','submitted'=>'primary','examination'=>'warning','graduated'=>'success'];
                ?>
                <span class="badge bg-<?php echo e($stageColors[$thesis->current_stage] ?? 'secondary'); ?>"><?php echo e($thesis->stage_label); ?></span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="text-muted small">Student</div>
                        <div class="fw-medium"><?php echo e($thesis->student->user->name); ?></div>
                        <div class="text-muted small"><?php echo e($thesis->student->reg_no); ?> &bull; <?php echo e($thesis->student->program); ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Supervisor</div>
                        <?php if($thesis->supervisor): ?>
                            <div class="fw-medium"><?php echo e($thesis->supervisor->user->name); ?></div>
                            <div class="text-muted small"><?php echo e($thesis->supervisor->department); ?></div>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark">Unassigned</span>
                            <div class="mt-1">
                                <a href="<?php echo e(route('admin.thesis.allocate-supervisor.form', $thesis)); ?>" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-user-plus me-1"></i>Assign Supervisor
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Progress</div>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <div class="progress flex-grow-1" style="height:8px;">
                                <div class="progress-bar bg-success" style="width:<?php echo e($thesis->progress_percentage); ?>%;"></div>
                            </div>
                            <small class="fw-medium"><?php echo e(number_format($thesis->progress_percentage, 0)); ?>%</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Started</div>
                        <div class="fw-medium"><?php echo e($thesis->created_at->format('d M Y')); ?></div>
                    </div>
                </div>

                
                <?php if(Auth::user()->hasRole('admin')): ?>
                <hr>
                <form method="POST" action="<?php echo e(route('thesis.transition', $thesis)); ?>" class="row g-2 align-items-end">
                    <?php echo csrf_field(); ?>
                    <div class="col-md-5">
                        <label class="form-label small fw-medium">Move to Stage</label>
                        <select name="transition" class="form-select form-select-sm" required>
                            <option value="">Select stage…</option>
                            <?php $__currentLoopData = \App\Models\Thesis::STAGE_LABELS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($key !== $thesis->current_stage): ?>
                                <option value="<?php echo e($key); ?>"><?php echo e($label); ?></option>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small fw-medium">Notes</label>
                        <input type="text" name="notes" class="form-control form-control-sm" placeholder="Optional note">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-sm btn-primary w-100">Move</button>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold">Stage Progress</h6>
            </div>
            <div class="card-body">
                <?php $__currentLoopData = $stageProgress; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage => $progress): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="flex-shrink-0 mt-1">
                        <?php if($progress['status'] === 'completed'): ?>
                            <i class="fas fa-check-circle text-success"></i>
                        <?php elseif($progress['status'] === 'current'): ?>
                            <i class="fas fa-dot-circle text-primary"></i>
                        <?php else: ?>
                            <i class="far fa-circle text-muted"></i>
                        <?php endif; ?>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-medium" style="font-size:13px;"><?php echo e($progress['name']); ?></div>
                        <?php if($progress['submission']): ?>
                            <small class="text-success">Submitted: <?php echo e($progress['submission']->submitted_at->format('d M Y')); ?></small>
                        <?php endif; ?>
                        <?php if($progress['feedback']): ?>
                            <small class="ms-2 text-<?php echo e($progress['feedback']->decision === 'approved' ? 'success' : ($progress['feedback']->decision === 'rejected' ? 'danger' : 'warning')); ?>">
                                &bull; <?php echo e(ucfirst($progress['feedback']->decision)); ?>

                            </small>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <?php if($thesis->progressReports->count() > 0): ?>
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">Progress Reports</h6>
                <a href="<?php echo e(route('progress-reports.index', $thesis)); ?>" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="font-size:12px;">Type</th>
                            <th style="font-size:12px;">Period</th>
                            <th style="font-size:12px;">Supervisor</th>
                            <th style="font-size:12px;">Rating</th>
                            <th style="font-size:12px;">Acknowledged</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $thesis->progressReports->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><span class="badge bg-<?php echo e($report->report_type === 'quarterly' ? 'info' : 'primary'); ?>"><?php echo e(ucfirst($report->report_type)); ?></span></td>
                            <td><small><?php echo e(\Carbon\Carbon::parse($report->period_start)->format('M Y')); ?> – <?php echo e(\Carbon\Carbon::parse($report->period_end)->format('M Y')); ?></small></td>
                            <td style="font-size:13px;"><?php echo e($report->supervisor->user->name); ?></td>
                            <td>
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star<?php echo e($i <= $report->student_progress_rating ? '' : '-o'); ?> text-<?php echo e($i <= $report->student_progress_rating ? 'warning' : 'muted'); ?>" style="font-size:11px;"></i>
                                <?php endfor; ?>
                            </td>
                            <td>
                                <?php if($report->isAcknowledged()): ?>
                                    <span class="badge bg-success">Yes</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">No</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>

    
    <div class="col-lg-4">

        
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold">Recent Meetings</h6>
            </div>
            <div class="card-body p-0">
                <?php $__empty_1 = true; $__currentLoopData = $thesis->meetings->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meeting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="d-flex align-items-start gap-2 p-3 border-bottom">
                    <i class="fas fa-calendar-check mt-1 <?php echo e(($meeting->signed_student && $meeting->signed_supervisor) ? 'text-success' : 'text-warning'); ?>"></i>
                    <div>
                        <div style="font-size:13px;" class="text-truncate" style="max-width:200px;" title="<?php echo e($meeting->agenda); ?>"><?php echo e($meeting->agenda); ?></div>
                        <small class="text-muted"><?php echo e(\Carbon\Carbon::parse($meeting->meeting_date)->format('d M Y')); ?></small>
                        <?php if(!($meeting->signed_student && $meeting->signed_supervisor)): ?>
                            <small class="text-warning d-block">Pending sign-off</small>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="p-3 text-muted text-center small">No meetings yet.</div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold">Milestones</h6>
            </div>
            <div class="card-body p-0">
                <?php $__empty_1 = true; $__currentLoopData = $thesis->milestones->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $milestone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="d-flex align-items-start gap-2 p-3 border-bottom">
                    <i class="fas fa-flag mt-1 text-<?php echo e($milestone->status === 'completed' ? 'success' : (optional($milestone->target_date)->isPast() ? 'danger' : 'warning')); ?>"></i>
                    <div>
                        <div style="font-size:13px;"><?php echo e($milestone->milestone_name); ?></div>
                        <small class="text-<?php echo e(optional($milestone->target_date)->isPast() && $milestone->status !== 'completed' ? 'danger' : 'muted'); ?>">
                            Due: <?php echo e(optional($milestone->target_date)->format('d M Y')); ?>

                        </small>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="p-3 text-muted text-center small">No milestones set.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\admin\thesis-detail.blade.php ENDPATH**/ ?>