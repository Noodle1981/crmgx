{{-- resources/views/components/primary-button.blade.php --}}
<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'inline-flex items-center justify-center px-6 py-3
                border border-transparent rounded-full
                font-bold text-sm text-dark-void uppercase tracking-widest
                bg-gradient-to-r from-aurora-blue to-aurora-cyan
                transition-all duration-300 ease-in-out
                transform hover:scale-105 hover:shadow-lg hover:shadow-aurora-cyan/40
                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-aurora-cyan focus:ring-offset-dark-void
                active:scale-100'
]) }}>
    {{ $slot }}
</button>