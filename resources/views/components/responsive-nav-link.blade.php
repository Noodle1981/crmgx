{{-- resources/views/components/responsive-nav-link.blade.php --}}
@props(['active'])

@php
$baseClasses = 'block w-full ps-3 pe-4 py-3 border-l-4 text-start text-base font-medium
                transition-all duration-300 ease-in-out focus:outline-none';

$activeClasses = 'border-aurora-cyan bg-aurora-cyan/10 text-light-text';

$inactiveClasses = 'border-transparent text-light-text-muted hover:text-light-text
                    hover:bg-gray-800/50 hover:border-aurora-cyan/50';

$classes = $baseClasses . ' ' . ($active ?? false ? $activeClasses : $inactiveClasses);
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>