<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Letter</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111827; line-height: 1.6;">
    <p>Dear <?php echo e($application->first_name); ?>,</p>

    <p>Welcome to <?php echo e(brand('name')); ?>. Your student account has been created successfully.</p>

    <p><strong>Admission number:</strong> <?php echo e($admNo); ?></p>
    <p><strong>Temporary password:</strong> <?php echo e($password); ?></p>

    <p>Please log in and change your password immediately.</p>

    <p>A PDF admission letter has been attached to this email for your records.</p>

    <p>Regards,<br><?php echo e(brand('name')); ?></p>
</body>
</html>
<?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\emails\admission-letter.blade.php ENDPATH**/ ?>