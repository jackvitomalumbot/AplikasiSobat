<?php $__env->startSection('title', 'Checkout'); ?>

<?php $__env->startSection('content'); ?>
<a href="<?php echo e(url('/mahasiswa/beli-kelas')); ?>" class="body-sm text-muted d-inline-flex align-center gap-xs mb-lg" style="text-decoration:none;">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
    Kembali
</a>

<div class="d-flex gap-xl flex-wrap" style="max-width:900px;">
    
    <div style="flex:1;min-width:300px;">
        <div class="card" style="cursor:default;">
            <?php if($kelas->thumbnail): ?>
                <img src="<?php echo e(asset('storage/' . $kelas->thumbnail)); ?>" alt="<?php echo e($kelas->nama_kelas); ?>" class="card-img-top">
            <?php else: ?>
                <div style="width:100%;aspect-ratio:16/9;background:linear-gradient(135deg,var(--primary-fixed),var(--secondary-fixed));display:flex;align-items:center;justify-content:center;">
                    <svg width="64" height="64" fill="none" stroke="var(--on-primary-fixed-variant)" stroke-width="1" viewBox="0 0 24 24" opacity="0.4"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
                </div>
            <?php endif; ?>
            <div class="card-body">
                <h2 class="headline-md mb-sm"><?php echo e($kelas->nama_kelas); ?></h2>
                <p class="body-sm text-muted mb-md"><?php echo e($kelas->deskripsi); ?></p>
                <div class="d-flex align-center gap-sm">
                    <img src="<?php echo e($kelas->pengajar->foto_profile ? asset('storage/'.$kelas->pengajar->foto_profile) : 'https://ui-avatars.com/api/?name='.urlencode($kelas->pengajar->nama).'&size=32&background=cce5ff&color=004b73'); ?>" alt="" class="avatar" style="width:32px;height:32px;">
                    <span class="body-sm"><?php echo e($kelas->pengajar->nama); ?></span>
                </div>
            </div>
        </div>
    </div>

    
    <div style="flex:1;min-width:300px;">
        <div class="card" style="cursor:default;">
            <div class="card-header">
                <h3 class="headline-sm">Ringkasan Pembayaran</h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-between mb-md">
                    <span class="body-md text-muted">Kelas</span>
                    <span class="body-md fw-600"><?php echo e($kelas->nama_kelas); ?></span>
                </div>
                <div class="d-flex justify-between mb-md">
                    <span class="body-md text-muted">Harga</span>
                    <span class="body-md fw-600"><?php echo e($kelas->formatted_harga); ?></span>
                </div>
                <hr style="border:none;border-top:1px solid var(--outline-variant);margin:var(--space-md) 0;">
                <div class="d-flex justify-between mb-lg">
                    <span class="body-lg fw-700">Total</span>
                    <span class="body-lg fw-700 text-primary"><?php echo e($kelas->formatted_harga); ?></span>
                </div>

                <div class="alert alert-info mb-lg">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    Mode simulasi — pembayaran akan otomatis berhasil.
                </div>

                <form method="POST" action="<?php echo e(url('/mahasiswa/beli-kelas/' . $kelas->id . '/pay')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-primary btn-block btn-lg" id="btn-pay">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                        Bayar Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\AplikasiSobatMedis\resources\views/payment/checkout.blade.php ENDPATH**/ ?>