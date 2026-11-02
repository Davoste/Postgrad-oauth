<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Supervisor Allocated</title>
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

                       <div style="border: 1px solid #ddd; border-top:none; padding: 24px; border-radius: 0 0 6px 6px;">
                            <?php if($recipientRole === 'student'): ?>
                                <p>Dear <?php echo e($thesis->student->user->name); ?>,</p>
                                <p>A supervisor has been assigned to your thesis.</p>
                                <p><strong>Supervisor:</strong> <?php echo e($supervisor->user->name); ?> (<?php echo e($supervisor->department); ?>)</p>
                                <p><strong>Thesis:</strong> <?php echo e($thesis->title); ?></p>
                                <p>Your supervisor will be in contact shortly. You may now begin scheduling supervision meetings.</p>
                            <?php else: ?>
                                <p>Dear <?php echo e($supervisor->user->name); ?>,</p>
                                <p>A new student has been assigned to your supervision.</p>
                                <p><strong>Student:</strong> <?php echo e($thesis->student->user->name); ?> (<?php echo e($thesis->student->reg_no); ?>)</p>
                                <p><strong>Programme:</strong> <?php echo e($thesis->student->program); ?></p>
                                <p><strong>Thesis:</strong> <?php echo e($thesis->title); ?></p>
                                <p>Please log in to the portal to review the student's current thesis and begin supervision.</p>
                            <?php endif; ?>
                            
                        </div>

                        <table width="100%" cellpadding="0" cellspacing="0" style="margin: 24px 0 16px;">
                            <tr>
                                <td align="center">
                                    <a href="<?php echo e(route('login')); ?>"
                                       style="display:inline-block;
                                              background:<?php echo e(brand('accent_color')); ?>;
                                              color:#ffffff;
                                              font-size:15px;
                                              font-weight:600;
                                              text-decoration:none;
                                              padding:14px 36px;
                                              border-radius:8px;">
                                        Sign In to the Portal
                                    </a>
                                </td>
                            </tr>
                        </table>
                        <p style="margin:0 0 24px; font-size:12px; color:#64748b; text-align:center;">
                            Or visit <a href="<?php echo e(route('login')); ?>" style="color:<?php echo e(brand('primary_color')); ?>;">the portal sign-in page</a>.
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
<?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\emails\supervisor-allocated.blade.php ENDPATH**/ ?>