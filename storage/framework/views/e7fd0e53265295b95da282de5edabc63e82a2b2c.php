<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Feedback on Your Submission</title>
</head>
<body style="margin:0; padding:0; background:#f4f6f9; font-family: Arial, Helvetica, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f9; padding: 40px 0;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%;">

                
                <tr>
                    <td style="background: linear-gradient(180deg, <?php echo e(brand('primary_color')); ?> 0%, <?php echo e(brand('accent_color')); ?> 100%);
                                border-radius: 16px 16px 0 0;
                                padding: 36px 40px;
                                text-align: center;">
                            <img src="<?php echo e(brand('logo_url')); ?>"
                                alt="<?php echo e(brand('short_name')); ?>" width="56" height="56"
                             style="border-radius:12px; display:block; margin: 0 auto 16px;">
                            <h1 style="margin:0; color:#ffffff; font-size:20px; font-weight:700;"><?php echo e(brand('short_name')); ?> Postgraduate Portal</h1>
                            <p style="margin:6px 0 0; color:rgba(255,255,255,.75); font-size:13px;"><?php echo e(brand('tagline')); ?></p>
                    </td>
                </tr>

                
                <tr>
                    <td style="background:#ffffff;
                                border: 1px solid #e2e8f0;
                                border-top: none;
                                border-radius: 0 0 16px 16px;
                                padding: 40px;">

                        <?php
                            $stageName   = \App\Models\Thesis::STAGE_LABELS[$submission->stage] ?? ucfirst(str_replace('_', ' ', $submission->stage));
                            $decisionStr = ucfirst(str_replace('_', ' ', $decision));
                            $colour      = $decision === 'approved' ? '#16a34a' : ($decision === 'rejected' ? '#dc2626' : '#d97706');
                            $bgColour    = $decision === 'approved' ? '#f0fdf4' : ($decision === 'rejected' ? '#fef2f2' : '#fffbeb');
                            $borderColour= $decision === 'approved' ? '#bbf7d0' : ($decision === 'rejected' ? '#fecaca' : '#fde68a');
                        ?>

                        <p style="margin:0 0 6px; font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#64748b;">Submission Feedback</p>
                        <h2 style="margin:0 0 24px; font-size:22px; color:#1a202c;"><?php echo e($stageName); ?></h2>

                        <p style="margin:0 0 16px; font-size:15px; color:#374151; line-height:1.6;">
                            Dear <?php echo e($thesis->student->user->name); ?>,
                        </p>
                        <p style="margin:0 0 24px; font-size:15px; color:#374151; line-height:1.6;">
                            Your <strong><?php echo e($stageName); ?></strong> submission has been reviewed
                            <?php if($reviewerName): ?> by <strong><?php echo e($reviewerName); ?></strong><?php endif; ?>.
                            Please review the decision and feedback below.
                        </p>

                        
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
                            <tr>
                                <td style="background:<?php echo e($bgColour); ?>;
                                           border: 1px solid <?php echo e($borderColour); ?>;
                                           border-left: 4px solid <?php echo e($colour); ?>;
                                           border-radius:8px;
                                           padding:16px 20px;">
                                    <p style="margin:0 0 4px; font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:<?php echo e($colour); ?>;">Decision</p>
                                    <p style="margin:0; font-size:18px; font-weight:700; color:<?php echo e($colour); ?>;"><?php echo e($decisionStr); ?></p>
                                </td>
                            </tr>
                        </table>

                        
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:28px;">
                            <tr>
                                <td style="background:#f8fafc;
                                           border: 1px solid #e2e8f0;
                                           border-radius:8px;
                                           padding:20px;">
                                    <p style="margin:0 0 10px; font-size:13px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.05em;">Feedback Comments</p>
                                    <p style="margin:0; font-size:14px; color:#374151; line-height:1.7; white-space:pre-wrap;"><?php echo e($comments); ?></p>
                                </td>
                            </tr>
                        </table>

                        <?php if($decision === 'revision_required'): ?>
                        
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:28px;">
                            <tr>
                                <td style="background:#fffbeb; border:1px solid #fde68a; border-left:4px solid #f59e0b; border-radius:0 8px 8px 0; padding:14px 16px;">
                                    <p style="margin:0; font-size:13px; color:#92400e; line-height:1.5;">
                                        <strong>Action Required:</strong> Please address the feedback and resubmit your work through the portal.
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <?php endif; ?>

                        
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:32px;">
                            <tr>
                                <td align="center">
                                    <a href="<?php echo e(route('thesis.feedback')); ?>"
                                       style="display:inline-block;
                                              background:<?php echo e(brand('accent_color')); ?>;
                                              color:#ffffff;
                                              font-size:15px;
                                              font-weight:600;
                                              text-decoration:none;
                                              padding:14px 36px;
                                              border-radius:8px;">
                                        View Full Feedback
                                    </a>
                                </td>
                            </tr>
                        </table>
                        <p style="margin:-20px 0 32px; font-size:12px; color:#64748b; text-align:center;">
                            If the button doesn't work, visit <a href="<?php echo e(route('thesis.feedback')); ?>" style="color:<?php echo e(brand('primary_color')); ?>;">your feedback page</a>.
                        </p>

                        <hr style="border:none; border-top:1px solid #e2e8f0; margin:0 0 24px;">
                        <p style="margin:0; font-size:12px; color:#94a3b8; text-align:center; line-height:1.6;">
                            This is an automated message from the <?php echo e(brand('short_name')); ?> Postgraduate Portal.<br>
                            <?php echo e(brand('name')); ?><br>
                            &copy; <?php echo e(date('Y')); ?> All rights reserved.
                        </p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>

</body>
</html>
<?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\emails\feedback-given.blade.php ENDPATH**/ ?>