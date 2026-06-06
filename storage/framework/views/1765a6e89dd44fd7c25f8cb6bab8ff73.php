<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Absensi — <?php echo e($kelas->nama_kelas); ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 10px; color: #181c20; padding: 20px; }

        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #006194; padding-bottom: 15px; }
        .header h1 { font-size: 18px; color: #006194; margin-bottom: 4px; }
        .header h2 { font-size: 14px; font-weight: 600; color: #181c20; margin-bottom: 8px; }
        .header .meta { font-size: 10px; color: #707881; }
        .header .meta span { margin: 0 8px; }

        .section-title { font-size: 12px; font-weight: 700; color: #006194; margin: 18px 0 8px; padding-bottom: 4px; border-bottom: 1px solid #bfc7d2; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; font-size: 9px; }
        th, td { border: 1px solid #bfc7d2; padding: 5px 6px; text-align: center; }
        th { background: #ebeef4; font-weight: 700; color: #181c20; font-size: 8px; }
        td { color: #3f4850; }

        .nama-col { text-align: left; font-weight: 600; color: #181c20; min-width: 100px; white-space: nowrap; }
        .nim-col { text-align: left; font-size: 8px; color: #707881; }

        .hadir { color: #15803d; font-weight: 700; font-size: 12px; }
        .tidak-hadir { color: #ba1a1a; font-weight: 700; font-size: 12px; }
        .izin { color: #894d00; font-weight: 700; font-size: 10px; }

        .date-header { font-size: 7px; line-height: 1.2; }
        .date-header .date-text { font-weight: 700; }
        .date-header .type-badge { display: inline-block; font-size: 6px; padding: 1px 4px; border-radius: 3px; color: #fff; margin-top: 2px; }
        .type-pertemuan { background: #006194; }
        .type-tugas { background: #894d00; }

        .summary-row td { background: #f1f4fa; font-weight: 700; }

        .footer { margin-top: 30px; font-size: 9px; color: #707881; }
        .footer .sign { margin-top: 50px; }
        .footer .sign-line { border-top: 1px solid #181c20; width: 180px; margin-top: 40px; padding-top: 4px; }

        .legend { display: inline-block; margin-right: 16px; font-size: 9px; color: #3f4850; }
        .legend-box { margin-bottom: 10px; }

        .stats-grid { display: table; width: 100%; margin-bottom: 16px; }
        .stat-box { display: table-cell; width: 25%; text-align: center; padding: 8px; border: 1px solid #bfc7d2; }
        .stat-box .value { font-size: 18px; font-weight: 800; color: #006194; }
        .stat-box .label { font-size: 8px; color: #707881; margin-top: 2px; }
    </style>
</head>
<body>
    
    <div class="header">
        <h1>SobatMedis</h1>
        <h2>Rekap Absensi: <?php echo e($kelas->nama_kelas); ?></h2>
        <div class="meta">
            <span>Pengajar: <?php echo e($kelas->pengajar->nama); ?></span>
            <span>|</span>
            <span>Total Mahasiswa: <?php echo e($mahasiswaList->count()); ?></span>
            <span>|</span>
            <span>Total Pertemuan: <?php echo e($pertemuanList->where('tipe', 'pertemuan')->count()); ?></span>
            <span>|</span>
            <span>Total Tugas: <?php echo e($pertemuanList->where('tipe', 'tugas')->count()); ?></span>
            <span>|</span>
            <span>Dicetak: <?php echo e(now()->translatedFormat('d F Y H:i')); ?></span>
        </div>
    </div>

    
    <div class="stats-grid">
        <div class="stat-box">
            <div class="value"><?php echo e($pertemuanList->count()); ?></div>
            <div class="label">Total Sesi</div>
        </div>
        <div class="stat-box">
            <div class="value"><?php echo e($mahasiswaList->count()); ?></div>
            <div class="label">Mahasiswa</div>
        </div>
        <div class="stat-box">
            <div class="value" style="color:#15803d;"><?php echo e($totalHadir); ?></div>
            <div class="label">Total Kehadiran</div>
        </div>
        <div class="stat-box">
            <div class="value" style="color:#ba1a1a;"><?php echo e($totalTidakHadir); ?></div>
            <div class="label">Total Absen</div>
        </div>
    </div>

    
    <div class="legend-box">
        <span class="legend"><span class="hadir">✓</span> = Hadir</span>
        <span class="legend"><span class="tidak-hadir">✗</span> = Tidak Hadir</span>
        <span class="legend"><span class="izin">I</span> = Izin</span>
        <span class="legend"><span class="hadir">✓*</span> = Otomatis (Tugas)</span>
    </div>

    
    <div class="section-title">Tabel Rekap Absensi</div>
    <table>
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="min-width: 100px; text-align: left;">Nama Mahasiswa</th>
                <th style="min-width: 60px; text-align: left;">NIM</th>
                <?php $__currentLoopData = $pertemuanList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <th style="min-width: 40px;">
                    <div class="date-header">
                        <div class="date-text"><?php echo e($p->tanggal->format('d/m')); ?></div>
                        <span class="type-badge <?php echo e($p->isTugas() ? 'type-tugas' : 'type-pertemuan'); ?>">
                            <?php echo e($p->isTugas() ? 'T' : 'P'); ?>

                        </span>
                    </div>
                </th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <th style="width: 35px;">Hadir</th>
                <th style="width: 35px;">Absen</th>
                <th style="width: 30px;">%</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $mahasiswaList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $mhs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $mhsHadir = 0;
                $mhsTidak = 0;
                $mhsIzin = 0;
            ?>
            <tr>
                <td><?php echo e($idx + 1); ?></td>
                <td class="nama-col"><?php echo e($mhs->nama); ?></td>
                <td class="nim-col"><?php echo e($mhs->mahasiswaDetail->nim ?? '-'); ?></td>
                <?php $__currentLoopData = $pertemuanList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $absensi = $absensiMatrix[$p->id][$mhs->id] ?? null;
                        $submission = $submissionMatrix[$p->id][$mhs->id] ?? null;
                        $status = $absensi ? $absensi->status : 'tidak_hadir';

                        if ($status === 'hadir') $mhsHadir++;
                        elseif ($status === 'izin') $mhsIzin++;
                        else $mhsTidak++;

                        $isAutoAbsen = $absensi && str_contains($absensi->keterangan ?? '', 'Otomatis');
                    ?>
                    <td>
                        <?php if($status === 'hadir'): ?>
                            <span class="hadir">✓<?php echo e($isAutoAbsen ? '*' : ''); ?></span>
                        <?php elseif($status === 'izin'): ?>
                            <span class="izin">I</span>
                        <?php else: ?>
                            <span class="tidak-hadir">✗</span>
                        <?php endif; ?>
                    </td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php $total = $pertemuanList->count(); $persen = $total > 0 ? round(($mhsHadir / $total) * 100) : 0; ?>
                <td style="font-weight:700; color:#15803d;"><?php echo e($mhsHadir); ?></td>
                <td style="font-weight:700; color:#ba1a1a;"><?php echo e($mhsTidak); ?></td>
                <td style="font-weight:700; color:<?php echo e($persen >= 75 ? '#15803d' : ($persen >= 50 ? '#894d00' : '#ba1a1a')); ?>;"><?php echo e($persen); ?>%</td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
        
        <tfoot>
            <tr class="summary-row">
                <td colspan="3" style="text-align:right; font-weight:700;">Total Hadir per Sesi:</td>
                <?php $__currentLoopData = $pertemuanList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $hadirPerSesi = collect($absensiMatrix[$p->id] ?? [])->where('status', 'hadir')->count();
                    ?>
                    <td style="font-weight:700;"><?php echo e($hadirPerSesi); ?>/<?php echo e($mahasiswaList->count()); ?></td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <td colspan="3"></td>
            </tr>
        </tfoot>
    </table>

    
    <?php if($pertemuanList->where('tipe', 'tugas')->count() > 0): ?>
    <div class="section-title">Rekap Pengumpulan Tugas</div>
    <table>
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="min-width: 100px; text-align: left;">Nama Mahasiswa</th>
                <?php $__currentLoopData = $pertemuanList->where('tipe', 'tugas'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <th>
                    <div class="date-header">
                        <div class="date-text"><?php echo e(Str::limit($t->judul, 15)); ?></div>
                        <div style="font-size:7px; color:#707881;"><?php echo e($t->tanggal->format('d/m/Y')); ?></div>
                    </div>
                </th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <th style="width:45px;">Dikumpulkan</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $mahasiswaList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $mhs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $totalSubmit = 0; ?>
            <tr>
                <td><?php echo e($idx + 1); ?></td>
                <td class="nama-col"><?php echo e($mhs->nama); ?></td>
                <?php $__currentLoopData = $pertemuanList->where('tipe', 'tugas'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $sub = $submissionMatrix[$t->id][$mhs->id] ?? null;
                        if ($sub) $totalSubmit++;
                    ?>
                    <td>
                        <?php if($sub): ?>
                            <span class="hadir">✓</span>
                            <?php if($sub->nilai !== null): ?>
                                <div style="font-size:8px; font-weight:700; color:#006194;"><?php echo e($sub->nilai); ?></div>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="tidak-hadir">✗</span>
                        <?php endif; ?>
                    </td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <td style="font-weight:700;"><?php echo e($totalSubmit); ?>/<?php echo e($pertemuanList->where('tipe', 'tugas')->count()); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php endif; ?>

    
    <div class="footer">
        <p>Dokumen ini dihasilkan otomatis oleh sistem SobatMedis pada <?php echo e(now()->translatedFormat('d F Y, H:i')); ?> WIB</p>
        <div class="sign">
            <div>Pengajar,</div>
            <div class="sign-line"><?php echo e($kelas->pengajar->nama); ?></div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\AplikasiSobatMedis\resources\views/pengajar/kelas/absensi-pdf.blade.php ENDPATH**/ ?>