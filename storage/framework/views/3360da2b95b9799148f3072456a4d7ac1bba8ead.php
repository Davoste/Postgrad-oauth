<?php $__env->startSection('title', brand('name') . ' — Apply'); ?>

<?php $__env->startSection('content'); ?>

<div class="section-divider"></div>
<section class="hero-overlay py-5" >
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <span class="section-label">Board of Postgraduate Studies</span>
                <h1 class="display-4 fw-bold mt-2 mb-3">Advance your research journey at <?php echo e(brand('short_name')); ?></h1>
                <p class="fs-5 opacity-75 mb-4" style="max-width:36rem;">
                    Apply online for Masters, PhD and Postgraduate Diploma programmes — then track your
                    application, accept your offer, and manage your entire thesis journey in one portal.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="<?php echo e(route('register')); ?>" class="btn btn-gold btn-lg px-4"><i class="fas fa-file-signature me-2"></i>Start your application</a>
                    <a href="#programmes" class="btn btn-outline-light btn-lg px-4">Explore programmes</a>
                </div>
                <div class="d-flex flex-wrap gap-4 mt-4 pt-2 small opacity-75">
                    <span><i class="fas fa-check-circle me-2 text-warning"></i>Fully online application</span>
                    <span><i class="fas fa-check-circle me-2 text-warning"></i>Track your status anytime</span>
                    <span><i class="fas fa-check-circle me-2 text-warning"></i>Instant enrolment on offer</span>
                </div>
            </div>
            <div class="col-lg-5 text-center d-none d-lg-block">
                <div class="bg-white bg-opacity-10 rounded-4 p-5 border border-light border-opacity-25">
                    <img src="<?php echo e(brand('logo_url')); ?>" alt="<?php echo e(brand('short_name')); ?>" style="width:140px;height:auto;">
                    <div class="mt-3 fw-semibold"><?php echo e(brand('name')); ?></div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="section-divider"></div>


<section class="bg-white border-bottom py-4">
    <div class="container">
        <div class="row text-center g-3">
            <div class="col-6 col-md-3"><div class="fs-3 fw-bold text-jooust"><?php echo e($programmeCount ?? '80+'); ?></div><div class="text-muted small">Programmes</div></div>
            <div class="col-6 col-md-3"><div class="fs-3 fw-bold text-jooust"><?php echo e($schoolCount ?? 9); ?></div><div class="text-muted small">Schools</div></div>
            <div class="col-6 col-md-3"><div class="fs-3 fw-bold text-jooust">2</div><div class="text-muted small">Masters &amp; PhD</div></div>
            <div class="col-6 col-md-3"><div class="fs-3 fw-bold text-jooust">100%</div><div class="text-muted small">Online process</div></div>
        </div>
    </div>
</section>
<div class="section-divider"></div>

<section id="programmes" class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-label1 section-divider1">Call to Action</span>
            
            <h2 class="fw-bold text-jooust mt-2">EAC Scholarship programme</h2>
        </div>
        
        <div class="row g-4 align-items-center">
            <!-- Poster column -->
            <div class="col-lg-5">
                <a href="#" data-bs-toggle="modal" data-bs-target="#posterModal" class="d-inline-block">
                    <img src="<?php echo e(asset('assets/img/EAC.jpeg')); ?>" 
                        alt="EAC Scholarship Poster" 
                        class="img-fluid rounded shadow-sm"
                        style="max-height: 480px; width: 100%; object-fit: contain; cursor: zoom-in;">
                </a>
                <small class="d-block text-muted mt-1 text-center">Click image to view full size</small>
            </div>

            <!-- Text column -->
            <div class="col-lg-7">
                <p class="mb-4">
                    The Jaramogi Oginga Odinga University of Science and Technology (JOOUST) invites suitably qualified nationals of the East African Community (EAC) to apply for cohort 4 of the KfW–funded Scholarships under the Inter-University Council for East Africa (IUCEA) to pursue Master's studies. The applicants must be citizens of an EAC Partner State other than Kenya. In addition to applying for admission to JOOUST Master’s programmes, applicants are required to apply for a scholarship through IUCEA.
                </p>
                <div class="text-center text-lg-start">
                    <a href="<?php echo e(route('announcements.index')); ?>" class="btn btn-blue px-4">
                        Details <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Full-size Poster Modal -->
        <div class="modal fade" id="posterModal" tabindex="-1" aria-labelledby="posterModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content bg-transparent border-0">
                    <div class="modal-body p-0 text-center position-relative">
                        <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" 
                                data-bs-dismiss="modal" aria-label="Close" style="z-index: 10;"></button>
                        <img src="<?php echo e(asset('assets/img/EAC.jpeg')); ?>" 
                            alt="EAC Scholarship Poster - Full Size" 
                            class="img-fluid rounded"
                            style="max-height: 90vh; width: auto;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="section-divider1"></div>

<section id="programmes" class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-label">What you can study</span>
            <h2 class="fw-bold text-jooust mt-2">Postgraduate programmes</h2>
        </div>
        <div class="row g-4">
            <?php $__empty_1 = true; $__currentLoopData = ($programmes ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <span class="badge bg-jooust mb-2"><?php echo e($p->level_label); ?></span>
                            <h5 class="fw-semibold text-jooust"><?php echo e($p->name); ?></h5>
                            <p class="text-muted small mb-3"><?php echo e(\Illuminate\Support\Str::limit($p->overview, 110) ?: $p->department->school->name); ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small text-muted"><i class="fas fa-clock me-1"></i><?php echo e($p->duration_years); ?> yrs</span>
                                <a href="<?php echo e(route('apply', ['programme' => $p->id])); ?>" class="btn btn-sm btn-blue">Apply <i class="fas fa-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12 text-center text-muted py-4">Programmes will be published shortly. <a href="<?php echo e(route('apply')); ?>">Start an application</a>.</div>
            <?php endif; ?>
        </div>
        <div class="text-center mt-4">
            <a href="<?php echo e(route('programmes.index')); ?>" class="btn btn-blue px-4">View all programmes <i class="fas fa-arrow-right ms-1"></i></a>
        </div>
    </div>
</section>
<div class="section-divider"></div>

<section id="how" class="py-5 bg-white border-top">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-label">Admissions</span>
            <h2 class="fw-bold text-jooust mt-2">How to apply</h2>
        </div>
        <div class="row g-4">
            <?php $__currentLoopData = [
                ['fa-list-check','Choose a programme','Browse programmes and confirm you meet the entry requirements.'],
                ['fa-file-pen','Submit your application','Complete the online ADM-AF/01A form with your details and two referees.'],
                ['fa-user-check','Departmental review','The HOD reviews and recommends your application to the Board.'],
                ['fa-graduation-cap','Board decision & offer','The Director, BPS makes the final decision; you are notified by email.'],
                ['fa-right-to-bracket','Accept & enrol','Accept online — your student account and admission number are created instantly.'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6 col-lg">
                    <div class="text-center px-2">
                        <div class="rounded-circle bg-jooust text-white d-inline-flex align-items-center justify-content-center mb-3" style="width:56px;height:56px;"><i class="fas <?php echo e($s[0]); ?> fa-lg"></i></div>
                        <div class="fw-bold text-muted small mb-1">STEP <?php echo e($i+1); ?></div>
                        <h6 class="fw-semibold"><?php echo e($s[1]); ?></h6>
                        <p class="text-muted small mb-0"><?php echo e($s[2]); ?></p>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="text-center mt-5">
            <a href="<?php echo e(route('apply')); ?>" class="btn btn-gold btn-lg px-5">Begin your application <i class="fas fa-arrow-right ms-2"></i></a>
        </div>
    </div>
</section>
<div class="section-divider"></div>

<section class="hero2 py-5">
    <div class="container text-center py-3">
        <h3 class="fw-bold mb-2 ">NOTE</h3>
        <p class=" mb-4">All fees including admission fees should be paid directly to <strong>Equity Bank, Account Number 0750294239156, 
            Bondo Branch</strong> or <strong>Cooperative
            Bank, Account No.01129255709500, Siaya Branch</strong> or
            any other branches of the banks countrywide. You will be required to submit original bank slip indicating the amount
            of money paid. <strong>Cash, personal or institutional cheques are not acceptable.</strong> 
        </p>
        <p class=" mb-4">
            Application fee for a masters programme is <strong>Kshs. 3,000</strong> and for a PhD programme is <strong>Kshs. 3,500</strong>.
        </p>
        <p class=" mb-4">
            For more information or clarifications, please contact the Board of Postgraduate Studies at <strong><?php echo e(brand('support_email')); ?></strong>
        </p>
        
    </div>
</section>
<div class="section-divider"></div>

<section class="hero py-5">
    <div class="container text-center py-3">
        <h3 class="fw-bold mb-2">Already applied?</h3>
        <p class="opacity-75 mb-4">Track your application status or respond to your offer of admission.</p>
        <a href="<?php echo e(route('login')); ?>" class="btn btn-outline-light btn-lg px-4">Sign in to the portal</a>
    </div>
</section>

<div class="section-divider"></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\welcome.blade.php ENDPATH**/ ?>