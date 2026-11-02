<!DOCTYPE html>
<html lang="en">
<head><meta charset="utf-8"><title>Thesis Deadline Reminder</title></head>
<body style="font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(180deg, <?php echo e(brand('primary_color')); ?> 0%, <?php echo e(brand('accent_color')); ?> 100%); color: white; padding: 20px; border-radius: 6px 6px 0 0; text-align:center;">
        <img src="<?php echo e(brand('logo_url')); ?>" alt="<?php echo e(brand('short_name')); ?>" width="56" height="56" style="border-radius:12px; display:block; margin:0 auto 12px;">
        <h2 style="margin:0;"><?php echo e(brand('short_name')); ?> Postgraduate Portal</h2>
        <p style="margin:4px 0 0;">Thesis Submission Deadline Reminder</p>
    </div>
    <div style="border: 1px solid #ddd; border-top:none; padding: 24px; border-radius: 0 0 6px 6px;">
        <p>Dear <?php echo e($thesis->student->user->name); ?>,</p>
        <div style="background: #fff3cd; border:1px solid #ffc107; padding:16px; border-radius:4px; margin:16px 0;">
            <strong>⚠ Your thesis submission deadline is in <?php echo e($daysLeft); ?> day(s).</strong>
        </div>
        <p><strong>Thesis:</strong> <?php echo e($thesis->title); ?></p>
        <p><strong>Intended Submission Date:</strong> <?php echo e($thesis->intent_to_submit_date->format('d M Y')); ?></p>
        <p>Please ensure you are on track to submit your thesis by this date. Contact your supervisor if you have any concerns.</p>
        <table width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0 16px;">
            <tr>
                <td align="center">
                    <a href="<?php echo e(route('login')); ?>" style="display:inline-block; background:<?php echo e(brand('accent_color')); ?>; color:#ffffff; font-size:15px; font-weight:600; text-decoration:none; padding:14px 36px; border-radius:8px;">
                        Sign In to the Portal
                    </a>
                </td>
            </tr>
        </table>
        <p style="margin:0 0 24px; font-size:12px; color:#64748b; text-align:center;">
            Or visit <a href="<?php echo e(route('login')); ?>" style="color:<?php echo e(brand('primary_color')); ?>;">the portal sign-in page</a>.
        </p>
        <p style="margin-top: 32px; font-size: 12px; color: #888; text-align:center;">
            This is an automated message from the <?php echo e(brand('short_name')); ?> Postgraduate Portal.<br>
            <?php echo e(brand('name')); ?><br>
            &copy; <?php echo e(date('Y')); ?> All rights reserved.
        </p>
    </div>
</body>
</html>
<?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\emails\countdown-alert.blade.php ENDPATH**/ ?>