<?php $__env->startSection('title', 'Manajemen Kelas'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-between align-center flex-wrap gap-md">
    <div class="page-header" style="margin-bottom:0;">
        <h1>Manajemen Kelas</h1>
        <p>Buat dan kelola kelas Anda</p>
    </div>
    <a href="<?php echo e(url('/pengajar/kelas/create')); ?>" class="btn btn-primary" id="btn-create-kelas">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Buat Kelas Baru
    </a>
</div>

<?php if($kelasList->count()): ?>
<div class="grid grid-3 mt-lg">
    <?php $__currentLoopData = $kelasList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kelas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <a href="<?php echo e(url('/pengajar/kelas/' . $kelas->id)); ?>" class="card card-kelas" style="text-decoration:none;color:inherit;">
        <?php if($kelas->thumbnail): ?>
            <img src="<?php echo e(asset('storage/' . $kelas->thumbnail)); ?>" alt="<?php echo e($kelas->nama_kelas); ?>" class="card-img-top">
        <?php else: ?>
            <div style="width:100%;aspect-ratio:16/9;background:linear-gradient(135deg,var(--primary-fixed),var(--secondary-fixed));display:flex;align-items:center;justify-content:center;">
                <svg width="48" height="48" fill="none" stroke="var(--on-primary-fixed-variant)" stroke-width="1.5" viewBox="0 0 24 24" opacity="0.5"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
            </div>
        <?php endif; ?>
        <div class="card-body">
            <span class="card-tag"><?php echo e($kelas->active_enrollments_count); ?> Mahasiswa</span>
            <h3 class="card-title"><?php echo e($kelas->nama_kelas); ?></h3>
            <p class="card-desc"><?php echo e($kelas->deskripsi); ?></p>
        </div>
        <div class="card-footer">
            <span class="card-price"><?php echo e($kelas->formatted_harga); ?></span>
            <span class="badge <?php echo e($kelas->is_active ? 'badge-success' : 'badge-neutral'); ?>"><?php echo e($kelas->is_active ? 'Aktif' : 'Nonaktif'); ?></span>
        </div>
    </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php else: ?>
<div class="empty-state mt-xl">
    <svg width="64" height="64" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
    <h3>Belum Ada Kelas</h3>
    <p>Mulai dengan membuat kelas pertama Anda.</p>
    <a href="<?php echo e(url('/pengajar/kelas/create')); ?>" class="btn btn-primary">Buat Kelas Baru</a>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\AplikasiSobatMedis\resources\views/pengajar/kelas/index.blade.php ENDPATH**/ ?>