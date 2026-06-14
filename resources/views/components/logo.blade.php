{{-- SobatMedis Logo Component --}}
{{-- Usage: @include('components.logo', ['size' => 32]) --}}
@php $logoSize = $size ?? 28; @endphp
<img
    src="{{ asset('images/logo.png') }}"
    alt="SobatMedis"
    class="brand-logo"
    style="width:{{ $logoSize }}px;height:{{ $logoSize }}px;border-radius:50%;object-fit:cover;clip-path:circle(50%);display:block;flex-shrink:0;"
>
