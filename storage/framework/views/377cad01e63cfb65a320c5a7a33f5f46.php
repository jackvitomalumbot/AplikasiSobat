<?php $__env->startSection('title', $pertemuan->judul); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <a href="<?php echo e(url('/mahasiswa/kelas/' . $kelas->id)); ?>" class="btn btn-ghost btn-sm mb-sm" style="margin-left: -8px;">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
        Kembali ke <?php echo e($kelas->nama_kelas); ?>

    </a>
    <h1><?php echo e($pertemuan->judul); ?></h1>
    <div class="d-flex align-center gap-sm flex-wrap mt-xs">
        <span class="badge <?php echo e($pertemuan->isTugas() ? 'badge-warning' : 'badge-primary'); ?>"><?php echo e(ucfirst($pertemuan->tipe)); ?></span>
        <span class="text-muted body-sm"><?php echo e($pertemuan->tanggal->translatedFormat('d F Y')); ?></span>
        <?php if($pertemuan->isTugas() && $pertemuan->deadline): ?>
            <?php $isLate = now()->greaterThan($pertemuan->deadline); ?>
            <span class="badge <?php echo e($isLate ? 'badge-error' : 'badge-neutral'); ?>">
                Deadline: <?php echo e($pertemuan->deadline->translatedFormat('d M Y H:i')); ?>

                <?php echo e($isLate ? '(Lewat)' : ''); ?>

            </span>
        <?php endif; ?>
    </div>
</div>

<div class="grid grid-2" style="margin-bottom: var(--space-xl);">
    
    <div class="card">
        <div class="card-body">
            <h3 class="headline-sm mb-md">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align: middle;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                Deskripsi
            </h3>
            <?php if($pertemuan->deskripsi): ?>
                <p><?php echo e($pertemuan->deskripsi); ?></p>
            <?php else: ?>
                <p class="text-muted">Tidak ada deskripsi.</p>
            <?php endif; ?>

            <?php if($pertemuan->isTugas() && $pertemuan->instruksi_tugas): ?>
                <div class="mt-lg" style="padding: var(--space-md); background: var(--surface-container-low); border-radius: var(--radius-sm); border-left: 3px solid var(--tertiary);">
                    <span class="fw-600 body-sm" style="color: var(--tertiary);">📋 Instruksi Tugas</span>
                    <p class="mt-xs"><?php echo e($pertemuan->instruksi_tugas); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="card">
        <div class="card-body">
            <h3 class="headline-sm mb-md">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align: middle;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Status Anda
            </h3>

            
            <div class="mb-lg" style="padding: var(--space-md); border-radius: var(--radius-sm); border: 1px solid var(--outline-variant);">
                <span class="body-sm fw-600">Absensi:</span>
                <?php if($absensi): ?>
                    <?php if($absensi->status === 'hadir'): ?>
                        <span class="badge badge-success" style="margin-left: 8px;">✅ Hadir</span>
                    <?php elseif($absensi->status === 'izin'): ?>
                        <span class="badge badge-warning" style="margin-left: 8px;">📝 Izin</span>
                    <?php else: ?>
                        <span class="badge badge-error" style="margin-left: 8px;">❌ Tidak Hadir</span>
                    <?php endif; ?>
                    <?php if($absensi->keterangan): ?>
                        <p class="body-sm text-muted mt-xs"><?php echo e($absensi->keterangan); ?></p>
                    <?php endif; ?>
                <?php else: ?>
                    <span class="badge badge-neutral" style="margin-left: 8px;">Belum dicatat</span>
                <?php endif; ?>
            </div>

            
            <?php if($pertemuan->isTugas()): ?>
                <div style="padding: var(--space-md); border-radius: var(--radius-sm); border: 1px solid var(--outline-variant);">
                    <span class="body-sm fw-600">Tugas:</span>
                    <?php if($submission): ?>
                        <span class="badge badge-success" style="margin-left: 8px;">✅ Sudah dikumpulkan</span>
                        <div class="mt-sm">
                            <a href="<?php echo e(asset('storage/' . $submission->file_path)); ?>" target="_blank" class="timeline-file" style="display:inline-flex;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                Lihat File Saya
                            </a>
                        </div>
                        <?php if($submission->catatan): ?>
                            <p class="body-sm text-muted mt-xs">Catatan: <?php echo e($submission->catatan); ?></p>
                        <?php endif; ?>
                        <?php if($submission->nilai !== null): ?>
                            <div class="mt-sm" style="padding: var(--space-sm) var(--space-md); background: var(--primary-fixed); border-radius: var(--radius-sm);">
                                <span class="body-sm">Nilai: </span>
                                <span class="fw-700" style="font-size: var(--font-size-xl); color: var(--primary);"><?php echo e($submission->nilai); ?></span>
                                <span class="body-sm text-muted">/100</span>
                            </div>
                        <?php else: ?>
                            <p class="body-sm text-muted mt-sm">⏳ Menunggu penilaian dari pengajar.</p>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="badge badge-error" style="margin-left: 8px;">❌ Belum dikumpulkan</span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>


<?php if($pertemuan->materiFiles->count() > 0): ?>
<div class="card" style="margin-bottom: var(--space-xl);">
    <div class="card-body">
        <h3 class="headline-sm mb-md">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align: middle;"><path d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48"/></svg>
            File Materi
        </h3>
        <div class="timeline-files">
            <?php $__currentLoopData = $pertemuan->materiFiles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(asset('storage/' . $file->file_path)); ?>" target="_blank" class="timeline-file">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    <?php echo e($file->file_name); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<?php endif; ?>


<?php if($pertemuan->isTugas()): ?>
<div class="card">
    <div class="card-header">
        <h3 class="headline-sm">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align: middle;"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            <?php echo e($submission ? 'Perbarui Tugas' : 'Kumpulkan Tugas'); ?>

        </h3>
    </div>
    <div class="card-body">
        <?php $isLate = $pertemuan->deadline && now()->greaterThan($pertemuan->deadline); ?>

        <?php if($isLate && !$submission): ?>
            <div class="alert alert-error mb-lg" style="margin: 0 0 var(--space-lg) 0;">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                Deadline sudah lewat. Anda masih bisa mengumpulkan tugas, namun akan tercatat terlambat.
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('mahasiswa.tugas.submit', $pertemuan)); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label class="form-label">File Tugas <span class="required">*</span></label>
                <input type="file" name="file" class="form-file" required>
                <span class="form-text">Maks. 10MB. Format: PDF, DOC, DOCX, ZIP, RAR, JPG, PNG</span>
                <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="form-error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="form-label">Catatan (opsional)</label>
                <textarea name="catatan" class="form-control" rows="3" placeholder="Tambahkan catatan untuk pengajar..."><?php echo e($submission->catatan ?? ''); ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                <?php echo e($submission ? 'Perbarui Tugas' : 'Kumpulkan Tugas'); ?>

            </button>

            <?php if($submission): ?>
                <p class="body-sm text-muted mt-sm">
                    ⚠️ Mengirim ulang akan menggantikan file sebelumnya. Absensi otomatis tetap tercatat.
                </p>
            <?php else: ?>
                <p class="body-sm text-muted mt-sm">
                    💡 Mengumpulkan tugas akan otomatis mencatat kehadiran Anda.
                </p>
            <?php endif; ?>
        </form>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\AplikasiSobatMedis\resources\views/mahasiswa/kelas/pertemuan.blade.php ENDPATH**/ ?>