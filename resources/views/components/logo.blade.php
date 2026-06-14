{{-- SobatMedis Logo Component --}}
@php $logoSize = $size ?? 40; @endphp
<img
    src="{{ asset('images/logo.png') }}"
    alt="SobatMedis"
    class="brand-logo"
    style="width:{{ $logoSize }}px;height:{{ $logoSize }}px;border-radius:50%;object-fit:cover;display:block;flex-shrink:0;margin:0 auto;"
>
