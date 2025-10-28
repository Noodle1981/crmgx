{{-- resources/views/components/text-input.blade.php --}}
@props(['disabled' => false])

@php
$classes = ($disabled ?? false)
    ? 'w-full bg-gray-800/ border border-white/5 rounded-lg text-light-text-muted/50 cursor-not-allowed'
    : 'w-full bg-gray-900/60 border border-white/10 rounded-lg text-light-text placeholder:text-light-text-muted/50 
       transition-all duration-300
       focus:border-aurora-cyan focus:ring-2 focus:ring-aurora-cyan/40 focus:outline-none';
@endphp

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => $classes]) !!}>