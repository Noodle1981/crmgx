{{-- resources/views/components/card.blade.php --}}
@props([
    'title' => null,
])

<div {{ $attributes->merge(['class' => 'bg-gray-900/60 backdrop-blur-xl border border-white/10 rounded-2xl shadow-xl']) }}>
    @if ($title || isset($header))
        <div class="px-6 py-4 border-b border-white/10">
            @if ($title)
                <h3 class="font-headings text-xl text-light-text">{{ $title }}</h3>
            @else
                {{ $header }}
            @endif
        </div>
    @endif

    <div class="p-6">
        {{ $slot }}
    </div>

    @if (isset($footer))
        <div class="px-6 py-4 bg-black/20 border-t border-white/10 rounded-b-2xl">
            {{ $footer }}
        </div>
    @endif
</div>