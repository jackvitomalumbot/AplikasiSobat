<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pengajar — SobatMedis</title>
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

        .footer { margin-top: 30px; font-size: 9px; color: #707881; border-top: 1px solid #bfc7d2; padding-top: 10px; }
        .sign { margin-top: 40px; }
        .sign-line { border-top: 1px solid #181c20; width: 180px; margin-top: 40px; padding-top: 4px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>SobatMedis</h1>
        <h2>Data Pengajar</h2>
        <div class="meta">
            Total: {{ $pengajarList->count() }} pengajar
            &nbsp;|&nbsp;
            Dicetak: {{ now()->translatedFormat('d F Y H:i') }}
        </div>
    </div>

    <div class="stats">
        <div class="stat-box">
            <div class="value">{{ $pengajarList->count() }}</div>
            <div class="label">Total Pengajar</div>
        </div>
        <div class="stat-box">
            <div class="value">{{ $pengajarList->sum('kelas_as_teacher_count') }}</div>
            <div class="label">Total Kelas</div>
        </div>
        <div class="stat-box">
            <div class="value">{{ $pengajarList->filter(fn($p) => $p->pengajarDetail && $p->pengajarDetail->spesialisasi)->unique(fn($p) => $p->pengajarDetail->spesialisasi)->count() }}</div>
            <div class="label">Spesialisasi</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:25px;" class="text-center">No</th>
                <th>Nama Lengkap</th>
                <th>Email</th>
                <th>Spesialisasi</th>
                <th class="text-center">Jumlah Kelas</th>
                <th>Tgl Daftar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengajarList as $idx => $pgj)
            <tr>
                <td class="text-center">{{ $idx + 1 }}</td>
                <td style="font-weight:600;">{{ $pgj->nama }}</td>
                <td>{{ $pgj->email }}</td>
                <td>{{ $pgj->pengajarDetail->spesialisasi ?? '-' }}</td>
                <td class="text-center" style="font-weight:700; color:#006194;">{{ $pgj->kelas_as_teacher_count }}</td>
                <td>{{ $pgj->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dihasilkan otomatis oleh sistem SobatMedis pada {{ now()->translatedFormat('d F Y, H:i') }} WIB</p>
        <div class="sign">
            <div>Administrator,</div>
            <div class="sign-line">Admin SobatMedis</div>
        </div>
    </div>
</body>
</html>
