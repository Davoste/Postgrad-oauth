<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirm Your Email</title>
</head>
<body style="margin:0; padding:0; background:#f4f6f9; font-family:Arial, Helvetica, sans-serif;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f9; padding:40px 12px;">
    <tr>
        <td align="center">
            <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="width:100%; max-width:600px;">
                <tr>
                    <td style="padding:32px 28px; color:#ffffff; text-align:center; background:linear-gradient(145deg, <?php echo e(brand('primary_color')); ?> 0%, <?php echo e(brand('accent_color')); ?> 100%); border-radius:12px 12px 0 0;">
                        <?php if(brand('logo_url')): ?>
                            <img src="<?php echo e(brand('logo_url')); ?>" alt="<?php echo e(brand('short_name')); ?>" width="56" height="56" style="display:block; width:56px; height:56px; object-fit:contain; margin:0 auto 14px; border-radius:10px;">
                        <?php endif; ?>
                        <h1 style="margin:0; color:#ffffff; font-size:21px; line-height:1.35; font-weight:700;"><?php echo e(brand('short_name')); ?> Postgraduate Portal</h1>
                        <p style="margin:7px 0 0; color:#e5eaf2; font-size:13px; line-height:1.5;"><?php echo e(brand('tagline')); ?></p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:36px 40px; background:#ffffff; border:1px solid #e2e8f0; border-top:0; border-radius:0 0 12px 12px;">
                        <p style="margin:0 0 8px; color:<?php echo e(brand('accent_color')); ?>; font-size:12px; font-weight:700; letter-spacing:.08em; text-transform:uppercase;">Account confirmation</p>
                        <h2 style="margin:0 0 20px; color:#1a202c; font-size:22px; line-height:1.35;">Confirm your email address</h2>
                        <p style="margin:0 0 14px; color:#374151; font-size:15px; line-height:1.65;">Hello <?php echo e($user->first_name ?: $user->name); ?>,</p>
                        <p style="margin:0 0 26px; color:#4b5563; font-size:15px; line-height:1.65;">
                            Thanks for creating your <?php echo e(brand('short_name')); ?> Postgraduate Portal account. Confirm this email address to secure your account and continue to the portal.
                        </p>

                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 26px;">
                            <tr>
                                <td align="center">
                                    <a href="<?php echo e($url); ?>" style="display:inline-block; padding:14px 30px; color:#ffffff; background:<?php echo e(brand('accent_color')); ?>; border-radius:6px; font-size:15px; font-weight:700; line-height:1.3; text-decoration:none;">Verify Email Address</a>
                                </td>
                            </tr>
                        </table>

                        <div style="margin:0 0 24px; padding:14px 16px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:7px;">
                            <p style="margin:0 0 7px; color:#64748b; font-size:12px; font-weight:700; text-transform:uppercase;">Button not working?</p>
                            <p style="margin:0; color:<?php echo e(brand('primary_color')); ?>; font-size:12px; line-height:1.6; word-break:break-all;"><?php echo e($url); ?></p>
                        </div>

                        <p style="margin:0 0 26px; padding:13px 15px; color:#7c4a03; background:#fffbeb; border-left:4px solid #e3ad38; border-radius:0 5px 5px 0; font-size:13px; line-height:1.55;">
                            If you did not create this account, you can ignore this message. The email address will not be verified unless the button above is used.
                        </p>

                        <hr style="margin:0 0 20px; border:0; border-top:1px solid #e2e8f0;">
                        <p style="margin:0; color:#94a3b8; font-size:12px; line-height:1.6; text-align:center;">
                            Automated message from the <?php echo e(brand('short_name')); ?> Postgraduate Portal.<br>
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
</html><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views/emails/verify-email.blade.php ENDPATH**/ ?>