<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Mahasiswa — SobatMedis</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 10px; color: #181c20; padding: 20px; }

        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #006194; padding-bottom: 15px; }
        .header h1 { font-size: 18px; color: #006194; margin-bottom: 4px; }
        .header h2 { font-size: 14px; font-weight: 600; color: #181c20; margin-bottom: 8px; }
        .header .meta { font-size: 10px; color: #707881; }

        .stats { display: table; width: 100%; margin-bottom: 16px; }
        .stat-box { display: table-cell; width: 33%; text-align: center; padding: 10px; border: 1px solid #bfc7d2; }
        .stat-box .value { font-size: 20px; font-weight: 800; color: #006194; }
        .stat-box .label { font-size: 9px; color: #707881; margin-top: 2px; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th, td { border: 1px solid #bfc7d2; padding: 6px 8px; text-align: left; font-size: 9px; }
        th { background: #ebeef4; font-weight: 700; color: #181c20; }

        .text-center { text-align: center; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 3px; font-size: 8px; font-weight: 600; }
        .badge-success { background: #dcfce7; color: #15803d; }
        .badge-neutral { background: #ebeef4; color: #3f4850; }

        .footer { margin-top: 30px; font-size: 9px; color: #707881; border-top: 1px solid #bfc7d2; padding-top: 10px; }
        .sign { margin-top: 40px; }
        .sign-line { border-top: 1px solid #181c20; width: 180px; margin-top: 40px; padding-top: 4px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>SobatMedis</h1>
        <h2>Data Mahasiswa</h2>
        <div class="meta">
            Total: <?php echo e($mahasiswaList->count()); ?> mahasiswa
            &nbsp;|&nbsp;
            Dicetak: <?php echo e(now()->translatedFormat('d F Y H:i')); ?>

        </div>
    </div>

    <div class="stats">
        <div class="stat-box">
            <div class="value"><?php echo e($mahasiswaList->count()); ?></div>
            <div class="label">Total Mahasiswa</div>
        </div>
        <div class="stat-box">
            <div class="value"><?php echo e($mahasiswaList->filter(fn($m) => $m->email_verified_at)->count()); ?></div>
            <div class="label">Email Terverifikasi</div>
        </div>
        <div class="stat-box">
            <div class="value"><?php echo e($mahasiswaList->unique(fn($m) => $m->mahasiswaDetail->universitas ?? '')->count()); ?></div>
            <div class="label">Universitas</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:25px;" class="text-center">No</th>
                <th>Nama Lengkap</th>
                <th>Email</th>
                <th>NIM</th>
                <th>Universitas</th>
                <th class="text-center">Kelas Diikuti</th>
                <th class="text-center">Email Verified</th>
                <th>Tgl Daftar</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $mahasiswaList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $mhs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td class="text-center"><?php echo e($idx + 1); ?></td>
                <td style="font-weight:600;"><?php echo e($mhs->nama); ?></td>
                <td><?php echo e($mhs->email); ?></td>
                <td><?php echo e($mhs->mahasiswaDetail->nim ?? '-'); ?></td>
                <td><?php echo e($mhs->mahasiswaDetail->universitas ?? '-'); ?></td>
                <td class="text-center"><?php echo e($mhs->enrollments->count()); ?></td>
                <td class="text-center">
                    <?php if($mhs->email_verified_at): ?>
                        <span class="badge badge-success">✓</span>
                    <?php else: ?>
                        <span class="badge badge-neutral">✗</span>
                    <?php endif; ?>
                </td>
                <td><?php echo e($mhs->created_at->format('d/m/Y')); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dihasilkan otomatis oleh sistem SobatMedis pada <?php echo e(now()->translatedFormat('d F Y, H:i')); ?> WIB</p>
        <div class="sign">
            <div>Administrator,</div>
            <div class="sign-line">Admin SobatMedis</div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\AplikasiSobatMedis\resources\views/admin/mahasiswa-pdf.blade.php ENDPATH**/ ?>