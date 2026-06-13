<?php $__env->startSection('title', $kelas->nama_kelas); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-between align-center flex-wrap gap-md mb-lg">
    <div>
        <a href="<?php echo e(url('/pengajar/kelas')); ?>" class="body-sm text-muted d-inline-flex align-center gap-xs mb-sm" style="text-decoration:none;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
            Kembali ke Manajemen Kelas
        </a>
        <h1 class="headline-lg"><?php echo e($kelas->nama_kelas); ?></h1>
        <p class="text-muted"><?php echo e($kelas->deskripsi); ?></p>
    </div>
    <div class="d-flex gap-sm flex-wrap">
        <button class="btn btn-primary" data-modal-target="#modal-add-pertemuan" id="btn-add-pertemuan">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah Pertemuan/Tugas
        </button>
        <a href="<?php echo e(route('pengajar.absensi.pdf', $kelas)); ?>" class="btn btn-outline" target="_blank">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            Download Rekap Absensi (PDF)
        </a>
        <a href="<?php echo e(route('pengajar.kuis.create', $kelas)); ?>" class="btn btn-secondary">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 14l2 2 4-4"/></svg>
            Buat Kuis
        </a>
        <form method="POST" action="<?php echo e(url('/pengajar/kelas/' . $kelas->id)); ?>" style="display:inline;">
            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
            <button type="submit" class="btn btn-danger" data-confirm="Hapus kelas <?php echo e($kelas->nama_kelas); ?>? Semua data akan hilang.">Hapus Kelas</button>
        </form>
    </div>
</div>


<div class="card mb-lg" style="cursor:default;">
    <div class="card-header">
        <h3 class="headline-sm">Mahasiswa Terdaftar (<?php echo e($kelas->activeEnrollments->count()); ?>)</h3>
    </div>
    <div class="card-body" style="padding:0;">
        <?php if($kelas->activeEnrollments->count()): ?>
        <div style="max-height:200px;overflow-y:auto;">
            <?php $__currentLoopData = $kelas->activeEnrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="deadline-item">
                <img src="<?php echo e($enrollment->mahasiswa->foto_profile ? asset('storage/'.$enrollment->mahasiswa->foto_profile) : 'https://ui-avatars.com/api/?name='.urlencode($enrollment->mahasiswa->nama).'&size=40&background=cce5ff&color=004b73'); ?>" alt="" class="avatar" style="width:36px;height:36px;">
                <div class="deadline-content">
                    <div class="deadline-title"><?php echo e($enrollment->mahasiswa->nama); ?></div>
                    <div class="deadline-meta"><?php echo e($enrollment->mahasiswa->email); ?></div>
                </div>
                <span class="badge badge-success">Aktif</span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php else: ?>
        <p class="text-center text-muted" style="padding:var(--space-lg);">Belum ada mahasiswa terdaftar.</p>
        <?php endif; ?>
    </div>
</div>


<h2 class="headline-md mb-lg">Jadwal Pertemuan & Tugas</h2>

<?php if($kelas->pertemuan->count()): ?>
<ul class="timeline">
    <?php $__currentLoopData = $kelas->pertemuan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <li class="timeline-item <?php echo e($item->isTugas() ? 'task' : ''); ?>">
        <div class="timeline-date">
            <?php echo e($item->tanggal->format('d M Y')); ?>

            · <span class="badge <?php echo e($item->isTugas() ? 'badge-warning' : 'badge-primary'); ?>"><?php echo e($item->isTugas() ? 'Tugas' : 'Pertemuan'); ?></span>
            <?php
                $hadirCount = $item->absensi->where('status', 'hadir')->count();
                $totalEnrolled = $kelas->activeEnrollments->count();
            ?>
            <?php if($hadirCount > 0): ?>
                <span class="badge badge-success"><?php echo e($hadirCount); ?>/<?php echo e($totalEnrolled); ?> hadir</span>
            <?php endif; ?>
            <?php if($item->isTugas()): ?>
                <?php $subCount = $item->tugasSubmissions->count(); ?>
                <span class="badge badge-neutral"><?php echo e($subCount); ?>/<?php echo e($totalEnrolled); ?> tugas</span>
            <?php endif; ?>
        </div>

        
        <a href="<?php echo e(url('/pengajar/pertemuan/' . $item->id)); ?>" class="timeline-title" style="text-decoration:none; color: var(--on-surface); display: block;">
            <?php echo e($item->judul); ?>

            <svg width="14" height="14" fill="none" stroke="var(--primary)" stroke-width="2" viewBox="0 0 24 24" style="vertical-align: middle; margin-left: 4px;"><polyline points="9 18 15 12 9 6"/></svg>
        </a>

        <?php if($item->deskripsi): ?>
            <div class="timeline-body"><?php echo e(Str::limit($item->deskripsi, 80)); ?></div>
        <?php endif; ?>
        <?php if($item->isTugas() && $item->deadline): ?>
            <div class="timeline-body mt-xs"><strong>Deadline:</strong> <?php echo e($item->deadline->format('d M Y H:i')); ?></div>
        <?php endif; ?>
        <?php if($item->materiFiles->count()): ?>
            <div class="timeline-files">
                <?php $__currentLoopData = $item->materiFiles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(asset('storage/' . $file->file_path)); ?>" target="_blank" class="timeline-file">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    <?php echo e($file->file_name); ?>

                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <div class="d-flex gap-sm mt-sm align-center">
            <a href="<?php echo e(url('/pengajar/pertemuan/' . $item->id)); ?>" class="btn btn-outline btn-sm">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
                Absensi & Detail
            </a>
            <form method="POST" action="<?php echo e(url('/pengajar/pertemuan/' . $item->id)); ?>" style="display:inline;">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button type="submit" class="btn btn-ghost btn-sm" data-confirm="Hapus <?php echo e($item->isTugas() ? 'tugas' : 'pertemuan'); ?> ini?" style="color:var(--error);">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                    Hapus
                </button>
            </form>
        </div>
    </li>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>
<?php else: ?>
<div class="empty-state">
    <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
    <h3>Belum Ada Pertemuan</h3>
    <p>Tambahkan pertemuan atau tugas pertama untuk kelas ini.</p>
</div>
<?php endif; ?>


<h2 class="headline-md mb-lg mt-xl">Kuis</h2>

<?php if($kelas->kuis->count()): ?>
<div class="d-flex gap-md flex-wrap">
    <?php $__currentLoopData = $kelas->kuis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kuis): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="card" style="flex:1; min-width:280px; max-width:400px; cursor:default; border-top:3px solid <?php echo e($kuis->is_active ? 'var(--primary)' : 'var(--outline-variant)'); ?>;">
        <div class="card-body">
            <div class="d-flex justify-between align-center mb-sm">
                <h4 class="headline-sm" style="margin:0;"><?php echo e($kuis->judul); ?></h4>
                <span class="badge <?php echo e($kuis->is_active ? 'badge-success' : 'badge-neutral'); ?>"><?php echo e($kuis->is_active ? 'Aktif' : 'Nonaktif'); ?></span>
            </div>
            <?php if($kuis->deskripsi): ?>
                <p class="body-sm text-muted mb-sm"><?php echo e(Str::limit($kuis->deskripsi, 60)); ?></p>
            <?php endif; ?>
            <div class="d-flex gap-sm flex-wrap mb-sm">
                <span class="badge badge-primary"><?php echo e($kuis->soal->count()); ?> soal</span>
                <span class="badge badge-neutral"><?php echo e($kuis->durasi_menit); ?> menit</span>
                <?php
                    $pgCount = $kuis->soal->where('tipe', 'pilihan_ganda')->count();
                    $essayCount = $kuis->soal->where('tipe', 'essay')->count();
                ?>
                <?php if($pgCount): ?> <span class="badge badge-primary" style="font-size:10px;">PG: <?php echo e($pgCount); ?></span> <?php endif; ?>
                <?php if($essayCount): ?> <span class="badge badge-warning" style="font-size:10px;">Essay: <?php echo e($essayCount); ?></span> <?php endif; ?>
            </div>
            <?php if($kuis->deadline): ?>
                <div class="body-xs text-muted mb-sm">Deadline: <?php echo e($kuis->deadline->format('d M Y H:i')); ?></div>
            <?php endif; ?>
            <div class="body-xs text-muted mb-md">Dikerjakan: <?php echo e($kuis->hasil->where('waktu_selesai', '!=', null)->count()); ?>/<?php echo e($kelas->activeEnrollments->count()); ?></div>
            <div class="d-flex gap-sm">
                <a href="<?php echo e(route('pengajar.kuis.show', $kuis)); ?>" class="btn btn-outline btn-sm">Detail & Nilai</a>
                <form method="POST" action="<?php echo e(route('pengajar.kuis.destroy', $kuis)); ?>" style="display:inline;">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-ghost btn-sm" data-confirm="Hapus kuis <?php echo e($kuis->judul); ?>?" style="color:var(--error);">Hapus</button>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php else: ?>
<div class="empty-state">
    <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 14l2 2 4-4"/></svg>
    <h3>Belum Ada Kuis</h3>
    <p>Buat kuis baru dengan soal pilihan ganda dan essay.</p>
    <a href="<?php echo e(route('pengajar.kuis.create', $kelas)); ?>" class="btn btn-primary btn-sm">Buat Kuis</a>
</div>
<?php endif; ?>


<div class="modal-overlay" id="modal-add-pertemuan">
    <div class="modal">
        <div class="modal-header">
            <h3>Tambah Pertemuan / Tugas</h3>
            <button class="modal-close" aria-label="Close">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form method="POST" action="<?php echo e(url('/pengajar/kelas/' . $kelas->id . '/pertemuan')); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Tipe <span class="required">✱</span></label>
                    <select class="form-control" name="tipe" id="pertemuan-tipe" required>
                        <option value="pertemuan">Pertemuan</option>
                        <option value="tugas">Tugas</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Judul <span class="required">✱</span></label>
                    <input type="text" class="form-control" name="judul" placeholder="Judul pertemuan/tugas" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal <span class="required">✱</span></label>
                    <input type="date" class="form-control" name="tanggal" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea class="form-control" name="deskripsi" placeholder="Deskripsi singkat..." style="min-height:80px;"></textarea>
                </div>
                <div id="tugas-fields" style="display:none;">
                    <div class="form-group">
                        <label class="form-label">Deadline</label>
                        <input type="datetime-local" class="form-control" name="deadline">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Instruksi Tugas</label>
                        <textarea class="form-control" name="instruksi_tugas" placeholder="Instruksi pengerjaan tugas..." style="min-height:80px;"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Upload Materi (Opsional)</label>
                    <input type="file" class="form-file" name="files[]" multiple>
                    <span class="form-text">Bisa upload banyak file. Maks 10MB per file.</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.getElementById('pertemuan-tipe').addEventListener('change', function() {
    document.getElementById('tugas-fields').style.display = this.value === 'tugas' ? 'block' : 'none';
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\AplikasiSobatMedis\resources\views/pengajar/kelas/show.blade.php ENDPATH**/ ?>