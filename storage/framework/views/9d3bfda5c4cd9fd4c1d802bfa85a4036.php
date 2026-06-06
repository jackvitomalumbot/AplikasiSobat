<?php $__env->startSection('title', 'Verifikasi Email'); ?>
<?php $__env->startSection('auth_subtitle', 'Verifikasi email Anda'); ?>
<?php $__env->startSection('content'); ?>
<div class="text-center">
    <svg width="64" height="64" fill="none" stroke="var(--primary)" stroke-width="1.5" viewBox="0 0 24 24" style="margin: 0 auto var(--space-md);">
        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
    </svg>
    <p class="body-md text-muted mb-lg">Kami telah mengirimkan email verifikasi ke alamat email Anda. Silakan cek inbox (dan folder spam) untuk mengaktifkan akun.</p>
    <?php if(session('status') == 'verification-link-sent'): ?>
        <div class="alert alert-success">Link verifikasi baru telah dikirim ke email Anda.</div>
    <?php endif; ?>
    <div class="d-flex flex-column gap-sm">
        <form method="POST" action="<?php echo e(url('/email/verification-notification')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-primary btn-block">Kirim Ulang Email Verifikasi</button>
        </form>
        <form method="POST" action="<?php echo e(url('/logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-ghost btn-block">Logout</button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\AplikasiSobatMedis\resources\views/auth/verify-email.blade.php ENDPATH**/ ?>