<?php $__env->startSection('title', 'Single Sign-On'); ?>
<?php $__env->startSection('page-title', 'Single Sign-On (OAuth2) Clients'); ?>

<?php $__env->startSection('content'); ?>
<?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
<?php endif; ?>

<?php if(session('newClient')): ?>
    <?php ($nc = session('newClient')); ?>
    <div class="alert alert-warning">
        <strong>Save these now — the secret will not be shown again.</strong>
        <div class="mt-2 small">
            <div><strong>Client ID:</strong> <code><?php echo e($nc['client_id']); ?></code></div>
            <div><strong>Client Secret:</strong> <code><?php echo e($nc['client_secret']); ?></code></div>
            <div><strong>Authorization URL:</strong> <code><?php echo e(url('/oauth/authorize')); ?></code></div>
            <div><strong>Token URL:</strong> <code><?php echo e(url('/oauth/token')); ?></code></div>
            <div><strong>UserInfo URL:</strong> <code><?php echo e(url('/oauth/userinfo')); ?></code></div>
        </div>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <h5 class="fw-semibold mb-3">Add a client (e.g. your Moodle instance)</h5>
        <form method="POST" action="<?php echo e(route('admin.oauth-clients.store')); ?>" class="row g-3">
            <?php echo csrf_field(); ?>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Name</label>
                <input name="name" class="form-control" placeholder="Moodle LMS" required>
            </div>
            <div class="col-md-5">
                <label class="form-label small fw-semibold">Redirect URI(s)</label>
                <input name="redirect_uri" class="form-control" placeholder="https://lms.example.edu/admin/oauth2callback.php" required>
                <small class="text-muted">Moodle's OAuth2 callback URL, from Site administration &gt; OAuth 2 services.</small>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="trusted" value="1" id="trusted">
                    <label class="form-check-label small" for="trusted">Trusted (skip consent)</label>
                </div>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button class="btn btn-primary w-100">Add</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h5 class="fw-semibold mb-3">Existing clients</h5>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead><tr><th>Name</th><th>Client ID</th><th>Redirect URI</th><th>Trusted</th><th>Status</th><th></th></tr></thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($c->name); ?></td>
                        <td><code class="small"><?php echo e($c->client_id); ?></code></td>
                        <td class="small text-break"><?php echo e($c->redirect_uri); ?></td>
                        <td><?php echo e($c->trusted ? 'Yes' : 'No'); ?></td>
                        <td>
                            <?php if($c->revoked): ?>
                                <span class="badge bg-danger">Revoked</span>
                            <?php else: ?>
                                <span class="badge bg-success">Active</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <?php if (! ($c->revoked)): ?>
                                <form method="POST" action="<?php echo e(route('admin.oauth-clients.toggle-trusted', $c)); ?>" class="d-inline">
                                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                    <button class="btn btn-sm btn-outline-secondary"><?php echo e($c->trusted ? 'Require consent' : 'Trust'); ?></button>
                                </form>
                                <form method="POST" action="<?php echo e(route('admin.oauth-clients.destroy', $c)); ?>" class="d-inline" onsubmit="return confirm('Revoke this client? It will stop working immediately.');">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-outline-danger">Revoke</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="text-muted text-center py-4">No SSO clients yet — add your Moodle instance above.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Admin\Desktop\Version2.2\resources\views\admin\oauth-clients.blade.php ENDPATH**/ ?>