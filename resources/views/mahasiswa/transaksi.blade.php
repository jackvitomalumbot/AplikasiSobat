@extends('layouts.dashboard')
@section('title', 'Riwayat Transaksi')

@section('content')
<div class="page-header">
    <h1>Riwayat Transaksi</h1>
    <p>Semua riwayat pembayaran kelas Anda</p>
</div>

{{-- Summary Cards --}}
<div class="grid grid-3 mb-xl">
    <div class="stat-card">
        <div class="stat-icon" style="background:var(--primary-container);color:var(--on-primary-container);">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
        </div>
        <div class="stat-info">
            <span class="stat-value">{{ $transactions->total() }}</span>
            <span class="stat-label">Total Transaksi</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:var(--secondary-container);color:var(--on-secondary-container);">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <div class="stat-info">
            <span class="stat-value">{{ $paidCount }}</span>
            <span class="stat-label">Berhasil</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:var(--tertiary-container);color:var(--on-tertiary-container);">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
        </div>
        <div class="stat-info">
            <span class="stat-value">Rp {{ number_format($totalSpent, 0, ',', '.') }}</span>
            <span class="stat-label">Total Pengeluaran</span>
        </div>
    </div>
</div>

{{-- Transaction Table --}}
<div class="table-wrapper">
    <table class="table" id="table-transaksi">
        <thead>
            <tr>
                <th>#</th>
                <th>Kelas</th>
                <th>Pengajar</th>
                <th>Harga</th>
                <th>Status</th>
                <th>Metode</th>
                <th>ID Transaksi</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $i => $trx)
            <tr>
                <td>{{ $transactions->firstItem() + $i }}</td>
                <td>
                    <strong>{{ $trx->kelas->nama_kelas ?? 'Kelas Dihapus' }}</strong>
                </td>
                <td>{{ $trx->kelas->pengajar->nama ?? '-' }}</td>
                <td>Rp {{ number_format($trx->kelas->harga ?? 0, 0, ',', '.') }}</td>
                <td>
                    @if($trx->payment_status === 'paid')
                        <span class="badge badge-success">Berhasil</span>
                    @elseif($trx->payment_status === 'pending')
                        <span class="badge badge-warning">Menunggu</span>
                    @elseif($trx->payment_status === 'expired')
                        <span class="badge badge-muted">Kedaluwarsa</span>
                    @else
                        <span class="badge badge-danger">Gagal</span>
                    @endif
                </td>
                <td>
                    @if(str_starts_with($trx->payment_id ?? '', 'FREE-ADMIN'))
                        <span class="badge badge-info">Gratis (Admin)</span>
                    @elseif($trx->payment_type)
                        {{ ucwords(str_replace('_', ' ', $trx->payment_type)) }}
                    @else
                        -
                    @endif
                </td>
                <td style="font-family:monospace;font-size:12px;">{{ $trx->payment_id ?? '-' }}</td>
                <td>
                    @if($trx->paid_at)
                        {{ $trx->paid_at->format('d M Y H:i') }}
                    @elseif($trx->transaction_time)
                        {{ $trx->transaction_time->format('d M Y H:i') }}
                    @else
                        {{ $trx->created_at->format('d M Y H:i') }}
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center text-muted" style="padding:var(--space-xl);">
                    Belum ada transaksi. <a href="{{ url('/mahasiswa/beli-kelas') }}">Beli kelas</a> untuk memulai.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:var(--space-lg);">
    {{ $transactions->links() }}
</div>
@endsection
