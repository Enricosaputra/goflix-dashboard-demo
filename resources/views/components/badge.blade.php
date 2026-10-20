@props(['tone' => 'slate'])

@php
    $tones = ['slate' => 'bg-ink-100 text-ink-600', 'green' => 'bg-emerald-100 text-emerald-700', 'amber' => 'bg-amber-100 text-amber-700'];
@endphp

<span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $tones[$tone] }}">{{ $slot }}</span>
