<?php $__env->startSection('title', $pertemuan->judul); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div class="d-flex justify-between align-center flex-wrap gap-md">
        <div>
            <a href="<?php echo e(url('/pengajar/kelas/' . $kelas->id)); ?>" class="btn btn-ghost btn-sm mb-sm" style="margin-left: -8px;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                Kembali ke <?php echo e($kelas->nama_kelas); ?>

            </a>
            <h1><?php echo e($pertemuan->judul); ?></h1>
            <p>
                <span class="badge <?php echo e($pertemuan->isTugas() ? 'badge-warning' : 'badge-primary'); ?>"><?php echo e(ucfirst($pertemuan->tipe)); ?></span>
                <span class="text-muted" style="margin-left: 8px;"><?php echo e($pertemuan->tanggal->translatedFormat('d F Y')); ?></span>
                <?php if($pertemuan->isTugas() && $pertemuan->deadline): ?>
                    <span class="text-muted" style="margin-left: 8px;">• Deadline: <?php echo e($pertemuan->deadline->translatedFormat('d M Y H:i')); ?></span>
                <?php endif; ?>
            </p>
        </div>
        <form method="POST" action="<?php echo e(route('pengajar.pertemuan.destroy', $pertemuan)); ?>" onsubmit="return confirm('Yakin hapus pertemuan/tugas ini?')">
            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
            <button type="submit" class="btn btn-danger btn-sm">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                Hapus
            </button>
        </form>
    </div>
</div>


<div class="grid grid-2" style="margin-bottom: var(--space-xl);">
    <div class="card">
        <div class="card-body">
            <h3 class="headline-sm mb-md">Detail</h3>
            <?php if($pertemuan->deskripsi): ?>
                <p class="mb-md"><?php echo e($pertemuan->deskripsi); ?></p>
            <?php endif; ?>
            <?php if($pertemuan->isTugas() && $pertemuan->instruksi_tugas): ?>
                <div class="mb-md">
                    <span class="fw-600 body-sm">Instruksi Tugas:</span>
                    <p class="mt-xs"><?php echo e($pertemuan->instruksi_tugas); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h3 class="headline-sm mb-md">File Materi</h3>
            <?php if($pertemuan->materiFiles->count() > 0): ?>
                <div class="timeline-files">
                    <?php $__currentLoopData = $pertemuan->materiFiles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(asset('storage/' . $file->file_path)); ?>" target="_blank" class="timeline-file">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                            <?php echo e($file->file_name); ?>

                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <p class="text-muted body-sm">Belum ada file materi.</p>
            <?php endif; ?>
        </div>
    </div>
</div>


<div class="card" style="margin-bottom: var(--space-xl);">
    <div class="card-header d-flex justify-between align-center">
        <h3 class="headline-sm">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align: middle;"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
            Absensi Mahasiswa
        </h3>
        <span class="badge badge-neutral"><?php echo e($enrolledMahasiswa->count()); ?> mahasiswa</span>
    </div>
    <div class="card-body">
        <?php if($enrolledMahasiswa->count() > 0): ?>
            <form method="POST" action="<?php echo e(route('pengajar.absensi.store', $pertemuan)); ?>">
                <?php echo csrf_field(); ?>
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Mahasiswa</th>
                                <th>Email</th>
                                <th style="text-align: center;">Hadir</th>
                                <th style="text-align: center;">Tidak Hadir</th>
                                <th style="text-align: center;">Izin</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $enrolledMahasiswa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $mhs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $current = $absensiMap[$mhs->id] ?? null;
                                    $currentStatus = $current ? $current->status : 'tidak_hadir';
                                ?>
                                <tr>
                                    <td><?php echo e($idx + 1); ?></td>
                                    <td>
                                        <div class="d-flex align-center gap-xs">
                                            <img src="<?php echo e($mhs->foto_profile ? asset('storage/' . $mhs->foto_profile) : 'https://ui-avatars.com/api/?name=' . urlencode($mhs->nama) . '&size=32&background=cce5ff&color=004b73'); ?>" alt="" style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
                                            <?php echo e($mhs->nama); ?>

                                        </div>
                                    </td>
                                    <td class="text-muted body-sm"><?php echo e($mhs->email); ?></td>
                                    <td style="text-align:center;">
                                        <input type="hidden" name="absensi[<?php echo e($idx); ?>][mahasiswa_id]" value="<?php echo e($mhs->id); ?>">
                                        <input type="radio" name="absensi[<?php echo e($idx); ?>][status]" value="hadir" <?php echo e($currentStatus === 'hadir' ? 'checked' : ''); ?> style="width:18px;height:18px;accent-color:var(--secondary);">
                                    </td>
                                    <td style="text-align:center;">
                                        <input type="radio" name="absensi[<?php echo e($idx); ?>][status]" value="tidak_hadir" <?php echo e($currentStatus === 'tidak_hadir' ? 'checked' : ''); ?> style="width:18px;height:18px;accent-color:var(--error);">
                                    </td>
                                    <td style="text-align:center;">
                                        <input type="radio" name="absensi[<?php echo e($idx); ?>][status]" value="izin" <?php echo e($currentStatus === 'izin' ? 'checked' : ''); ?> style="width:18px;height:18px;accent-color:var(--tertiary);">
                                    </td>
                                    <td>
                                        <input type="text" name="absensi[<?php echo e($idx); ?>][keterangan]" value="<?php echo e($current->keterangan ?? ''); ?>" class="form-control" placeholder="Opsional" style="padding:6px 10px;font-size:12px;">
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: var(--space-lg); text-align: right;">
                    <button type="submit" class="btn btn-primary">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
                        Simpan Absensi
                    </button>
                </div>
            </form>
        <?php else: ?>
            <div class="empty-state">
                <p>Belum ada mahasiswa yang terdaftar di kelas ini.</p>
            </div>
        <?php endif; ?>
    </div>
</div>


<?php if($pertemuan->isTugas()): ?>
<div class="card">
    <div class="card-header d-flex justify-between align-center">
        <h3 class="headline-sm">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align: middle;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            Pengumpulan Tugas
        </h3>
        <span class="badge badge-neutral"><?php echo e($submissionMap->count()); ?>/<?php echo e($enrolledMahasiswa->count()); ?> dikumpulkan</span>
    </div>
    <div class="card-body">
        <?php if($enrolledMahasiswa->count() > 0): ?>
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Mahasiswa</th>
                            <th>Status</th>
                            <th>File</th>
                            <th>Catatan</th>
                            <th>Dikumpulkan</th>
                            <th>Nilai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $enrolledMahasiswa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $mhs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $sub = $submissionMap[$mhs->id] ?? null; ?>
                            <tr>
                                <td><?php echo e($idx + 1); ?></td>
                                <td><?php echo e($mhs->nama); ?></td>
                                <td>
                                    <?php if($sub): ?>
                                        <span class="badge badge-success">Sudah</span>
                                    <?php else: ?>
                                        <span class="badge badge-error">Belum</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($sub): ?>
                                        <a href="<?php echo e(asset('storage/' . $sub->file_path)); ?>" target="_blank" class="timeline-file" style="display:inline-flex;">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                            Download
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="body-sm"><?php echo e($sub->catatan ?? '-'); ?></td>
                                <td class="body-sm text-muted"><?php echo e($sub ? $sub->created_at->translatedFormat('d M Y H:i') : '-'); ?></td>
                                <td>
                                    <?php if($sub): ?>
                                        <span class="fw-600" style="color: var(--primary);"><?php echo e($sub->nilai ?? '-'); ?></span>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($sub): ?>
                                        <form method="POST" action="<?php echo e(route('pengajar.nilai.store', $pertemuan)); ?>" class="d-inline-flex gap-xs align-center">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="submission_id" value="<?php echo e($sub->id); ?>">
                                            <input type="number" name="nilai" value="<?php echo e($sub->nilai); ?>" min="0" max="100" class="form-control" style="width:70px;padding:4px 8px;font-size:12px;" placeholder="0-100">
                                            <button type="submit" class="btn btn-primary btn-sm" style="padding:4px 10px;">
                                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <p>Belum ada mahasiswa yang terdaftar di kelas ini.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\AplikasiSobatMedis\resources\views/pengajar/kelas/pertemuan.blade.php ENDPATH**/ ?>