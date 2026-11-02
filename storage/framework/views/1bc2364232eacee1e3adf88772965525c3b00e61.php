<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Account — <?php echo e(brand('name')); ?></title>
    <link rel="icon" type="image/x-icon" href="<?php echo e(brand('logo_url')); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <style>
        :root { --brand-primary: <?php echo e(brand('primary_color')); ?>; --brand-accent: <?php echo e(brand('accent_color')); ?>; --brand-gold: <?php echo e(brand('gold_color')); ?>; }
        * { font-family: 'Inter', sans-serif; }
        body { background: #f4f6f9; min-height: 100vh; display: flex; align-items: center; padding: 32px 0; }
        .login-card { border: none; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,.1); overflow: hidden; }
        .brand-header { background: linear-gradient(180deg, var(--brand-primary) 0%, var(--brand-accent) 100%); color: #fff; padding: 24px 32px; display: flex; align-items: center; justify-content: center; gap: 16px; }
        .brand-header .icon { width: 64px; height: 64px; flex: 0 0 64px; display: flex; align-items: center; justify-content: center; }
        .brand-header .icon img { width: 50px; height: auto; display: block; }
        .brand-copy { text-align: left; }
        .login-body { padding: 32px; }
        .btn-login { background: var(--brand-accent); border: none; padding: 12px; font-weight: 600; letter-spacing: .03em; }
        .btn-login:hover { background: var(--brand-gold); }
        .form-control:focus { border-color: var(--brand-primary); box-shadow: 0 0 0 3px rgba(26,60,110,.15); }
        @media (max-width: 575.98px) {
            .brand-header, .login-body { padding: 24px; }
            .brand-header { justify-content: flex-start; gap: 12px; }
            .brand-header .icon { width: 52px; height: 52px; flex-basis: 52px; }
            .brand-copy h4 { font-size: 1.1rem; }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-md-10">
            <div class="login-card card">
                <div class="brand-header">
                    <div class="icon"><img src="<?php echo e(brand('logo_url')); ?>" alt="<?php echo e(brand('short_name')); ?> Logo"></div>
                    <div class="brand-copy">
                        <h4 class="mb-1 fw-bold"><?php echo e(brand('short_name')); ?> P.A.T.T.S</h4>
                        <p class="mb-0 opacity-75" style="font-size:14px;"><?php echo e(brand('tagline')); ?></p>
                    </div>
                </div>

                <div class="login-body">
                    <h5 class="fw-semibold mb-1">Create your account</h5>
                    <p class="text-muted mb-4" style="font-size:14px;">Register to begin your postgraduate journey. You'll be able to submit your admission application once you're signed in.</p>

                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div><?php echo e($error); ?></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo e(route('register')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="surname" class="form-label fw-medium">Surname</label>
                                        <input type="text" id="surname" name="surname" class="form-control form-control-lg <?php $__errorArgs = ['surname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            value="<?php echo e(old('surname')); ?>" placeholder="Doe" required autofocus autocomplete="family-name">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="first_name" class="form-label fw-medium">First Name</label>
                                        <input type="text" id="first_name" name="first_name" class="form-control form-control-lg <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            value="<?php echo e(old('first_name')); ?>" placeholder="Jane" required autocomplete="given-name">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-medium">Email Address</label>
                                    <input type="email" id="email" name="email" class="form-control form-control-lg <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        value="<?php echo e(old('email')); ?>" placeholder="you@example.com" required autocomplete="username">
                                </div>
                                <div class="mb-3">
                                    <label for="email_confirmation" class="form-label fw-medium">Confirm Email Address</label>
                                    <input type="email" id="email_confirmation" name="email_confirmation" class="form-control form-control-lg <?php $__errorArgs = ['email_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        value="<?php echo e(old('email_confirmation')); ?>" placeholder="you@example.com" required autocomplete="username">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="id_no" class="form-label fw-medium">ID / Passport Number</label>
                                    <input type="text" id="id_no" name="id_no" class="form-control form-control-lg <?php $__errorArgs = ['id_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        value="<?php echo e(old('id_no')); ?>" placeholder="e.g. 30123456" required autocomplete="off">
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label fw-medium">Password</label>
                                    <input type="password" id="password" name="password" class="form-control form-control-lg <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        placeholder="••••••••" required autocomplete="new-password">
                                </div>
                                <div class="mb-4">
                                    <label for="password_confirmation" class="form-label fw-medium">Confirm Password</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="form-control form-control-lg" placeholder="••••••••" required autocomplete="new-password">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-login btn-primary w-50 btn-md text-white">Create Account</button>
                    </form>

                    <p class="text-center text-muted mt-4 mb-0" style="font-size: 13px;">
                        Already have an account? <a href="<?php echo e(route('login')); ?>" class="text-decoration-none fw-semibold">Sign in</a>
                    </p>
                </div>
            </div>
            <p class="text-center text-muted mt-3" style="font-size:12px;">
                &copy; <?php echo e(date('Y')); ?> <?php echo e(brand('footer_text')); ?>

            </p>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php echo $__env->make('partials.loader', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>
</html>
<?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\auth\register.blade.php ENDPATH**/ ?>