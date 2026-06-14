{{-- SobatMedis Logo Component — Pure SVG, no image file needed --}}
@php $logoSize = $size ?? 28; @endphp
<svg width="{{ $logoSize }}" height="{{ $logoSize }}" viewBox="0 0 32 32" fill="none" style="flex-shrink:0;">
    <circle cx="16" cy="16" r="16" fill="#006a61"/>
    <path d="M22 12h-4l-3 9L9 3l-3 9H2" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" transform="translate(5, 4) scale(0.7)"/>
    <path d="M16 8v16M10 14h12M10 20h12" stroke="white" stroke-width="1.8" stroke-linecap="round" opacity="0.3"/>
    <path d="M22 12h-4l-3 9L9 3l-3 9H2" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" transform="translate(3, 5) scale(0.8)"/>
</svg>
