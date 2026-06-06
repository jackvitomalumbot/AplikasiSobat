<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $__env->yieldContent('meta_description', 'SobatMedis — Platform Pembelajaran Medis Online'); ?>">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Beranda'); ?> — SobatMedis</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
</head>
<body>
    
    <header class="topnav" id="topnav">
        <div class="container d-flex justify-between align-center">
            <a href="<?php echo e(url('/')); ?>" class="topnav-brand">
                <svg width="28" height="28" fill="none" stroke="var(--primary)" stroke-width="2" viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                <span>SobatMedis</span>
            </a>
            <nav class="topnav-links">
                <a href="<?php echo e(url('/')); ?>" class="<?php echo e(request()->is('/') ? 'active' : ''); ?>">Dashboard</a>
                <a href="<?php echo e(url('/bantuan')); ?>" class="<?php echo e(request()->is('bantuan') ? 'active' : ''); ?>">Pusat Bantuan</a>
            </nav>
            <div class="topnav-actions">
                <?php if(auth()->guard()->check()): ?>
                    <?php $role = auth()->user()->role; ?>
                    <a href="<?php echo e(url("/{$role}/dashboard")); ?>" class="btn btn-ghost btn-sm"><?php echo e(auth()->user()->nama); ?></a>
                    <form method="POST" action="<?php echo e(url('/logout')); ?>" style="display:inline;">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-outline btn-sm">Logout</button>
                    </form>
                <?php else: ?>
                    <a href="<?php echo e(url('/login')); ?>" class="btn btn-primary btn-sm" id="btn-login">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-error"><?php echo e(session('error')); ?></div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="alert alert-error">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div><?php echo e($error); ?></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

    
    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    
    <footer class="footer">
        <div class="container text-center">
            <p>© <?php echo e(date('Y')); ?> SobatMedis. All rights reserved.</p>
        </div>
    </footer>

    <script src="<?php echo e(asset('js/app.js')); ?>"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\laragon\www\AplikasiSobatMedis\resources\views/layouts/app.blade.php ENDPATH**/ ?>