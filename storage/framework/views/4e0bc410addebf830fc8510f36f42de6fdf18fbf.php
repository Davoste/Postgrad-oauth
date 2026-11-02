<!DOCTYPE html>
<html lang="en">
<head><meta charset="utf-8"><title>Monthly Meeting Reminder</title></head>
<body style="font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(180deg, <?php echo e(brand('primary_color')); ?> 0%, <?php echo e(brand('accent_color')); ?> 100%); color: white; padding: 20px; border-radius: 6px 6px 0 0; text-align:center;">
        <img src="<?php echo e(brand('logo_url')); ?>" alt="<?php echo e(brand('short_name')); ?>" width="56" height="56" style="border-radius:12px; display:block; margin:0 auto 12px;">
        <h2 style="margin:0;"><?php echo e(brand('short_name')); ?> Postgraduate Portal</h2>
        <p style="margin:4px 0 0;">Monthly Supervision Meeting Reminder</p>
    </div>
    <div style="border: 1px solid #ddd; border-top:none; padding: 24px; border-radius: 0 0 6px 6px;">
        <p>Dear <?php echo e($supervisor->user->name); ?>,</p>
        <p>Our records indicate that no supervision meeting was logged last month for the following student(s):</p>
        <ul>
            <?php $__currentLoopData = $studentsWithNoMeeting; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><strong><?php echo e($student->user->name); ?></strong> (<?php echo e($student->reg_no); ?>) — <?php echo e(optional($student->thesis)->stage_label ?? 'N/A'); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
        <p>As per <?php echo e(brand('short_name')); ?> postgraduate regulations, supervisors are required to meet with each student <strong>at least once per month</strong>. Please log any missing meetings or schedule a meeting as soon as possible.</p>
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
<?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\emails\monthly-meeting-reminder.blade.php ENDPATH**/ ?>