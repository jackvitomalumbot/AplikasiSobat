<?php $__env->startSection('title', 'Login'); ?>
<?php $__env->startSection('auth_subtitle', 'Masuk ke akun Anda'); ?>

<?php $__env->startSection('content'); ?>
<form method="POST" action="<?php echo e(url('/login')); ?>" id="login-form">
    <?php echo csrf_field(); ?>

    <div class="form-group">
        <label class="form-label" for="login-email">Email <span class="required">✱</span></label>
        <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="login-email" name="email" value="<?php echo e(old('email')); ?>" placeholder="email@universitas.ac.id" required autofocus>
        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span class="form-error"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="form-group">
        <label class="form-label" for="login-password">Password <span class="required">✱</span></label>
        <div class="input-group">
            <input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="login-password" name="password" placeholder="Masukkan password" required>
            <button type="button" class="input-toggle" aria-label="Toggle password visibility">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
        </div>
        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span class="form-error"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="d-flex justify-between align-center mb-lg">
        <label class="d-flex align-center gap-sm body-sm" style="cursor: pointer;">
            <input type="checkbox" name="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
            Ingat saya
        </label>
        <a href="<?php echo e(url('/forgot-password')); ?>" class="body-sm">Lupa password?</a>
    </div>

    <button type="submit" class="btn btn-primary btn-block btn-lg" id="btn-submit-login">
        Masuk
    </button>
</form>

<div class="auth-footer">
    Belum punya akun? <a href="<?php echo e(url('/register')); ?>"><strong>Daftar sekarang</strong></a>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\AplikasiSobatMedis\resources\views/auth/login.blade.php ENDPATH**/ ?>