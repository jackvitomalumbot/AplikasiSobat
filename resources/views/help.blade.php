@extends('layouts.app')

@section('title', 'Pusat Bantuan')
@section('meta_description', 'Pusat Bantuan SobatMedis — Hubungi kami via email atau WhatsApp untuk bantuan dan informasi.')

@section('content')
<section class="section">
    <div class="container">
        <div class="section-header">
            <h1 class="headline-lg">Pusat Bantuan</h1>
            <p>Ada kendala atau pertanyaan? Kami siap membantu Anda melalui email atau WhatsApp.</p>
        </div>

        <div class="grid grid-2" style="max-width: 900px; margin: 0 auto;">
            {{-- Chat via Email --}}
            <div class="card" style="cursor: default;" id="card-email-help">
                <div class="card-body">
                    <div class="d-flex align-center gap-sm mb-lg">
                        <div class="stat-icon primary">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </div>
                        <h3 class="headline-sm">Chat via Email</h3>
                    </div>

                    <form method="POST" action="{{ url('/bantuan/email') }}">
                        @csrf
                        <div class="form-group">
                            <label class="form-label" for="help-name">Nama <span class="required">✱</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="help-name" name="name" value="{{ old('name') }}" placeholder="Masukkan nama Anda" required>
                            @error('name')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="help-email">Email <span class="required">✱</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="help-email" name="email" value="{{ old('email') }}" placeholder="email@universitas.ac.id" required>
                            @error('email')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="help-subject">Subjek <span class="required">✱</span></label>
                            <input type="text" class="form-control @error('subject') is-invalid @enderror" id="help-subject" name="subject" value="{{ old('subject') }}" placeholder="Topik bantuan" required>
                            @error('subject')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="help-message">Pesan <span class="required">✱</span></label>
                            <textarea class="form-control @error('message') is-invalid @enderror" id="help-message" name="message" placeholder="Jelaskan kendala atau pertanyaan Anda..." required>{{ old('message') }}</textarea>
                            @error('message')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                            Kirim Email
                        </button>
                    </form>
                </div>
            </div>

            {{-- Chat via WhatsApp --}}
            <div class="card" style="cursor: default;" id="card-whatsapp-help">
                <div class="card-body d-flex flex-column" style="height: 100%; justify-content: space-between;">
                    <div>
                        <div class="d-flex align-center gap-sm mb-lg">
                            <div class="stat-icon" style="background: #dcfce7; color: #166534;">
                                <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            </div>
                            <h3 class="headline-sm">Chat WhatsApp</h3>
                        </div>

                        <p class="body-md text-muted mb-lg">
                            Butuh bantuan cepat? Hubungi tim support kami langsung melalui WhatsApp. Kami tersedia di hari kerja pukul 08:00 - 17:00 WIB.
                        </p>

                        <div class="card" style="background: var(--surface-container-low); border: 1px dashed var(--outline-variant); box-shadow: none; cursor: default; margin-bottom: var(--space-lg);">
                            <div class="card-body" style="padding: var(--space-md);">
                                <p class="body-sm text-muted mb-sm"><strong>Jam Operasional:</strong></p>
                                <p class="body-sm">Senin - Jumat: 08:00 - 17:00 WIB</p>
                                <p class="body-sm">Sabtu: 09:00 - 14:00 WIB</p>
                                <p class="body-sm text-muted mt-sm">Minggu & Hari Libur: Tutup</p>
                            </div>
                        </div>
                    </div>

                    <a href="https://wa.me/628xxxxxxxxxx?text=Halo%20SobatMedis%2C%20saya%20butuh%20bantuan%20mengenai%20..." target="_blank" class="btn btn-block" style="background: #25d366; color: white; font-size: 16px; padding: 16px;">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Chat Sekarang
                    </a>
                </div>
            </div>
        </div>

        {{-- FAQ Section --}}
        <div style="max-width: 700px; margin: var(--space-xl) auto 0;">
            <h2 class="headline-md text-center mb-lg">Pertanyaan Umum (FAQ)</h2>

            <div class="card mb-md" style="cursor: default;" id="faq-1">
                <div class="card-body">
                    <h4 class="headline-sm mb-sm" style="font-size: 16px;">Bagaimana cara mendaftar?</h4>
                    <p class="body-sm text-muted">Klik tombol "Login" di navigasi atas, lalu pilih "Daftar Sekarang". Isi form registrasi dengan data diri Anda, termasuk NIM dan email universitas. Setelah submit, cek email Anda untuk verifikasi akun.</p>
                </div>
            </div>

            <div class="card mb-md" style="cursor: default;" id="faq-2">
                <div class="card-body">
                    <h4 class="headline-sm mb-sm" style="font-size: 16px;">Bagaimana cara membeli kelas?</h4>
                    <p class="body-sm text-muted">Setelah login, buka menu "Beli Kelas" di sidebar. Browse kelas yang tersedia, klik "Beli Sekarang", lalu selesaikan pembayaran melalui payment gateway.</p>
                </div>
            </div>

            <div class="card mb-md" style="cursor: default;" id="faq-3">
                <div class="card-body">
                    <h4 class="headline-sm mb-sm" style="font-size: 16px;">Bagaimana jika lupa password?</h4>
                    <p class="body-sm text-muted">Di halaman login, klik "Lupa Password". Masukkan email terdaftar Anda, dan kami akan mengirimkan link reset password ke email tersebut.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
