<?php $__env->startSection('title', 'Branding'); ?>
<?php $__env->startSection('page-title', 'Branding & White-labelling'); ?>

<?php $__env->startSection('content'); ?>
<?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-semibold mb-1">Institution branding</h5>
                <p class="text-muted small mb-4">
                    These settings control the name, colours and logo shown across the portal, emails and generated
                    PDFs (admission letters, transcripts) — no code changes or redeploy needed.
                </p>

                <form method="POST" action="<?php echo e(route('admin.branding.update')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Full institution name</label>
                            <input name="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('name', $values['name'])); ?>" required>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Short name / acronym</label>
                            <input name="short_name" class="form-control <?php $__errorArgs = ['short_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('short_name', $values['short_name'])); ?>" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-semibold">Tagline</label>
                            <input name="tagline" class="form-control" value="<?php echo e(old('tagline', $values['tagline'])); ?>" placeholder="e.g. Thesis Supervision Portal">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Primary colour</label>
                            <input type="color" name="primary_color" class="form-control form-control-color w-100" value="<?php echo e(old('primary_color', $values['primary_color'])); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Accent colour</label>
                            <input type="color" name="accent_color" class="form-control form-control-color w-100" value="<?php echo e(old('accent_color', $values['accent_color'])); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Gold / highlight colour</label>
                            <input type="color" name="gold_color" class="form-control form-control-color w-100" value="<?php echo e(old('gold_color', $values['gold_color'])); ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Support email</label>
                            <input type="email" name="support_email" class="form-control" value="<?php echo e(old('support_email', $values['support_email'])); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Support phone</label>
                            <input name="support_phone" class="form-control" value="<?php echo e(old('support_phone', $values['support_phone'])); ?>">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-semibold">Address</label>
                            <input name="address" class="form-control" value="<?php echo e(old('address', $values['address'])); ?>">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-semibold">Footer text</label>
                            <input name="footer_text" class="form-control" value="<?php echo e(old('footer_text', $values['footer_text'])); ?>">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-semibold">Favicon emoji</label>
                            <input name="favicon_emoji" class="form-control" value="<?php echo e(old('favicon_emoji', $values['favicon_emoji'])); ?>" maxlength="8">
                            <small class="text-muted">Used where a favicon image isn't practical (e.g. published pages).</small>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-semibold">Logo</label>
                            <div class="d-flex align-items-center gap-3">
                                <img src="<?php echo e($logoUrl); ?>" alt="Current logo" style="height:48px;width:auto;border:1px solid #eee;border-radius:6px;padding:4px;">
                                <input type="file" name="logo" accept="image/*" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-semibold">Public site hero background image</label>
                            <p class="text-muted small mb-2">Shown behind the headline on the public homepage. Leave unset to use the default colour gradient.</p>
                            <?php if($heroImageUrl): ?>
                                <img src="<?php echo e($heroImageUrl); ?>" alt="Current hero background" class="mb-2" style="max-height:120px;width:auto;border:1px solid #eee;border-radius:6px;">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="remove_hero_image" value="1" id="remove_hero_image">
                                    <label class="form-check-label small" for="remove_hero_image">Remove current image and use the gradient instead</label>
                                </div>
                            <?php endif; ?>
                            <input type="file" name="hero_image" accept="image/*" class="form-control">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-2">Save branding</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-semibold">Live preview</h6>
                <div class="rounded p-3 mb-2 text-white" style="background: <?php echo e($values['primary_color']); ?>;">
                    <div class="d-flex align-items-center gap-2">
                        <img src="<?php echo e($logoUrl); ?>" style="height:28px;width:auto;">
                        <strong><?php echo e($values['short_name']); ?></strong>
                    </div>
                    <div class="small opacity-75 mt-1"><?php echo e($values['tagline']); ?></div>
                </div>
                <button class="btn btn-sm w-100 mb-2" style="background: <?php echo e($values['accent_color']); ?>; color:#fff;">Primary action</button>
                <button class="btn btn-sm w-100" style="background: <?php echo e($values['gold_color']); ?>; color:#1a1a1a;">Highlight action</button>
                <p class="text-muted small mt-3 mb-0">Applies to the public site, portal shell, registration page, emails and generated PDFs.</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-3">
            <div class="card-body">
                <h6 class="fw-semibold">Reset</h6>
                <p class="text-muted small">Clears all overrides above and falls back to the install's <code>.env</code> defaults.</p>
                <form method="POST" action="<?php echo e(route('admin.branding.reset')); ?>" onsubmit="return confirm('Reset all branding overrides?');">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button class="btn btn-outline-danger btn-sm">Reset to defaults</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\admin\branding.blade.php ENDPATH**/ ?>