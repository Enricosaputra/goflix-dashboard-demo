@props(['href' => null, 'variant' => 'primary', 'type' => 'submit'])

@php
    $class = 'inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition-colors '.
        ($variant === 'primary' ? 'bg-slate-900 text-white hover:bg-slate-700' : 'border border-slate-300 bg-white text-slate-700 hover:bg-slate-50');
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $class]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $class]) }}>{{ $slot }}</button>
@endif
