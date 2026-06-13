<?php $__env->startSection('title', 'Kelola Perangkat'); ?>

<?php $__env->startSection('content'); ?>
<div style="max-width:800px;">
    <div class="d-flex align-center justify-between mb-lg flex-wrap gap-md">
        <div>
            <h1 class="headline-lg mb-xs">Perangkat Aktif</h1>
            <p class="body-md text-muted">Anda hanya dapat login di 1 perangkat saja.</p>
        </div>
        <div class="d-flex align-center gap-sm">
            <span class="badge badge-info" style="font-size:0.85rem;padding:6px 14px;">
                <?php echo e($sessions->count()); ?> / 1 Perangkat
            </span>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success mb-lg">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-error mb-lg">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <div class="d-flex flex-column gap-md">
        <?php $__empty_1 = true; $__currentLoopData = $sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="card" style="cursor:default;">
                <div class="card-body">
                    <div class="d-flex align-center justify-between flex-wrap gap-md">
                        <div class="d-flex align-center gap-md">
                            
                            <div style="width:48px;height:48px;border-radius:var(--radius-lg);background:var(--primary-container);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <?php if(str_contains($session->user_agent, 'Android') || str_contains($session->user_agent, 'iOS')): ?>
                                    <svg width="24" height="24" fill="none" stroke="var(--on-primary-container)" stroke-width="2" viewBox="0 0 24 24"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
                                <?php else: ?>
                                    <svg width="24" height="24" fill="none" stroke="var(--on-primary-container)" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                                <?php endif; ?>
                            </div>

                            <div>
                                <div class="body-md fw-600 d-flex align-center gap-sm">
                                    <?php echo e($session->user_agent); ?>

                                    <?php if($session->is_current): ?>
                                        <span class="badge badge-success" style="font-size:0.7rem;padding:2px 8px;">Perangkat Ini</span>
                                    <?php endif; ?>
                                </div>
                                <div class="body-sm text-muted mt-xs">
                                    <span>IP: <?php echo e($session->ip_address); ?></span>
                                    <span style="margin:0 6px;">•</span>
                                    <span>Aktif <?php echo e($session->last_activity); ?></span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <?php if($session->is_current): ?>
                                <span class="body-sm text-muted" style="font-style:italic;">Sesi aktif</span>
                            <?php else: ?>
                                <form method="POST" action="<?php echo e(url('/mahasiswa/devices/' . $session->id)); ?>" onsubmit="return confirm('Yakin ingin logout dari perangkat ini?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-outline" style="color:var(--error);border-color:var(--error);">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                        Logout
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="card" style="cursor:default;">
                <div class="card-body text-center" style="padding:var(--space-xl);">
                    <p class="body-md text-muted">Tidak ada sesi aktif ditemukan.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="card mt-lg" style="cursor:default;background:var(--surface-container-low);">
        <div class="card-body">
            <div class="d-flex align-center gap-sm mb-sm">
                <svg width="18" height="18" fill="none" stroke="var(--primary)" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                <span class="body-md fw-600">Informasi</span>
            </div>
            <ul class="body-sm text-muted" style="margin:0;padding-left:var(--space-lg);line-height:1.8;">
                <li>Anda hanya dapat login di maksimal <strong>1 perangkat</strong>.</li>
                <li>Jika ingin login di perangkat baru, silakan logout dari salah satu perangkat aktif.</li>
                <li>Sesi yang tidak aktif selama <?php echo e(config('session.lifetime')); ?> menit akan otomatis berakhir.</li>
            </ul>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\AplikasiSobatMedis\resources\views/mahasiswa/devices.blade.php ENDPATH**/ ?>