{{-- resources/views/components/nav-link.blade.php --}}
@props(['active'])

@php
$baseClasses = 'inline-flex items-center px-3 pt-1 border-b-2 text-sm font-semibold leading-5
                transition-all duration-300 ease-in-out focus:outline-none';

$activeClasses = 'border-aurora-cyan text-light-text shadow-[0_2px_10px_-3px] shadow-aurora-cyan/50';

$inactiveClasses = 'border-transparent text-light-text-muted hover:border-aurora-cyan/50 hover:text-light-text
                    focus:border-aurora-cyan/50 focus:text-light-text';

$classes = $baseClasses . ' ' . ($active ?? false ? $activeClasses : $inactiveClasses);
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>