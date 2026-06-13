<?php $__env->startSection('title', $kui->judul); ?>

<?php const KKM = 75; ?>

<?php $__env->startSection('content'); ?>
<div class="mb-lg">
    <a href="<?php echo e(route('pengajar.kelas.show', $kelas)); ?>" class="body-sm text-muted d-inline-flex align-center gap-xs mb-sm" style="text-decoration:none;">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
        Kembali ke <?php echo e($kelas->nama_kelas); ?>

    </a>
    <div class="d-flex justify-between align-center flex-wrap gap-md">
        <div>
            <h1 class="headline-lg"><?php echo e($kui->judul); ?></h1>
            <p class="text-muted"><?php echo e($kui->deskripsi); ?></p>
        </div>
        <div class="d-flex gap-sm flex-wrap">
            <form method="POST" action="<?php echo e(route('pengajar.kuis.toggle', $kui)); ?>" style="display:inline;">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <button type="submit" class="btn <?php echo e($kui->is_active ? 'btn-outline' : 'btn-primary'); ?> btn-sm">
                    <?php echo e($kui->is_active ? 'Nonaktifkan' : 'Aktifkan'); ?>

                </button>
            </form>
            <form method="POST" action="<?php echo e(route('pengajar.kuis.destroy', $kui)); ?>" style="display:inline;">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button type="submit" class="btn btn-danger btn-sm" data-confirm="Hapus kuis <?php echo e($kui->judul); ?>?">Hapus Kuis</button>
            </form>
        </div>
    </div>
</div>


<?php
    $completedResults = $kui->hasil->where('waktu_selesai', '!=', null);
    $lulusCount = $completedResults->where('nilai', '>=', KKM)->count();
    $tidakLulusCount = $completedResults->where('nilai', '<', KKM)->count();
    $avgNilai = $completedResults->count() > 0 ? round($completedResults->avg('nilai'), 1) : 0;
?>
<div class="stats-grid mb-lg">
    <div class="stat-card">
        <div class="stat-value"><?php echo e($kui->soal->count()); ?></div>
        <div class="stat-label">Total Soal</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?php echo e($kui->soal->where('tipe', 'pilihan_ganda')->count()); ?></div>
        <div class="stat-label">Pilihan Ganda</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?php echo e($kui->soal->where('tipe', 'essay')->count()); ?></div>
        <div class="stat-label">Essay</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?php echo e($completedResults->count()); ?>/<?php echo e($enrolledCount); ?></div>
        <div class="stat-label">Dikerjakan</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" style="color:var(--secondary);"><?php echo e($lulusCount); ?></div>
        <div class="stat-label">✅ Lulus (≥<?php echo e(KKM); ?>)</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" style="color:var(--error);"><?php echo e($tidakLulusCount); ?></div>
        <div class="stat-label">❌ Tidak Lulus</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?php echo e($avgNilai); ?></div>
        <div class="stat-label">Rata-rata Nilai</div>
    </div>
</div>


<div class="d-flex gap-sm flex-wrap mb-lg">
    <span class="badge badge-primary">Durasi: <?php echo e($kui->durasi_menit); ?> menit</span>
    <span class="badge badge-warning">KKM: <?php echo e(KKM); ?></span>
    <span class="badge badge-neutral">Rumus: (Benar / Total Soal) × 100</span>
    <?php if($kui->deadline): ?>
        <span class="badge <?php echo e($kui->isExpired() ? 'badge-danger' : 'badge-warning'); ?>">
            Deadline: <?php echo e($kui->deadline->format('d M Y H:i')); ?>

        </span>
    <?php endif; ?>
    <span class="badge <?php echo e($kui->is_active ? 'badge-success' : 'badge-neutral'); ?>">
        <?php echo e($kui->is_active ? 'Aktif' : 'Nonaktif'); ?>

    </span>
    <?php if($kui->acak_soal): ?>
        <span class="badge badge-neutral">Urutan Soal Diacak</span>
    <?php endif; ?>
</div>


<div class="card mb-lg" style="cursor:default;">
    <div class="card-header"><h3 class="headline-sm">Daftar Soal</h3></div>
    <div class="card-body" style="padding:0;">
        <?php $__currentLoopData = $kui->soal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $soal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="deadline-item" style="flex-direction:column; align-items:flex-start;">
            <div class="d-flex align-center gap-sm mb-xs" style="width:100%;">
                <span class="badge <?php echo e($soal->isPilihanGanda() ? 'badge-primary' : 'badge-warning'); ?>" style="font-size:10px;">
                    <?php echo e($soal->isPilihanGanda() ? 'PG' : 'Essay'); ?>

                </span>
                <strong class="body-sm">Soal #<?php echo e($soal->nomor); ?></strong>
                <?php if($soal->isPilihanGanda()): ?>
                    <?php
                        $opsiCount = 0;
                        foreach(['opsi_a','opsi_b','opsi_c','opsi_d','opsi_e'] as $f) { if($soal->$f) $opsiCount++; }
                    ?>
                    <span class="body-xs text-muted"><?php echo e($opsiCount); ?> opsi</span>
                <?php endif; ?>
            </div>
            <p class="body-sm" style="margin:0;"><?php echo e($soal->pertanyaan); ?></p>
            <?php if($soal->isPilihanGanda()): ?>
                <div class="body-xs text-muted mt-xs" style="padding-left:8px;">
                    <?php $__currentLoopData = ['A' => 'opsi_a', 'B' => 'opsi_b', 'C' => 'opsi_c', 'D' => 'opsi_d', 'E' => 'opsi_e']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $letter => $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($soal->$field): ?>
                            <div style="<?php echo e($soal->jawaban_benar === $letter ? 'color:var(--secondary); font-weight:600;' : ''); ?>">
                                <?php echo e($letter); ?>. <?php echo e($soal->$field); ?> <?php echo $soal->jawaban_benar === $letter ? ' ✓' : ''; ?>

                            </div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>


<h2 class="headline-md mb-md">Hasil Mahasiswa</h2>

<?php if($kui->hasil->count()): ?>
<div class="table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Mahasiswa</th>
                <th>Waktu Mulai</th>
                <th>Waktu Selesai</th>
                <th>Benar / Total</th>
                <th>Nilai</th>
                <th>KKM (<?php echo e(KKM); ?>)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $kui->hasil->sortByDesc('nilai'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $lulus = $h->nilai !== null && $h->nilai >= KKM; ?>
            <tr>
                <td><?php echo e($idx + 1); ?></td>
                <td>
                    <div class="d-flex align-center gap-sm">
                        <img src="<?php echo e($h->mahasiswa->foto_profile ? asset('storage/'.$h->mahasiswa->foto_profile) : 'https://ui-avatars.com/api/?name='.urlencode($h->mahasiswa->nama).'&size=32&background=cce5ff&color=004b73'); ?>" class="avatar" style="width:28px;height:28px;">
                        <?php echo e($h->mahasiswa->nama); ?>

                    </div>
                </td>
                <td class="body-sm"><?php echo e($h->waktu_mulai?->format('d/m H:i') ?? '-'); ?></td>
                <td class="body-sm"><?php echo e($h->waktu_selesai?->format('d/m H:i') ?? '-'); ?></td>
                <td><strong><?php echo e($h->total_benar); ?></strong> / <?php echo e($h->max_poin); ?></td>
                <td>
                    <strong style="font-size:var(--font-size-md); color:<?php echo e($h->nilai !== null && $h->nilai >= KKM ? 'var(--secondary)' : 'var(--error)'); ?>;">
                        <?php echo e($h->nilai ?? '-'); ?>

                    </strong>
                </td>
                <td>
                    <?php if($h->isCompleted()): ?>
                        <?php if($lulus): ?>
                            <span class="badge badge-success">✅ Lulus</span>
                        <?php else: ?>
                            <span class="badge badge-danger">❌ Tidak Lulus</span>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="badge badge-neutral">-</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($h->isCompleted()): ?>
                        <?php
                            $hasUngradedEssay = $h->jawaban->contains(fn($j) => $j->soal->isEssay() && $j->is_correct === null);
                        ?>
                        <?php if($hasUngradedEssay): ?>
                            <span class="badge badge-warning">Perlu Dinilai</span>
                        <?php else: ?>
                            <span class="badge badge-success">Selesai</span>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="badge badge-neutral">Sedang Mengerjakan</span>
                    <?php endif; ?>
                </td>
            </tr>

            
            <?php $ungradedEssays = $h->jawaban->filter(fn($j) => $j->soal->isEssay() && $j->is_correct === null); ?>
            <?php if($ungradedEssays->count() && $h->isCompleted()): ?>
            <tr>
                <td colspan="8" style="background:var(--surface-container-low); padding:var(--space-md);">
                    <form method="POST" action="<?php echo e(route('pengajar.kuis.nilai-essay', $kui)); ?>">
                        <?php echo csrf_field(); ?>
                        <strong class="body-sm d-block mb-sm" style="color:var(--tertiary);">Nilai Essay — <?php echo e($h->mahasiswa->nama); ?></strong>
                        <?php $__currentLoopData = $ungradedEssays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jawaban): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="mb-md" style="padding-left:var(--space-sm); border-left:3px solid var(--tertiary-container);">
                            <div class="body-xs text-muted mb-xs">Soal #<?php echo e($jawaban->soal->nomor); ?>: <?php echo e(Str::limit($jawaban->soal->pertanyaan, 80)); ?></div>
                            <div class="body-sm mb-xs"><strong>Jawaban:</strong> <?php echo e($jawaban->jawaban ?: '(tidak dijawab)'); ?></div>
                            <?php if($jawaban->soal->jawaban_benar): ?>
                                <div class="body-xs text-muted mb-xs"><em>Kunci: <?php echo e(Str::limit($jawaban->soal->jawaban_benar, 100)); ?></em></div>
                            <?php endif; ?>
                            <div class="d-flex align-center gap-sm">
                                <input type="hidden" name="penilaian[<?php echo e($jawaban->id); ?>][jawaban_id]" value="<?php echo e($jawaban->id); ?>">
                                <label class="body-xs">Benar?</label>
                                <select class="form-control" name="penilaian[<?php echo e($jawaban->id); ?>][benar]" required style="width:120px;">
                                    <option value="">-- Pilih --</option>
                                    <option value="1">✓ Benar</option>
                                    <option value="0">✗ Salah</option>
                                </select>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <button type="submit" class="btn btn-primary btn-sm mt-sm">Simpan Penilaian Essay</button>
                    </form>
                </td>
            </tr>
            <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php else: ?>
<div class="empty-state">
    <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>
    <h3>Belum Ada Mahasiswa Mengerjakan</h3>
    <p>Hasil akan muncul setelah mahasiswa menyelesaikan kuis.</p>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\AplikasiSobatMedis\resources\views/pengajar/kuis/show.blade.php ENDPATH**/ ?>