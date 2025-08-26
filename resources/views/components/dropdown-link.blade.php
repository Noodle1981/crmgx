{{-- resources/views/components/dropdown-link.blade.php --}}
<a {{ $attributes->merge([
    'class' => 'block w-full px-4 py-2 text-start text-sm leading-5
                text-light-text-muted hover:text-light-text
                hover:bg-gray-800/50 backdrop-blur-sm
                focus:outline-none focus:bg-gray-800/70
                transition duration-150 ease-in-out'
]) }}>
    {{ $slot }}
</a>