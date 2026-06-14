@extends('layouts.admin')
@section('title', 'Riwayat Transaksi')

@section('content')
<div class="page-header">
    <h1>Riwayat Transaksi</h1>
    <p>Semua transaksi pembayaran kelas di platform</p>
</div>

{{-- Summary Cards --}}
<div class="grid grid-4 mb-xl">
    <div class="stat-card">
        <div class="stat-icon" style="background:var(--primary-container);color:var(--on-primary-container);">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
        </div>
        <div class="stat-info">
            <span class="stat-value">{{ $totalTransactions }}</span>
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
        <div class="stat-icon" style="background:#fff3cd;color:#856404;">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div class="stat-info">
            <span class="stat-value">{{ $pendingCount }}</span>
            <span class="stat-label">Pending</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:var(--tertiary-container);color:var(--on-tertiary-container);">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
        </div>
        <div class="stat-info">
            <span class="stat-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
            <span class="stat-label">Total Pendapatan</span>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="search-bar mb-lg">
    <form method="GET" action="{{ route('admin.transaksi') }}" style="display:flex;gap:var(--space-sm);flex:1;flex-wrap:wrap;">
        <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Cari nama mahasiswa, kelas, atau ID transaksi..." style="flex:1;min-width:200px;">
        <select class="form-control" name="status" style="width:auto;min-width:140px;">
            <option value="">Semua Status</option>
            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Berhasil</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Gagal</option>
            <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Kedaluwarsa</option>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
        @if(request('search') || request('status'))
            <a href="{{ route('admin.transaksi') }}" class="btn btn-outline">Reset</a>
        @endif
    </form>
</div>

{{-- Transaction Table --}}
<div class="table-wrapper">
    <table class="table" id="table-transaksi-admin">
        <thead>
            <tr>
                <th>#</th>
                <th>Mahasiswa</th>
                <th>Kelas</th>
                <th>Pengajar</th>
                <th>Harga</th>
                <th>Status</th>
                <th>Metode</th>
                <th>ID Transaksi</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $i => $trx)
            <tr>
                <td>{{ $transactions->firstItem() + $i }}</td>
                <td>
                    <div class="d-flex align-center gap-sm">
                        <img src="{{ $trx->mahasiswa->foto_profile ? asset($trx->mahasiswa->foto_profile) : 'https://ui-avatars.com/api/?name=' . urlencode($trx->mahasiswa->nama) . '&size=32&background=cce5ff&color=004b73' }}" alt="" class="avatar" style="width:28px;height:28px;">
                        {{ $trx->mahasiswa->nama ?? '-' }}
                    </div>
                </td>
                <td><strong>{{ $trx->kelas->nama_kelas ?? 'Kelas Dihapus' }}</strong></td>
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
                <td style="font-family:monospace;font-size:11px;">{{ $trx->payment_id ?? '-' }}</td>
                <td style="white-space:nowrap;">
                    @if($trx->paid_at)
                        {{ $trx->paid_at->format('d M Y H:i') }}
                    @elseif($trx->transaction_time)
                        {{ $trx->transaction_time->format('d M Y H:i') }}
                    @else
                        {{ $trx->created_at->format('d M Y H:i') }}
                    @endif
                </td>
                <td>
                    @if($trx->payment_status !== 'paid')
                        <div class="d-flex gap-xs flex-wrap">
                            <form method="POST" action="{{ route('admin.transaksi.approve', $trx->id) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm" style="background:var(--success);color:#fff;" title="Setujui Pembayaran">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                    Approve
                                </button>
                            </form>
                            @if($trx->payment_status !== 'failed')
                            <form method="POST" action="{{ route('admin.transaksi.reject', $trx->id) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm" title="Tolak Pembayaran">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    Reject
                                </button>
                            </form>
                            @endif
                        </div>
                    @else
                        <span class="text-muted" style="font-size:12px;">✅ Lunas</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center text-muted" style="padding:var(--space-xl);">Belum ada transaksi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:var(--space-lg);">
    {{ $transactions->withQueryString()->links() }}
</div>
@endsection
