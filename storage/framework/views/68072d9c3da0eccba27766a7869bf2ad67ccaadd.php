<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Your Password</title>
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
                                alt="<?php echo e(brand('short_name')); ?>"
                             width="56" height="56"
                             style="border-radius:12px; margin-bottom:16px; display:block; margin-left:auto; margin-right:auto;">
                        <h1 style="margin:0; color:#ffffff; font-size:22px; font-weight:700; letter-spacing:.02em;">
                            <?php echo e(brand('short_name')); ?> Postgraduate Portal
                        </h1>
                        <p style="margin:6px 0 0; color:rgba(255,255,255,.75); font-size:14px;">
                            <?php echo e(brand('tagline')); ?>

                        </p>
                    </td>
                </tr>

                
                <tr>
                    <td style="background:#ffffff;
                                border: 1px solid #e2e8f0;
                                border-top: none;
                                border-radius: 0 0 16px 16px;
                                padding: 40px;">

                        <h2 style="margin: 0 0 8px; font-size:20px; color:#1a202c;">Password Reset Request</h2>
                        <p style="margin: 0 0 24px; color:#64748b; font-size:14px;">
                            Hi <?php echo e($user->name); ?>,
                        </p>
                        <p style="margin: 0 0 24px; color:#374151; font-size:15px; line-height:1.6;">
                            We received a request to reset the password for your account. Click the button below to choose a new password.
                            This link will expire in <strong>60 minutes</strong>.
                        </p>

                        
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin: 0 0 32px;">
                            <tr>
                                <td align="center">
                                    <a href="<?php echo e($url); ?>"
                                       style="display:inline-block;
                                                background:<?php echo e(brand('accent_color')); ?>;
                                              color:#ffffff;
                                              font-size:15px;
                                              font-weight:600;
                                              text-decoration:none;
                                              padding:14px 36px;
                                              border-radius:8px;
                                              letter-spacing:.03em;">
                                        Reset My Password
                                    </a>
                                </td>
                            </tr>
                        </table>

                        
                        <div style="background:#f8fafc;
                                    border: 1px solid #e2e8f0;
                                    border-radius:8px;
                                    padding:16px;
                                    margin-bottom:28px;">
                            <p style="margin:0 0 8px; color:#64748b; font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:.05em;">
                                If the button doesn't work, copy this link:
                            </p>
                            <p style="margin:0; font-size:12px; word-break:break-all; color:<?php echo e(brand('primary_color')); ?>;">
                                <?php echo e($url); ?>

                            </p>
                        </div>

                        
                        <div style="border-left: 4px solid #f59e0b;
                                    background:#fffbeb;
                                    padding:14px 16px;
                                    border-radius:0 6px 6px 0;
                                    margin-bottom:28px;">
                            <p style="margin:0; color:#92400e; font-size:13px; line-height:1.5;">
                                <strong>Didn't request this?</strong> You can safely ignore this email. Your password will not change unless you click the link above.
                            </p>
                        </div>

                        <hr style="border:none; border-top:1px solid #e2e8f0; margin: 0 0 24px;">

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
<?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\emails\reset-password.blade.php ENDPATH**/ ?>