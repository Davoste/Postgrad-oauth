
<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Verify Email - <?php echo e(brand('name')); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand-primary: <?php echo e(brand('primary_color')); ?>;
            --brand-accent: <?php echo e(brand('accent_color')); ?>;
            --ink: #202938;
            --muted: #687386;
            --line: #e5e9ef;
            --surface: #ffffff;
            --page: #f3f5f8;
        }

        * { box-sizing: border-box; }

        body {
            min-height: 100vh;
            margin: 0;
            padding: 28px 16px;
            display: grid;
            place-items: center;
            color: var(--ink);
            background: var(--page);
            font-family: Inter, "Segoe UI", sans-serif;
        }

        .verify-wrap { width: min(100%, 480px); }

        .verify-panel {
            overflow: hidden;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 12px;
            box-shadow: 0 18px 50px rgba(28, 39, 56, .1);
        }

        .brand-header {
            min-height: 142px;
            padding: 28px 32px;
            display: flex;
            align-items: center;
            gap: 18px;
            color: #fff;
            background: linear-gradient(145deg, var(--brand-primary), var(--brand-accent));
        }

        .brand-logo {
            width: 58px;
            height: 58px;
            flex: 0 0 58px;
            object-fit: contain;
            border-radius: 10px;
        }

        .brand-name { margin: 0; font-size: 19px; line-height: 1.35; font-weight: 700; }
        .brand-tagline { margin: 5px 0 0; color: rgba(255, 255, 255, .8); font-size: 13px; line-height: 1.5; }
        .verify-content { padding: 32px; }
        .eyebrow { margin: 0 0 10px; color: var(--brand-accent); font-size: 12px; font-weight: 700; text-transform: uppercase; }
        .verify-title { margin: 0; font-size: 23px; line-height: 1.3; font-weight: 700; }
        .verify-message { margin: 12px 0 0; color: var(--muted); font-size: 14px; line-height: 1.7; }

        .notice {
            margin-top: 20px;
            padding: 12px 14px;
            border: 1px solid #b7e4c7;
            border-radius: 6px;
            color: #17643a;
            background: #effaf3;
            font-size: 13px;
            line-height: 1.5;
        }

        .verify-actions { margin-top: 24px; display: grid; gap: 14px; }

        .resend-button {
            width: 100%;
            min-height: 46px;
            padding: 12px 18px;
            border: 0;
            border-radius: 6px;
            color: #fff;
            background: var(--brand-accent);
            font: inherit;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }

        .resend-button:hover { filter: brightness(.94); }
        .logout-form { text-align: center; }
        .logout-button, .portal-link { color: var(--brand-primary); font: inherit; font-size: 13px; }
        .logout-button { padding: 4px 8px; border: 0; background: none; text-decoration: underline; cursor: pointer; }
        .portal-link { display: block; margin-top: 20px; text-align: center; text-decoration: none; }
        .portal-link:hover, .logout-button:hover { text-decoration: underline; }
        .copyright { margin: 16px 0 0; color: var(--muted); font-size: 11px; line-height: 1.5; text-align: center; }

        @media (max-width: 520px) {
            .brand-header { min-height: 120px; padding: 22px; gap: 14px; }
            .brand-logo { width: 50px; height: 50px; flex-basis: 50px; }
            .verify-content { padding: 26px 22px; }
            .verify-title { font-size: 21px; }
        }
    </style>
</head>
<body>
    <main class="verify-wrap">
        <section class="verify-panel" aria-labelledby="verify-title">
            <header class="brand-header">
                <img class="brand-logo" src="<?php echo e(brand('logo_url')); ?>" alt="<?php echo e(brand('short_name')); ?> logo">
                <div>
                    <p class="brand-name"><?php echo e(brand('name')); ?></p>
                    <p class="brand-tagline"><?php echo e(brand('tagline')); ?></p>
                </div>
            </header>

            <div class="verify-content">
                <p class="eyebrow">Account security</p>
                <h1 class="verify-title" id="verify-title">Confirm your email address</h1>
                <p class="verify-message">
                    <?php echo e(__('Thanks for signing up! Before getting started, verify your email address using the link we just sent. If you did not receive it, request a new link below.')); ?>

                </p>

                <?php if(session('status') == 'verification-link-sent'): ?>
                    <div class="notice" role="status">
                        <?php echo e(__('A new verification link has been sent to the email address you provided during registration.')); ?>

                    </div>
                <?php endif; ?>

                <div class="verify-actions">
                    <form method="POST" action="<?php echo e(route('verification.send')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="resend-button"><?php echo e(__('Resend verification email')); ?></button>
                    </form>

                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="logout-form">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="logout-button"><?php echo e(__('Log out')); ?></button>
                    </form>
                </div>

                <a class="portal-link" href="<?php echo e(route('welcome')); ?>"><?php echo e(brand('short_name')); ?> Portal</a>
            </div>
        </section>

        <p class="copyright">&copy; <?php echo e(date('Y')); ?> <?php echo e(brand('name')); ?>. <?php echo e(brand('legal_name')); ?>.</p>
    </main>
</body>
</html>
<?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\auth\verify-email.blade.php ENDPATH**/ ?>