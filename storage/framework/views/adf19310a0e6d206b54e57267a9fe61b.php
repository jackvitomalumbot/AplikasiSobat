<?php $__env->startSection('title', 'Data Pengajar'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-between align-center flex-wrap gap-md">
    <div class="page-header" style="margin-bottom:0;">
        <h1>Data Pengajar</h1>
        <p>Kelola semua pengajar</p>
    </div>
    <div class="d-flex gap-sm flex-wrap">
        <a href="<?php echo e(route('admin.pengajar.pdf')); ?>" class="btn btn-outline" target="_blank">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            Download PDF
        </a>
        <button class="btn btn-primary" data-modal-target="#modal-add-pengajar" id="btn-add-pengajar">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah Pengajar
        </button>
    </div>
</div>

<div class="search-bar mt-lg">
    <form method="GET" action="<?php echo e(url('/admin/pengajar')); ?>" style="display:flex;gap:var(--space-sm);flex:1;">
        <input type="text" class="form-control" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari nama, email, atau spesialisasi...">
        <button type="submit" class="btn btn-primary">Cari</button>
        <?php if(request('search')): ?>
            <a href="<?php echo e(url('/admin/pengajar')); ?>" class="btn btn-outline">Reset</a>
        <?php endif; ?>
    </form>
</div>

<div class="table-wrapper">
    <table class="table" id="table-pengajar">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Spesialisasi</th>
                <th>Jumlah Kelas</th>
                <th>Terdaftar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $pengajarList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $pgj): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($pengajarList->firstItem() + $i); ?></td>
                <td>
                    <div class="d-flex align-center gap-sm">
                        <img src="<?php echo e($pgj->foto_profile ? asset('storage/' . $pgj->foto_profile) : 'https://ui-avatars.com/api/?name=' . urlencode($pgj->nama) . '&size=40&background=cce5ff&color=004b73'); ?>" alt="" class="avatar" style="width:32px;height:32px;">
                        <?php echo e($pgj->nama); ?>

                    </div>
                </td>
                <td><?php echo e($pgj->email); ?></td>
                <td><?php echo e($pgj->pengajarDetail->spesialisasi ?? '-'); ?></td>
                <td><span class="badge badge-primary"><?php echo e($pgj->kelas_as_teacher_count); ?></span></td>
                <td><?php echo e($pgj->created_at->format('d M Y')); ?></td>
                <td>
                    <div class="d-flex gap-xs">
                        <button class="btn btn-outline btn-sm" onclick="openPasswordModal(<?php echo e($pgj->id); ?>, '<?php echo e(addslashes($pgj->nama)); ?>')">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                            Password
                        </button>
                        <form method="POST" action="<?php echo e(url('/admin/users/' . $pgj->id)); ?>" style="display:inline;">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger btn-sm" data-confirm="Hapus pengajar <?php echo e($pgj->nama); ?>?">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="7" class="text-center text-muted" style="padding:var(--space-xl);">Belum ada pengajar terdaftar.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div style="margin-top: var(--space-lg);">
    <?php echo e($pengajarList->withQueryString()->links()); ?>

</div>


<div class="modal-overlay" id="modal-add-pengajar">
    <div class="modal">
        <div class="modal-header">
            <h3>Tambah Pengajar Baru</h3>
            <button class="modal-close" aria-label="Close">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form method="POST" action="<?php echo e(url('/admin/pengajar')); ?>">
            <?php echo csrf_field(); ?>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="pgj-nama">Nama Lengkap <span class="required">✱</span></label>
                    <input type="text" class="form-control" id="pgj-nama" name="nama" placeholder="Dr. Nama Lengkap" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="pgj-email">Email <span class="required">✱</span></label>
                    <input type="email" class="form-control" id="pgj-email" name="email" placeholder="email@domain.com" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="pgj-password">Password <span class="required">✱</span></label>
                    <input type="password" class="form-control" id="pgj-password" name="password" placeholder="Min. 8 karakter" required minlength="8">
                </div>
                <div class="form-group">
                    <label class="form-label" for="pgj-spesialisasi">Spesialisasi</label>
                    <input type="text" class="form-control" id="pgj-spesialisasi" name="spesialisasi" placeholder="Contoh: Anatomi & Fisiologi">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Pengajar</button>
            </div>
        </form>
    </div>
</div>


<div class="modal-overlay" id="modal-change-password">
    <div class="modal">
        <div class="modal-header">
            <h3>Ganti Password <span id="password-user-name"></span></h3>
            <button class="modal-close" aria-label="Close">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form method="POST" id="form-change-password">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class="modal-body">
                <div class="alert alert-warning mb-md" style="display:flex;align-items:center;gap:8px;padding:12px 16px;border-radius:var(--radius-sm);background:var(--tertiary-container);color:var(--on-tertiary-container);">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    Password akan langsung diganti. Pastikan sudah benar!
                </div>
                <div class="form-group">
                    <label class="form-label">Password Baru <span class="required">✱</span></label>
                    <input type="password" class="form-control" name="new_password" placeholder="Min. 8 karakter" required minlength="8">
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password <span class="required">✱</span></label>
                    <input type="password" class="form-control" name="new_password_confirmation" placeholder="Ulangi password baru" required minlength="8">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                <button type="submit" class="btn btn-primary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                    Simpan Password
                </button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function openPasswordModal(userId, userName) {
    document.getElementById('password-user-name').textContent = userName;
    document.getElementById('form-change-password').action = '/admin/users/' + userId + '/password';
    const modal = document.getElementById('modal-change-password');
    modal.classList.add('active');
    modal.querySelector('input[name="new_password"]').value = '';
    modal.querySelector('input[name="new_password_confirmation"]').value = '';
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\AplikasiSobatMedis\resources\views/admin/pengajar.blade.php ENDPATH**/ ?>