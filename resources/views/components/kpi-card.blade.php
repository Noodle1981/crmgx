@props(['title', 'value', 'icon'])
<div {{ $attributes->merge(['class' => 'bg-gray-900/60 backdrop-blur-xl border border-white/10 rounded-2xl shadow-xl p-6 flex items-center space-x-6 transform hover:-translate-y-2 transition-transform duration-300']) }}>
    <div class="text-4xl text-aurora-cyan"><i class="{{ $icon }}"></i></div>
    <div>
        <h3 class="font-headings text-lg text-light-text-muted">{{ $title }}</h3>
        <p class="text-4xl font-bold text-light-text mt-1">{{ $value }}</p>
    </div>
</div>