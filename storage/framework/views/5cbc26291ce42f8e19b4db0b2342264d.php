<?php $__env->startSection('title', 'Profile Pengajar'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h1>Profile</h1>
    <p>Kelola informasi profile Anda</p>
</div>

<div class="card" style="max-width:600px;cursor:default;">
    <div class="card-body">
        <div class="text-center mb-xl">
            <img src="<?php echo e($user->foto_profile ? asset('storage/'.$user->foto_profile) : 'https://ui-avatars.com/api/?name='.urlencode($user->nama).'&size=128&background=cce5ff&color=004b73'); ?>" alt="<?php echo e($user->nama); ?>" class="avatar avatar-xl" style="margin:0 auto;">
            <h2 class="headline-md mt-md"><?php echo e($user->nama); ?></h2>
            <p class="text-muted"><?php echo e($user->email); ?></p>
        </div>

        <form method="POST" action="<?php echo e(url('/pengajar/profile')); ?>" enctype="multipart/form-data" id="form-profile-pengajar">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

            <div class="form-group">
                <label class="form-label" for="foto_profile">Foto Profile</label>
                <input type="file" class="form-file" id="foto_profile" name="foto_profile" accept="image/*">
            </div>

            <div class="form-group">
                <label class="form-label" for="spesialisasi">Spesialisasi</label>
                <input type="text" class="form-control" id="spesialisasi" name="spesialisasi" value="<?php echo e($user->pengajarDetail->spesialisasi ?? ''); ?>" placeholder="Contoh: Anatomi & Fisiologi">
            </div>

            <div class="form-group">
                <label class="form-label" for="kontak">Nomor Kontak</label>
                <input type="text" class="form-control" id="kontak" name="kontak" value="<?php echo e($user->pengajarDetail->kontak ?? ''); ?>" placeholder="08xxxxxxxxxx">
            </div>

            <div class="form-group">
                <label class="form-label" for="bio">Bio</label>
                <textarea class="form-control" id="bio" name="bio" placeholder="Ceritakan tentang diri Anda..."><?php echo e($user->pengajarDetail->bio ?? ''); ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Simpan Perubahan</button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\AplikasiSobatMedis\resources\views/pengajar/profile.blade.php ENDPATH**/ ?>