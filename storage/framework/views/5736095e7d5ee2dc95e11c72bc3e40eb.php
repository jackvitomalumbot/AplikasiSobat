<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Admin'); ?> — SobatMedis Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
</head>
<body class="has-sidebar">
    
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="<?php echo e(url('/admin/dashboard')); ?>" class="sidebar-brand">
                <svg width="24" height="24" fill="none" stroke="var(--primary)" stroke-width="2" viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                <span>Admin Panel</span>
            </a>
        </div>

        <nav class="sidebar-nav">
            <a href="<?php echo e(url('/admin/dashboard')); ?>" class="sidebar-link <?php echo e(request()->is('admin/dashboard') ? 'active' : ''); ?>">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                Dashboard
            </a>
            <a href="<?php echo e(url('/admin/mahasiswa')); ?>" class="sidebar-link <?php echo e(request()->is('admin/mahasiswa') ? 'active' : ''); ?>">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                Data Mahasiswa
            </a>
            <a href="<?php echo e(url('/admin/pengajar')); ?>" class="sidebar-link <?php echo e(request()->is('admin/pengajar') ? 'active' : ''); ?>">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Data Pengajar
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <img src="<?php echo e(auth()->user()->foto_profile ? asset('storage/' . auth()->user()->foto_profile) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->nama) . '&size=40&background=cce5ff&color=004b73'); ?>" alt="" class="avatar">
                <div class="sidebar-user-info">
                    <span class="sidebar-user-name"><?php echo e(auth()->user()->nama); ?></span>
                    <span class="sidebar-user-role">Admin</span>
                </div>
            </div>
            <form method="POST" action="<?php echo e(url('/logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-ghost btn-sm btn-block" style="justify-content: flex-start;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    
    <div class="main-content">
        <header class="mobile-header" id="mobile-header">
            <button class="sidebar-toggle" id="sidebar-toggle" aria-label="Toggle sidebar">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>
            <span class="sidebar-brand" style="font-size: 16px;">Admin Panel</span>
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

        <?php echo $__env->yieldContent('content'); ?>
    </div>

    <script src="<?php echo e(asset('js/app.js')); ?>"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\laragon\www\AplikasiSobatMedis\resources\views/layouts/admin.blade.php ENDPATH**/ ?>