<?php $__env->startSection('title', 'Hasil Kuis — ' . $kui->judul); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-lg">
    <a href="<?php echo e(route('mahasiswa.kelas.show', $kelas)); ?>" class="body-sm text-muted d-inline-flex align-center gap-xs mb-sm" style="text-decoration:none;">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
        Kembali ke <?php echo e($kelas->nama_kelas); ?>

    </a>
    <h1 class="headline-lg">Hasil: <?php echo e($kui->judul); ?></h1>
</div>


<?php
    $lulus = $hasil->nilai >= $kkm;
    $scoreColor = $lulus ? 'var(--secondary)' : 'var(--error)';
?>
<div class="card mb-lg" style="cursor:default; text-align:center; border-top:4px solid <?php echo e($scoreColor); ?>;">
    <div class="card-body" style="padding:var(--space-xl);">
        
        <div style="font-size:4rem; font-weight:800; color:<?php echo e($scoreColor); ?>; line-height:1;">
            <?php echo e($hasil->nilai ?? '—'); ?>

        </div>
        <p class="text-muted mb-sm" style="font-size:var(--font-size-md);">Nilai Anda</p>

        
        <div style="display:inline-block; padding:8px 24px; border-radius:var(--radius-full); font-weight:700; font-size:var(--font-size-md); margin-bottom:var(--space-lg);
            background:<?php echo e($lulus ? '#dcfce7' : 'var(--error-container)'); ?>;
            color:<?php echo e($lulus ? '#15803d' : 'var(--on-error-container)'); ?>;">
            <?php echo e($lulus ? '✅ LULUS — KKM Tercapai' : '❌ TIDAK LULUS — KKM Belum Tercapai'); ?>

        </div>

        <p class="body-sm text-muted mb-md">KKM (Kriteria Ketuntasan Minimal): <strong><?php echo e($kkm); ?></strong></p>

        
        <div class="d-flex justify-center gap-lg flex-wrap">
            <div>
                <div class="headline-sm"><?php echo e($hasil->total_benar); ?></div>
                <div class="body-xs text-muted">Jawaban Benar</div>
            </div>
            <div>
                <div class="headline-sm"><?php echo e($hasil->max_poin - $hasil->total_benar); ?></div>
                <div class="body-xs text-muted">Jawaban Salah</div>
            </div>
            <div>
                <div class="headline-sm"><?php echo e($hasil->max_poin); ?></div>
                <div class="body-xs text-muted">Total Soal</div>
            </div>
            <?php if($hasil->waktu_mulai && $hasil->waktu_selesai): ?>
            <?php
                $detik = $hasil->waktu_mulai->diffInSeconds($hasil->waktu_selesai);
                if ($detik < 60) {
                    $waktuLabel = '< 1 menit';
                } elseif ($detik < 3600) {
                    $waktuLabel = floor($detik / 60) . ' menit';
                } else {
                    $jam = floor($detik / 3600);
                    $mnt = floor(($detik % 3600) / 60);
                    $waktuLabel = $jam . ' jam ' . ($mnt > 0 ? $mnt . ' menit' : '');
                }
            ?>
            <div>
                <div class="headline-sm"><?php echo e($waktuLabel); ?></div>
                <div class="body-xs text-muted">Waktu Pengerjaan</div>
            </div>
            <?php endif; ?>
        </div>



        <?php $hasUngradedEssay = $hasil->jawaban->contains(fn($j) => $j->soal->isEssay() && $j->is_correct === null); ?>
        <?php if($hasUngradedEssay): ?>
            <div class="mt-md" style="padding:10px 16px; border-radius:var(--radius-sm); background:var(--tertiary-container); color:var(--on-tertiary-container); display:inline-block;">
                ⏳ Beberapa soal essay belum dinilai oleh pengajar. Nilai dapat berubah setelah penilaian essay.
            </div>
        <?php endif; ?>
    </div>
</div>


<h2 class="headline-md mb-md">Review Jawaban</h2>

<?php $jawabanMap = $hasil->jawaban->keyBy('kuis_soal_id'); ?>

<?php $__currentLoopData = $kui->soal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $soal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php $jwb = $jawabanMap[$soal->id] ?? null; ?>
<div class="card mb-md" style="cursor:default; border-left:3px solid <?php echo e($jwb && $jwb->is_correct === true ? 'var(--secondary)' : ($jwb && $jwb->is_correct === false ? 'var(--error)' : 'var(--outline-variant)')); ?>;">
    <div class="card-body">
        <div class="d-flex align-center gap-sm mb-sm">
            <span class="badge <?php echo e($soal->isPilihanGanda() ? 'badge-primary' : 'badge-warning'); ?>" style="font-size:10px;">
                <?php echo e($soal->isPilihanGanda() ? 'PG' : 'Essay'); ?>

            </span>
            <strong>Soal <?php echo e($soal->nomor); ?></strong>
            <?php if($jwb): ?>
                <?php if($jwb->is_correct === true): ?>
                    <span class="badge badge-success">✓ Benar</span>
                <?php elseif($jwb->is_correct === false): ?>
                    <span class="badge badge-danger">✗ Salah</span>
                <?php else: ?>
                    <span class="badge badge-neutral">Belum dinilai</span>
                <?php endif; ?>
            <?php else: ?>
                <span class="badge badge-neutral">Tidak dijawab</span>
            <?php endif; ?>
        </div>

        <p class="body-md mb-sm" style="white-space:pre-line;"><?php echo e($soal->pertanyaan); ?></p>

        <?php if($soal->isPilihanGanda()): ?>
            <?php $__currentLoopData = ['A' => 'opsi_a', 'B' => 'opsi_b', 'C' => 'opsi_c', 'D' => 'opsi_d', 'E' => 'opsi_e']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $letter => $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($soal->$field): ?>
                <?php
                    $isUserAnswer = $jwb && strtoupper($jwb->jawaban) === $letter;
                    $isCorrect = strtoupper($soal->jawaban_benar) === $letter;
                ?>
                <div class="d-flex align-center gap-sm mb-xs body-sm" style="padding:6px 10px; border-radius:var(--radius-sm); <?php echo e($isCorrect ? 'background:rgba(21,128,61,0.1); color:#15803d; font-weight:600;' : ($isUserAnswer && !$isCorrect ? 'background:rgba(186,26,26,0.1); color:#ba1a1a;' : '')); ?>">
                    <strong style="min-width:20px;"><?php echo e($letter); ?>.</strong>
                    <span><?php echo e($soal->$field); ?></span>
                    <?php if($isCorrect): ?> <span style="margin-left:auto;">✓ Jawaban benar</span> <?php endif; ?>
                    <?php if($isUserAnswer && !$isCorrect): ?> <span style="margin-left:auto;">← Jawaban Anda</span> <?php endif; ?>
                    <?php if($isUserAnswer && $isCorrect): ?> <span style="margin-left:auto;">✓ Jawaban Anda</span> <?php endif; ?>
                </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <div style="padding:10px 14px; border-radius:var(--radius-sm); background:var(--surface-container); margin-top:8px;">
                <div class="body-xs text-muted mb-xs">Jawaban Anda:</div>
                <p class="body-sm"><?php echo e($jwb->jawaban ?? '(tidak dijawab)'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\AplikasiSobatMedis\resources\views/mahasiswa/kuis/hasil.blade.php ENDPATH**/ ?>