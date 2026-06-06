<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Login'); ?> — SobatMedis</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card animate-slide-up">
            <div class="auth-logo">
                <svg width="48" height="48" viewBox="0 0 32 32" fill="none" style="margin: 0 auto 12px;">
                    <rect width="32" height="32" rx="8" fill="#006194"/>
                    <path d="M16 6L16 26M10 12H22M10 18H22" stroke="white" stroke-width="2.5" stroke-linecap="round"/>
                </svg>
                <h1>SobatMedis</h1>
                <p><?php echo $__env->yieldContent('auth_subtitle', 'Platform Pembelajaran Medis'); ?></p>
            </div>

            <?php if(session('status')): ?>
                <div class="alert alert-success mb-lg">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <?php echo e(session('status')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-error mb-lg">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </div>

    <script src="<?php echo e(asset('js/app.js')); ?>"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\laragon\www\AplikasiSobatMedis\resources\views/layouts/auth.blade.php ENDPATH**/ ?>