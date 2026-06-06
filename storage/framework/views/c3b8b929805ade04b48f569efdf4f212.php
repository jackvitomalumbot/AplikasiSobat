<?php $__env->startSection('title', 'Lupa Password'); ?>
<?php $__env->startSection('auth_subtitle', 'Reset password akun Anda'); ?>
<?php $__env->startSection('content'); ?>
<p class="body-sm text-muted mb-lg">Masukkan email terdaftar Anda. Kami akan mengirimkan link untuk reset password.</p>
<form method="POST" action="<?php echo e(url('/forgot-password')); ?>" id="forgot-password-form">
    <?php echo csrf_field(); ?>
    <div class="form-group">
        <label class="form-label" for="fp-email">Email <span class="required">✱</span></label>
        <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="fp-email" name="email" value="<?php echo e(old('email')); ?>" placeholder="email@universitas.ac.id" required autofocus>
        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="form-error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <button type="submit" class="btn btn-primary btn-block btn-lg">Kirim Link Reset</button>
</form>
<div class="auth-footer"><a href="<?php echo e(url('/login')); ?>">← Kembali ke Login</a></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\AplikasiSobatMedis\resources\views/auth/forgot-password.blade.php ENDPATH**/ ?>