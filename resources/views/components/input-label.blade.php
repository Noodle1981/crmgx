{{-- resources/views/components/input-label.blade.php --}}
@props(['value'])

<label {{ $attributes->merge([
    'class' => 'block mb-2 font-medium text-sm text-light-text-muted'
]) }}>
    {{ $value ?? $slot }}
</label>