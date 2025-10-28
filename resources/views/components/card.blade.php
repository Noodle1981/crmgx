{{-- resources/views/components/card.blade.php --}}
@props([
    'title' => null,
])

<div {{ $attributes->merge(['class' => 'bg-primary border border-primary-dark rounded-2xl shadow-lg text-white']) }}>
    @if ($title || isset($header))
        <div class="px-6 py-4 border-b border-primary-light/50">
            @if ($title)
                <h3 class="font-headings text-xl">{{ $title }}</h3>
            @else
                {{ $header }}
            @endif
        </div>
    @endif

    <div class="p-6">
        {{ $slot }}
    </div>

    @if (isset($footer))
        <div class="px-6 py-4 bg-primary-dark/50 border-t border-primary-light/50 rounded-b-2xl">
            {{ $footer }}
        </div>
    @endif
</div>