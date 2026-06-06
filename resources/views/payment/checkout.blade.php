@extends('layouts.dashboard')
@section('title', 'Checkout')

@section('content')
<a href="{{ url('/mahasiswa/beli-kelas') }}" class="body-sm text-muted d-inline-flex align-center gap-xs mb-lg" style="text-decoration:none;">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
    Kembali
</a>

<div class="d-flex gap-xl flex-wrap" style="max-width:900px;">
    {{-- Class Summary --}}
    <div style="flex:1;min-width:300px;">
        <div class="card" style="cursor:default;">
            @if($kelas->thumbnail)
                <img src="{{ asset('storage/' . $kelas->thumbnail) }}" alt="{{ $kelas->nama_kelas }}" class="card-img-top">
            @else
                <div style="width:100%;aspect-ratio:16/9;background:linear-gradient(135deg,var(--primary-fixed),var(--secondary-fixed));display:flex;align-items:center;justify-content:center;">
                    <svg width="64" height="64" fill="none" stroke="var(--on-primary-fixed-variant)" stroke-width="1" viewBox="0 0 24 24" opacity="0.4"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
                </div>
            @endif
            <div class="card-body">
                <h2 class="headline-md mb-sm">{{ $kelas->nama_kelas }}</h2>
                <p class="body-sm text-muted mb-md">{{ $kelas->deskripsi }}</p>
                <div class="d-flex align-center gap-sm">
                    <img src="{{ $kelas->pengajar->foto_profile ? asset('storage/'.$kelas->pengajar->foto_profile) : 'https://ui-avatars.com/api/?name='.urlencode($kelas->pengajar->nama).'&size=32&background=cce5ff&color=004b73' }}" alt="" class="avatar" style="width:32px;height:32px;">
                    <span class="body-sm">{{ $kelas->pengajar->nama }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Payment Card --}}
    <div style="flex:1;min-width:300px;">
        <div class="card" style="cursor:default;">
            <div class="card-header">
                <h3 class="headline-sm">Ringkasan Pembayaran</h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-between mb-md">
                    <span class="body-md text-muted">Kelas</span>
                    <span class="body-md fw-600">{{ $kelas->nama_kelas }}</span>
                </div>
                <div class="d-flex justify-between mb-md">
                    <span class="body-md text-muted">Harga</span>
                    <span class="body-md fw-600">{{ $kelas->formatted_harga }}</span>
                </div>
                <hr style="border:none;border-top:1px solid var(--outline-variant);margin:var(--space-md) 0;">
                <div class="d-flex justify-between mb-lg">
                    <span class="body-lg fw-700">Total</span>
                    <span class="body-lg fw-700 text-primary">{{ $kelas->formatted_harga }}</span>
                </div>

                <div class="alert alert-info mb-lg" id="payment-info">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    Pembayaran diproses melalui <strong>Midtrans</strong> — mendukung berbagai metode pembayaran (Transfer Bank, GoPay, QRIS, dll).
                </div>

                {{-- Error message container --}}
                <div id="payment-error" class="alert alert-error mb-lg" style="display:none;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    <span id="payment-error-text"></span>
                </div>

                <button type="button" class="btn btn-primary btn-block btn-lg" id="btn-pay" onclick="initiatePayment()">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                    <span id="btn-pay-text">Bayar Sekarang</span>
                </button>
            </div>
        </div>

        {{-- Payment methods info --}}
        <div class="card mt-md" style="cursor:default;background:var(--surface-container-low);">
            <div class="card-body">
                <div class="body-sm text-muted">
                    <strong>Metode Pembayaran Tersedia:</strong>
                    <div class="d-flex flex-wrap gap-sm mt-sm">
                        <span class="badge" style="background:var(--surface-container-high);color:var(--on-surface);">Bank Transfer</span>
                        <span class="badge" style="background:var(--surface-container-high);color:var(--on-surface);">GoPay</span>
                        <span class="badge" style="background:var(--surface-container-high);color:var(--on-surface);">QRIS</span>
                        <span class="badge" style="background:var(--surface-container-high);color:var(--on-surface);">ShopeePay</span>
                        <span class="badge" style="background:var(--surface-container-high);color:var(--on-surface);">Credit Card</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Midtrans Snap.js --}}
<script src="{{ $midtransSnapUrl }}" data-client-key="{{ $midtransClientKey }}"></script>

<script>
    let isProcessing = false;

    function initiatePayment() {
        if (isProcessing) return;
        isProcessing = true;

        const btn = document.getElementById('btn-pay');
        const btnText = document.getElementById('btn-pay-text');
        const errorDiv = document.getElementById('payment-error');
        const errorText = document.getElementById('payment-error-text');

        // Loading state
        btn.disabled = true;
        btnText.textContent = 'Memproses...';
        errorDiv.style.display = 'none';

        // Request snap token from server
        fetch('{{ url("/mahasiswa/beli-kelas/" . $kelas->id . "/create-transaction") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                showError(data.error);
                return;
            }

            if (data.snap_token) {
                // Open Midtrans Snap popup
                window.snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        window.location.href = '{{ url("/mahasiswa/beli-kelas/" . $kelas->id . "/finish") }}?status=success';
                    },
                    onPending: function(result) {
                        window.location.href = '{{ url("/mahasiswa/beli-kelas/" . $kelas->id . "/finish") }}?status=pending';
                    },
                    onError: function(result) {
                        showError('Pembayaran gagal. Silakan coba lagi.');
                    },
                    onClose: function() {
                        // User closed the popup without completing payment
                        resetButton();
                    }
                });
            } else {
                showError('Terjadi kesalahan. Silakan coba lagi.');
            }
        })
        .catch(error => {
            console.error('Payment error:', error);
            showError('Terjadi kesalahan koneksi. Silakan coba lagi.');
        });
    }

    function showError(message) {
        const errorDiv = document.getElementById('payment-error');
        const errorText = document.getElementById('payment-error-text');
        errorDiv.style.display = 'flex';
        errorText.textContent = message;
        resetButton();
    }

    function resetButton() {
        const btn = document.getElementById('btn-pay');
        const btnText = document.getElementById('btn-pay-text');
        btn.disabled = false;
        btnText.textContent = 'Bayar Sekarang';
        isProcessing = false;
    }
</script>
@endsection
