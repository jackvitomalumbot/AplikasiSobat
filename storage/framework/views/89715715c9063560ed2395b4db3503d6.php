<?php $__env->startSection('title', 'Kelas Saya'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h1>Kelas Saya</h1>
    <p>Kelas yang sudah Anda ikuti</p>
</div>

<?php if($enrolledKelas->count()): ?>
<div class="grid grid-3">
    <?php $__currentLoopData = $enrolledKelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kelas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <a href="<?php echo e(url('/mahasiswa/kelas/' . $kelas->id)); ?>" class="card card-kelas" style="text-decoration:none;color:inherit;">
        <?php if($kelas->thumbnail): ?>
            <img src="<?php echo e(asset('storage/' . $kelas->thumbnail)); ?>" alt="<?php echo e($kelas->nama_kelas); ?>" class="card-img-top">
        <?php else: ?>
            <div style="width:100%;aspect-ratio:16/9;background:linear-gradient(135deg,var(--primary-fixed),var(--secondary-fixed));display:flex;align-items:center;justify-content:center;">
                <svg width="48" height="48" fill="none" stroke="var(--on-primary-fixed-variant)" stroke-width="1.5" viewBox="0 0 24 24" opacity="0.5"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
            </div>
        <?php endif; ?>
        <div class="card-body">
            <div class="card-instructor mb-sm">
                <img src="<?php echo e($kelas->pengajar->foto_profile ? asset('storage/'.$kelas->pengajar->foto_profile) : 'https://ui-avatars.com/api/?name='.urlencode($kelas->pengajar->nama).'&size=32&background=cce5ff&color=004b73'); ?>" alt="">
                <?php echo e($kelas->pengajar->nama); ?>

            </div>
            <h3 class="card-title"><?php echo e($kelas->nama_kelas); ?></h3>
            <p class="card-desc"><?php echo e($kelas->deskripsi); ?></p>
            <div class="mt-md">
                <div class="progress-label">
                    <span>Progress</span>
                    <span><?php echo e($kelas->submitted); ?>/<?php echo e($kelas->total_tugas); ?> tugas</span>
                </div>
                <div class="progress">
                    <div class="progress-bar" style="width: <?php echo e($kelas->progress); ?>%"></div>
                </div>
            </div>
        </div>
    </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php else: ?>
<div class="empty-state">
    <svg width="64" height="64" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
    <h3>Belum Ada Kelas</h3>
    <p>Mulai belajar dengan membeli kelas pertamamu.</p>
    <a href="<?php echo e(url('/mahasiswa/beli-kelas')); ?>" class="btn btn-primary">Cari Kelas</a>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\AplikasiSobatMedis\resources\views/mahasiswa/kelas/index.blade.php ENDPATH**/ ?>