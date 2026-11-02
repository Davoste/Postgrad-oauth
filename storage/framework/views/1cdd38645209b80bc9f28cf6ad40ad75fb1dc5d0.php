

<?php $__env->startSection('title', 'Referees Confidential Report — JOOUST'); ?>

<?php $__env->startSection('content'); ?>

<style>
    /* =========================================================
       JOOUST REFEREE CONFIDENTIAL REPORT
       ========================================================= */

    .referee-page {
        min-height: 100vh;
        background: #f2f3f5;
        padding: 40px 15px 70px;
        font-family: Arial, Helvetica, sans-serif;
        color: #222;
    }

    .referee-document {
        width: 100%;
        max-width: 920px;
        margin: 0 auto;
        background: #fff;
        border: 1px solid #222;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    /* =========================================================
       HEADER
       ========================================================= */

    .report-header {
        padding: 28px 40px 22px;
        text-align: center;
        border-bottom: 2px solid #222;
    }

    .university-name {
        margin: 0;
        font-size: 20px;
        line-height: 1.35;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .2px;
    }

    .director-title {
        margin: 7px 0 0;
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .report-title {
        margin: 17px 0 0;
        font-size: 16px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .7px;
    }

    .confidential-badge {
        display: inline-block;
        margin-top: 12px;
        padding: 5px 15px;
        border: 1px solid #333;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1px;
    }

    /* =========================================================
       INTRODUCTION
       ========================================================= */

    .report-introduction {
        padding: 20px 40px;
        border-bottom: 1px solid #222;
        font-size: 13px;
        line-height: 1.65;
    }

    .report-introduction p {
        margin: 0;
    }

    .return-address {
        margin-top: 14px;
        padding-left: 20px;
        line-height: 1.5;
    }

    /* =========================================================
       FORM SECTIONS
       ========================================================= */

    .report-section {
        padding: 0 40px 28px;
    }

    .section-heading {
        margin: 26px -40px 20px;
        padding: 10px 40px;
        /* background: #eeeeee; */
        background: #0000FF;
        color: #fff;
        border-top: 1px solid #222;
        border-bottom: 1px solid #222;
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .2px;
    }

    .section-note {
        margin: -5px 0 18px;
        font-size: 11px;
        color: #666;
        line-height: 1.5;
    }

    /* =========================================================
       SECTION A — AUTO FILLED
       ========================================================= */

    .candidate-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        border: 1px solid #333;
    }

    .candidate-field {
        min-height: 72px;
        padding: 12px 14px;
        border-right: 1px solid #333;
        border-bottom: 1px solid #333;
    }

    .candidate-field:nth-child(3n) {
        border-right: 0;
    }

    .candidate-field:nth-last-child(-n + 3) {
        border-bottom: 0;
    }

    .field-caption {
        display: block;
        margin-bottom: 7px;
        font-size: 10px;
        line-height: 1.3;
        font-weight: 700;
        color: #555;
        text-transform: uppercase;
    }

    .candidate-value {
        font-size: 13px;
        line-height: 1.45;
        font-weight: 600;
    }

    .candidate-photo-panel {
        min-height: auto;
        width: 50%;
        max-width: 50%;
        padding: 12px 14px;
        border-right: 1px solid #333;
        border-bottom: 1px solid #333;
        background: #fafafa;
    }

    .candidate-photo {
        display: block;
        width: 100%;
        max-width: 180px;
        height: auto;
        border: 1px solid #d8d8d8;
        background: #f2f2f2;
        object-fit: cover;
    }

    .candidate-photo-placeholder {
        width: 100%;
        max-width: 180px;
        min-height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px dashed #b5b5b5;
        background: #f6f6f6;
        color: #666;
        font-size: 10px;
        text-align: center;
        padding: 8px;
    }

    .auto-filled-note {
        margin-top: 10px;
        font-size: 10px;
        color: #666;
        font-style: italic;
    }

    /* =========================================================
       FORM CONTROLS
       ========================================================= */

    .form-group {
        margin-bottom: 22px;
    }

    .form-label-official {
        display: block;
        margin-bottom: 8px;
        font-size: 12px;
        font-weight: 700;
        line-height: 1.45;
    }

    .required-mark {
        color: #a00000;
    }

    .official-control {
        width: 100%;
        border: 1px solid #555 !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        font-size: 13px;
    }

    .official-control:focus {
        border-color: #111 !important;
        box-shadow: 0 0 0 2px rgba(0, 0, 0, .08) !important;
    }

    textarea.official-control {
        resize: vertical;
    }

    .help-text {
        margin-top: 5px;
        font-size: 10px;
        line-height: 1.5;
        color: #666;
    }

    /* =========================================================
       RATING TABLE
       ========================================================= */

    .rating-container {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #333;
    }

    .rating-table {
        width: 100%;
        min-width: 720px;
        border-collapse: collapse;
        table-layout: fixed;
        font-size: 10px;
    }

    .rating-table th,
    .rating-table td {
        border: 1px solid #333;
        padding: 9px 6px;
        text-align: center;
        vertical-align: middle;
    }

    .rating-table th {
        background: #eeeeee;
        font-weight: 700;
        line-height: 1.25;
    }

    .rating-table th:first-child,
    .rating-table td:first-child {
        width: 35%;
        text-align: left;
    }

    .rating-table th:not(:first-child),
    .rating-table td:not(:first-child) {
        width: 10.8%;
    }

    .rating-table .criterion {
        font-weight: 600;
        line-height: 1.4;
    }

    .rating-table input[type="radio"] {
        width: 16px;
        height: 16px;
        margin: 0;
        cursor: pointer;
        accent-color: #222;
    }

    /* =========================================================
       REFEREE DETAILS
       ========================================================= */

    .referee-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        border: 1px solid #333;
    }

    .referee-field {
        padding: 12px;
        border-right: 1px solid #333;
        border-bottom: 1px solid #333;
    }

    .referee-field:nth-child(2n) {
        border-right: 0;
    }

    .referee-field:nth-last-child(-n + 2) {
        border-bottom: 0;
    }

    /* =========================================================
       CONFIDENTIALITY / SUBMISSION
       ========================================================= */

    .submission-box {
        margin-top: 10px;
        padding: 20px;
        border: 1px solid #333;
        background: #fafafa;
    }

    .confidentiality-text {
        margin: 0 0 16px;
        font-size: 11px;
        line-height: 1.6;
        color: #555;
    }

    .submit-button {
        display: inline-block;
        padding: 11px 24px;
        border: 1px solid #222;
        border-radius: 0;
        background: #0000FF;
        /* background: #222; */
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .submit-button:hover {
        /* background: #000; */
        /* background: #00ff01; */
        background: #4eb44e;
        color: #fff;
    }

    /* =========================================================
       VALIDATION
       ========================================================= */

    .validation-box {
        margin: 20px 40px 0;
        padding: 13px 16px;
        border: 1px solid #b00020;
        background: #fff5f6;
        color: #8b0018;
        font-size: 12px;
    }

    .validation-box ul {
        margin: 0;
        padding-left: 20px;
    }

    /* =========================================================
       FOOTER
       ========================================================= */

    .document-footer {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        padding: 12px 40px;
        border-top: 1px solid #222;
        font-size: 9px;
        color: #666;
    }

    /* =========================================================
       RESPONSIVE
       ========================================================= */

    @media (max-width: 700px) {

        .referee-page {
            padding: 15px 0 35px;
        }

        .referee-document {
            border-left: 0;
            border-right: 0;
        }

        .report-header {
            padding: 22px 18px;
        }

        .university-name {
            font-size: 16px;
        }

        .director-title {
            font-size: 12px;
        }

        .report-title {
            font-size: 14px;
        }

        .report-introduction {
            padding: 18px;
        }

        .report-section {
            padding-left: 18px;
            padding-right: 18px;
        }

        .section-heading {
            margin-left: -18px;
            margin-right: -18px;
            padding-left: 18px;
            padding-right: 18px;
        }

        .candidate-grid,
        .referee-grid {
            grid-template-columns: 1fr;
        }

        .candidate-field,
        .referee-field {
            border-right: 0 !important;
            border-bottom: 1px solid #333 !important;
        }

        .candidate-field:last-child,
        .referee-field:last-child {
            border-bottom: 0 !important;
        }

        .validation-box {
            margin-left: 18px;
            margin-right: 18px;
        }

        .document-footer {
            padding-left: 18px;
            padding-right: 18px;
            flex-direction: column;
        }
    }
</style>

<div class="referee-page">


<div class="referee-document">

    

    <header class="report-header">

        <h1 class="university-name">
            JARAMOGI OGINGA ODINGA UNIVERSITY
            OF SCIENCE &amp; TECHNOLOGY
        </h1>

        <div class="director-title">
            Director, Board of Postgraduate Studies
        </div>

        <div class="report-title">
            Referees Confidential Report
        </div>

        <div class="confidential-badge">
            CONFIDENTIAL
        </div>

    </header>


    

    <div class="report-introduction">

        <p>
            The candidate whose name is given below wishes to undertake
            postgraduate studies in the University. The University would
            be grateful for your comments on the candidate's suitability
            for this programme.
        </p>

    </div>


    

    <?php if($errors->any()): ?>

        <div class="validation-box">

            <strong>Please correct the following:</strong>

            <ul class="mt-2">

                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </ul>

        </div>

    <?php endif; ?>


    <form
        method="POST"
        action="<?php echo e(route('referee.respond', $referee->token)); ?>"
        enctype="multipart/form-data"
    >

        <?php echo csrf_field(); ?>


        

        <section class="report-section">

            <div class="section-heading">
                Section A: Candidate Information
            </div>

            <div class="section-note">
                The information in this section has been automatically
                populated from the candidate's postgraduate application.
                
            </div>


            <div class="candidate-grid">

                
                <div class="candidate-field">

                    <span class="field-caption">
                        1. Name of Candidate
                    </span>

                    <div class="candidate-value">
                        <?php echo e($referee->application->full_name ?? '—'); ?>

                    </div>

                </div>


                
                <div class="candidate-field">

                    <span class="field-caption">
                        2. Degree Applied For
                    </span>

                    <div class="candidate-value">
                        <?php echo e($referee->application->programme->name ?? '—'); ?>

                    </div>

                </div>

                
                <div class="candidate-field candidate-photo-panel">
                    <span class="field-caption">
                        5. Passport Photo
                    </span>

                    <?php
                        $candidatePhotoPath = $referee->application->passport_photo_path ?? null;
                        $candidatePhotoUrl = $candidatePhotoPath
                            ? asset('storage/' . ltrim($candidatePhotoPath, '/'))
                            : null;
                    ?>

                    <?php if($candidatePhotoUrl): ?>
                        <img
                            src="<?php echo e($candidatePhotoUrl); ?>"
                            alt="Passport photo for <?php echo e($referee->application->full_name ?? 'candidate'); ?>"
                            class="candidate-photo"
                        >
                    <?php else: ?>
                        <div class="candidate-photo-placeholder">
                            No passport photo attached
                        </div>
                    <?php endif; ?>
                </div>

                
                <div class="candidate-field">

                    <span class="field-caption">
                        3. Department / Faculty
                    </span>

                    <div class="candidate-value">
                        <?php echo e($referee->application->programme->department->name ?? '—'); ?>

                    </div>

                </div>


                
                <div class="candidate-field">

                    <span class="field-caption">
                        4. Field of Study / Subject
                    </span>

                    <div class="candidate-value">
                        <?php echo e($referee->application->programme->field_of_study
                            ?? $referee->application->programme->name
                            ?? '—'); ?>

                    </div>

                </div>

                

            </div>

            <div class="auto-filled-note">
                Candidate information is read-only and is retrieved directly
                from the submitted postgraduate application.
            </div>

        </section>


        

        <section class="report-section">

            <div class="section-heading">
                Section B: To Be Completed by the Referee
            </div>


            

            <div class="form-group">

                <label
                    for="known_for"
                    class="form-label-official"
                >
                    5. For how long and in what capacity have you known
                    the candidate?
                    <span class="required-mark">*</span>
                </label>

                <textarea
                    id="known_for"
                    name="known_for"
                    rows="4"
                    required
                    minlength="20"
                    maxlength="3000"
                    class="form-control official-control"
                ><?php echo e(old('known_for')); ?></textarea>

                <div class="help-text">
                    Please indicate the period you have known the candidate
                    and your professional/academic relationship with them.
                </div>

            </div>


            

            <div class="form-group">

                <label class="form-label-official">
                    6. Please rate the candidate:
                </label>

                <?php

                    $criteria = [

                        'academic_ability' =>
                            'Academic ability',

                        'persistent_independent_study' =>
                            'Ability for persistent & independent study',

                        'productive_scholarship' =>
                            'Potential for productive scholarship',

                        'previous_work' =>
                            'Quality of previous work',

                        'english_expression' =>
                            'Oral and written expression in English',

                    ];

                    $ratings = [

                        'excellent' =>
                            'Excellent',

                        'very_good' =>
                            'V. Good',

                        'good' =>
                            'Good',

                        'average' =>
                            'Average',

                        'below_average' =>
                            'Below Average',

                        'unable_to_assess' =>
                            'Unable to Assess',

                    ];

                ?>


                <div class="rating-container">

                    <table class="rating-table">

                        <thead>

                            <tr>

                                <th>
                                    Assessment Area
                                </th>

                                <?php $__currentLoopData = $ratings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ratingKey => $ratingLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                    <th>
                                        <?php echo e($ratingLabel); ?>

                                    </th>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </tr>

                        </thead>


                        <tbody>

                            <?php $__currentLoopData = $criteria; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $criterionKey => $criterion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <tr>

                                    <td class="criterion">
                                        <?php echo e($criterion); ?>

                                    </td>

                                    <?php $__currentLoopData = $ratings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ratingKey => $ratingLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                        <td>

                                            <input
                                                type="radio"
                                                name="ratings[<?php echo e($criterionKey); ?>]"
                                                value="<?php echo e($ratingKey); ?>"
                                                aria-label="<?php echo e($criterion); ?> - <?php echo e($ratingLabel); ?>"
                                                <?php echo e(old("ratings.$criterionKey") === $ratingKey ? 'checked' : ''); ?>

                                            >

                                        </td>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </tr>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </tbody>

                    </table>

                </div>

                <div class="help-text">
                    Please select one rating for each assessment area.
                </div>

            </div>


            

            <div class="form-group">

                <label
                    for="recommendation"
                    class="form-label-official"
                >
                    7. Comment freely on the candidate:
                    <span class="required-mark">*</span>
                </label>

                <textarea
                    id="recommendation"
                    name="recommendation"
                    rows="10"
                    required
                    minlength="50"
                    maxlength="10000"
                    class="form-control official-control"
                ><?php echo e(old('recommendation')); ?></textarea>

                <div class="help-text">
                    Please provide an honest assessment of the candidate's
                    suitability for postgraduate study. Minimum 50 characters.
                </div>

            </div>


            

            <div class="form-group">

                <label class="form-label-official">
                    8. Referee Details
                </label>


                <div class="referee-grid">

                    
                    <div class="referee-field">

                        <label
                            for="referee_name"
                            class="field-caption"
                        >
                            Name of Referee
                        </label>

                        <input
                            id="referee_name"
                            type="text"
                            name="referee_name"
                            value="<?php echo e(old('referee_name', $referee->name)); ?>"
                            required
                            class="form-control official-control"
                        >

                    </div>


                    
                    <div class="referee-field">

                        <label
                            for="designation"
                            class="field-caption"
                        >
                            Designation
                        </label>

                        <input
                            id="designation"
                            type="text"
                            name="designation"
                            value="<?php echo e(old('designation')); ?>"
                            class="form-control official-control"
                        >

                    </div>


                    
                    <div class="referee-field">

                        <label
                            for="referee_address"
                            class="field-caption"
                        >
                            Referee Address
                        </label>

                        <textarea
                            id="referee_address"
                            name="referee_address"
                            rows="4"
                            class="form-control official-control"
                        ><?php echo e(old('referee_address')); ?></textarea>

                    </div>


                    
                    <div class="referee-field">

                        <label
                            for="referee_contact"
                            class="field-caption"
                        >
                            Referee Email / Telephone
                        </label>

                        <input
                            id="referee_contact"
                            type="text"
                            name="referee_contact"
                            value="<?php echo e(old('referee_contact')); ?>"
                            class="form-control official-control"
                        >

                    </div>

                </div>

            </div>


            

            <div class="form-group">

                <label
                    for="attachment"
                    class="form-label-official"
                >
                    Supporting Letter
                    <span style="font-weight:400; color:#666;">
                        (Optional)
                    </span>
                </label>

                <input
                    id="attachment"
                    type="file"
                    name="attachment"
                    accept=".pdf,.doc,.docx"
                    class="form-control official-control"
                >

                <div class="help-text">
                    PDF, DOC or DOCX, maximum 10 MB.
                </div>

            </div>


            

            <div class="submission-box">

                <p class="confidentiality-text">

                    <strong>Confidentiality:</strong>
                    This report is submitted confidentially to the
                    Director, Board of Postgraduate Studies, and will
                    form part of the candidate's postgraduate admission
                    record.

                </p>

                <button
                    type="submit"
                    class="btn submit-button"
                >
                    Submit Confidential Recommendation
                </button>

            </div>

        </section>

    </form>


    

    <footer class="document-footer">

        <span>
            JOOUST/AA/BPS/ADM-AF/01B
        </span>

        <span>
            Board of Postgraduate Studies
        </span>

    </footer>

</div>


</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\referees\respond.blade.php ENDPATH**/ ?>