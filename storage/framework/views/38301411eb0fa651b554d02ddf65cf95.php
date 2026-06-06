<?php $__env->startSection('title', 'Dashboard Mahasiswa'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h1>Halo, <?php echo e(auth()->user()->nama); ?>! 👋</h1>
    <p>Berikut ringkasan tugas dan deadline kamu</p>
</div>


<div class="card mb-xl" style="cursor:default;">
    <div class="card-header d-flex justify-between align-center">
        <h2 class="headline-sm">Deadline Mendatang</h2>
        <span class="badge badge-warning"><?php echo e($deadlines->count()); ?> tugas</span>
    </div>
    <div class="card-body" style="padding:0;">
        <?php $__empty_1 = true; $__currentLoopData = $deadlines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deadline): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="deadline-item">
            <?php
                $daysLeft = now()->diffInDays($deadline->deadline, false);
                $iconClass = $daysLeft <= 2 ? 'urgent' : ($daysLeft <= 7 ? 'normal' : 'done');
            ?>
            <div class="deadline-icon <?php echo e($iconClass); ?>">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div class="deadline-content">
                <div class="deadline-title"><?php echo e($deadline->judul); ?></div>
                <div class="deadline-meta"><?php echo e($deadline->kelas->nama_kelas); ?> · Deadline: <?php echo e($deadline->deadline->format('d M Y, H:i')); ?></div>
            </div>
            <div class="deadline-badge">
                <?php if($daysLeft <= 0): ?>
                    <span class="badge badge-error">Overdue</span>
                <?php elseif($daysLeft <= 2): ?>
                    <span class="badge badge-warning"><?php echo e($daysLeft); ?> hari lagi</span>
                <?php else: ?>
                    <span class="badge badge-primary"><?php echo e($daysLeft); ?> hari lagi</span>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="text-center text-muted" style="padding:var(--space-xl);">Tidak ada deadline mendatang. 🎉</p>
        <?php endif; ?>
    </div>
</div>


<h2 class="headline-md mb-lg">Semua Tugas</h2>

<?php $__empty_1 = true; $__currentLoopData = $allTugas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tugas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<div class="card mb-md" style="cursor:default;">
    <div class="card-body d-flex justify-between align-center gap-md flex-wrap">
        <div style="flex:1;min-width:0;">
            <div class="d-flex align-center gap-sm mb-xs">
                <span class="badge <?php echo e($tugas->tugasSubmissions->count() ? 'badge-success' : 'badge-warning'); ?>">
                    <?php echo e($tugas->tugasSubmissions->count() ? 'Sudah Dikumpulkan' : 'Belum Dikumpulkan'); ?>

                </span>
            </div>
            <h3 class="headline-sm" style="font-size:16px;"><?php echo e($tugas->judul); ?></h3>
            <p class="body-sm text-muted"><?php echo e($tugas->kelas->nama_kelas); ?> · Deadline: <?php echo e($tugas->deadline?->format('d M Y, H:i') ?? '-'); ?></p>
            <?php if($tugas->tugasSubmissions->first()?->nilai !== null): ?>
                <p class="body-sm mt-xs"><strong>Nilai:</strong> <?php echo e($tugas->tugasSubmissions->first()->nilai); ?></p>
            <?php endif; ?>
        </div>
        <a href="<?php echo e(url('/mahasiswa/kelas/' . $tugas->kelas_id)); ?>" class="btn btn-outline btn-sm">Lihat Kelas</a>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<div class="empty-state">
    <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
    <h3>Belum Ada Tugas</h3>
    <p>Bergabung dengan kelas untuk mulai menerima tugas.</p>
    <a href="<?php echo e(url('/mahasiswa/beli-kelas')); ?>" class="btn btn-primary">Cari Kelas</a>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\AplikasiSobatMedis\resources\views/mahasiswa/dashboard.blade.php ENDPATH**/ ?>