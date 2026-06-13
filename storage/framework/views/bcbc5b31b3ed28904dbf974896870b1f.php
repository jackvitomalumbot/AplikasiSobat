<?php $__env->startSection('title', $kelas->nama_kelas); ?>

<?php $__env->startSection('content'); ?>
<a href="<?php echo e(url('/mahasiswa/kelas')); ?>" class="body-sm text-muted d-inline-flex align-center gap-xs mb-sm" style="text-decoration:none;">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
    Kembali ke Kelas Saya
</a>

<div class="d-flex justify-between align-start flex-wrap gap-md mb-xl">
    <div style="flex:1;min-width:0;">
        <h1 class="headline-lg"><?php echo e($kelas->nama_kelas); ?></h1>
        <p class="text-muted mt-sm"><?php echo e($kelas->deskripsi); ?></p>
        <div class="d-flex align-center gap-sm mt-md">
            <img src="<?php echo e($kelas->pengajar->foto_profile ? asset('storage/'.$kelas->pengajar->foto_profile) : 'https://ui-avatars.com/api/?name='.urlencode($kelas->pengajar->nama).'&size=40&background=cce5ff&color=004b73'); ?>" alt="" class="avatar" style="width:36px;height:36px;">
            <div>
                <div class="body-sm fw-600"><?php echo e($kelas->pengajar->nama); ?></div>
                <div class="body-sm text-muted">Pengajar</div>
            </div>
        </div>
    </div>
</div>


<h2 class="headline-md mb-lg">Jadwal Pertemuan & Tugas</h2>

<?php if($kelas->pertemuan->count()): ?>
<ul class="timeline">
    <?php $__currentLoopData = $kelas->pertemuan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <li class="timeline-item <?php echo e($item->isTugas() ? 'task' : ''); ?> <?php echo e(isset($submissions[$item->id]) ? 'completed' : ''); ?>">
        <div class="timeline-date">
            <?php echo e($item->tanggal->format('d M Y')); ?>

            · <span class="badge <?php echo e($item->isTugas() ? 'badge-warning' : 'badge-primary'); ?>"><?php echo e($item->isTugas() ? 'Tugas' : 'Pertemuan'); ?></span>
            <?php if($item->isTugas() && isset($submissions[$item->id])): ?>
                <span class="badge badge-success">Dikumpulkan</span>
            <?php endif; ?>
            
            <?php if(isset($absensiMap[$item->id])): ?>
                <?php $absensiStatus = $absensiMap[$item->id]->status; ?>
                <?php if($absensiStatus === 'hadir'): ?>
                    <span class="badge badge-success">✅ Hadir</span>
                <?php elseif($absensiStatus === 'izin'): ?>
                    <span class="badge badge-warning">📝 Izin</span>
                <?php else: ?>
                    <span class="badge badge-error">❌ Tidak Hadir</span>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        
        <a href="<?php echo e(url('/mahasiswa/pertemuan/' . $item->id)); ?>" class="timeline-title" style="text-decoration:none; color: var(--on-surface); display: block;">
            <?php echo e($item->judul); ?>

            <svg width="14" height="14" fill="none" stroke="var(--primary)" stroke-width="2" viewBox="0 0 24 24" style="vertical-align: middle; margin-left: 4px;"><polyline points="9 18 15 12 9 6"/></svg>
        </a>

        <?php if($item->deskripsi): ?>
            <div class="timeline-body"><?php echo e(Str::limit($item->deskripsi, 80)); ?></div>
        <?php endif; ?>

        
        <div class="d-flex align-center gap-xs flex-wrap mt-xs">
            <?php if($item->materiFiles->count()): ?>
                <span class="badge badge-neutral body-sm">📎 <?php echo e($item->materiFiles->count()); ?> file</span>
            <?php endif; ?>
            <?php if($item->isTugas() && $item->deadline): ?>
                <span class="badge <?php echo e(now()->gt($item->deadline) ? 'badge-error' : 'badge-neutral'); ?> body-sm">
                    ⏰ <?php echo e($item->deadline->format('d M H:i')); ?>

                </span>
            <?php endif; ?>
            <?php if($item->isTugas() && isset($submissions[$item->id]) && $submissions[$item->id]->nilai !== null): ?>
                <span class="badge badge-primary body-sm">Nilai: <?php echo e($submissions[$item->id]->nilai); ?></span>
            <?php endif; ?>
        </div>
    </li>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>
<?php else: ?>
<div class="empty-state">
    <p>Belum ada pertemuan untuk kelas ini.</p>
</div>
<?php endif; ?>


<?php if(isset($kuisList) && $kuisList->count()): ?>
<h2 class="headline-md mb-lg mt-xl">Kuis</h2>
<div class="d-flex gap-md flex-wrap">
    <?php $__currentLoopData = $kuisList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kuis): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $hasilKuis = $kuis->hasil->where('mahasiswa_id', auth()->id())->first();
        $isCompleted = $hasilKuis && $hasilKuis->isCompleted();
    ?>
    <div class="card" style="flex:1; min-width:260px; max-width:380px; cursor:default; border-top:3px solid <?php echo e($isCompleted ? 'var(--secondary)' : 'var(--primary)'); ?>;">
        <div class="card-body">
            <div class="d-flex justify-between align-center mb-sm">
                <h4 class="headline-sm" style="margin:0;"><?php echo e($kuis->judul); ?></h4>
                <?php if($isCompleted): ?>
                    <span class="badge badge-success">Selesai</span>
                <?php elseif($kuis->isExpired()): ?>
                    <span class="badge badge-danger">Expired</span>
                <?php else: ?>
                    <span class="badge badge-primary">Tersedia</span>
                <?php endif; ?>
            </div>
            <?php if($kuis->deskripsi): ?>
                <p class="body-sm text-muted mb-sm"><?php echo e(Str::limit($kuis->deskripsi, 60)); ?></p>
            <?php endif; ?>
            <div class="d-flex gap-sm flex-wrap mb-sm">
                <span class="badge badge-neutral"><?php echo e($kuis->soal->count()); ?> soal</span>
                <span class="badge badge-neutral"><?php echo e($kuis->durasi_menit); ?> mnt</span>
                <?php if($kuis->deadline): ?>
                    <span class="badge <?php echo e($kuis->isExpired() ? 'badge-danger' : 'badge-warning'); ?>"><?php echo e($kuis->deadline->format('d M H:i')); ?></span>
                <?php endif; ?>
            </div>
            <?php if($isCompleted): ?>
                <?php $lulus = $hasilKuis->nilai >= 75; ?>
                <div class="body-sm mb-xs" style="color:<?php echo e($lulus ? 'var(--secondary)' : 'var(--error)'); ?>;">
                    <strong>Nilai: <?php echo e($hasilKuis->nilai); ?></strong> · <?php echo e($hasilKuis->total_benar); ?>/<?php echo e($hasilKuis->max_poin); ?> benar
                </div>
                <div class="mb-sm">
                    <span class="badge <?php echo e($lulus ? 'badge-success' : 'badge-danger'); ?>">
                        <?php echo e($lulus ? '✅ Lulus KKM' : '❌ Tidak Lulus KKM'); ?>

                    </span>
                </div>
                <a href="<?php echo e(route('mahasiswa.kuis.hasil', $kuis)); ?>" class="btn btn-outline btn-sm">Lihat Hasil</a>
            <?php elseif(!$kuis->isExpired()): ?>
                <a href="<?php echo e(route('mahasiswa.kuis.show', $kuis)); ?>" class="btn btn-primary btn-sm">Kerjakan Kuis</a>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\AplikasiSobatMedis\resources\views/mahasiswa/kelas/show.blade.php ENDPATH**/ ?>