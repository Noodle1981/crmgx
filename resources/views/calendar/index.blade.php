<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Calendario de Actividades
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div id='calendar'></div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new Calendar(calendarEl, {
                plugins: [ dayGridPlugin ],
                initialView: 'dayGridMonth',
                locale: 'es', // ¡Calendario en español!
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,dayGridWeek'
                },
                events: '{{ route("calendar.events") }}', // La ruta que creamos para los datos
                eventColor: '#378006',
                eventDidMount: function(info) {
                    // Añadir tooltips con la descripción completa
                    if (info.event.title) {
                        info.el.setAttribute('title', info.event.title);
                    }
                }
            });
            calendar.render();
        });
    </script>
    @endpush
</x-app-layout>