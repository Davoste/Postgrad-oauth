<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Submission Received</title>
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
                            $stageName = \App\Models\Thesis::STAGE_LABELS[$submission->stage] ?? ucfirst(str_replace('_', ' ', $submission->stage));
                        ?>
                        <p style="margin:0 0 6px; font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#64748b;">New Submission</p>
                        <h2 style="margin:0 0 24px; font-size:22px; color:#1a202c;"><?php echo e($stageName); ?></h2>

                        
                        <p style="margin:0 0 16px; font-size:15px; color:#374151; line-height:1.6;">
                            Dear
                            <?php if($recipientRole === 'supervisor'): ?>
                                <?php echo e(optional(optional($thesis->supervisor)->user)->name ?? 'Supervisor'); ?>,
                            <?php else: ?>
                                Ethics Reviewer,
                            <?php endif; ?>
                        </p>
                        <p style="margin:0 0 24px; font-size:15px; color:#374151; line-height:1.6;">
                            A new <strong><?php echo e($stageName); ?></strong> submission has been received and requires your review.
                        </p>

                        
                        <table width="100%" cellpadding="0" cellspacing="0"
                               style="border: 1px solid #e2e8f0; border-radius:8px; overflow:hidden; margin-bottom:28px; font-size:14px;">
                            <tr style="background:#f8fafc;">
                                <td style="padding:10px 16px; color:#64748b; font-weight:600; width:35%; border-bottom:1px solid #e2e8f0;">Student</td>
                                <td style="padding:10px 16px; color:#1a202c; border-bottom:1px solid #e2e8f0;"><?php echo e($thesis->student->user->name); ?></td>
                            </tr>
                            <tr>
                                <td style="padding:10px 16px; color:#64748b; font-weight:600; background:#f8fafc; border-bottom:1px solid #e2e8f0;">Reg. No.</td>
                                <td style="padding:10px 16px; color:#1a202c; border-bottom:1px solid #e2e8f0;"><?php echo e($thesis->student->reg_no ?? $thesis->student->student_id ?? '—'); ?></td>
                            </tr>
                            <tr style="background:#f8fafc;">
                                <td style="padding:10px 16px; color:#64748b; font-weight:600; border-bottom:1px solid #e2e8f0;">Thesis Title</td>
                                <td style="padding:10px 16px; color:#1a202c; border-bottom:1px solid #e2e8f0;"><?php echo e($thesis->title); ?></td>
                            </tr>
                            <tr>
                                <td style="padding:10px 16px; color:#64748b; font-weight:600; background:#f8fafc; border-bottom:1px solid #e2e8f0;">Stage</td>
                                <td style="padding:10px 16px; color:#1a202c; border-bottom:1px solid #e2e8f0;"><?php echo e($stageName); ?></td>
                            </tr>
                            <tr style="background:#f8fafc;">
                                <td style="padding:10px 16px; color:#64748b; font-weight:600;">Submitted</td>
                                <td style="padding:10px 16px; color:#1a202c;"><?php echo e($submission->submitted_at->format('d M Y, H:i')); ?></td>
                            </tr>
                        </table>

                        
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:32px;">
                            <tr>
                                <td align="center">
                                    <a href="<?php echo e(route('admin.thesis-reviews')); ?>"
                                       style="display:inline-block;
                                              background:<?php echo e(brand('accent_color')); ?>;
                                              color:#ffffff;
                                              font-size:15px;
                                              font-weight:600;
                                              text-decoration:none;
                                              padding:14px 36px;
                                              border-radius:8px;">
                                        Review Submission
                                    </a>
                                </td>
                            </tr>
                        </table>

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
<?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\emails\submission-received.blade.php ENDPATH**/ ?>