@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-text-main">Calendario de Actividades</h2>
            <p class="text-text-muted text-sm">Tareas, reuniones y seguimientos en un solo lugar</p>
        </div>
        <div class="hidden sm:flex items-center space-x-4 text-sm">
            <div class="flex items-center">
                <span class="inline-block w-3 h-3 rounded-full mr-2" style="background:#FF8F12"></span>
                <span class="text-text-muted">Eventos/Tareas</span>
            </div>
            <div class="flex items-center">
                <span class="inline-block w-3 h-3 rounded-full mr-2 bg-green-500"></span>
                <span class="text-text-muted">Completados</span>
            </div>
        </div>
    </div>

    <div class="bg-white border border-primary/20 rounded-xl shadow-sm overflow-hidden">
        <div class="p-4">
            <div id="calendar"></div>
        </div>
    </div>
</div>

<style>
/* Estilos FullCalendar adaptados a la marca */
.fc .fc-toolbar-title{color:#FF8F12;font-weight:700}
.fc .fc-button-primary{background-color:#FF8F12;border-color:#FF8F12}
.fc .fc-button-primary:not(:disabled):hover{background-color:#D97706;border-color:#D97706}
.fc .fc-button-primary:disabled{background-color:#FFAC4E;border-color:#FFAC4E;opacity:.6}
.fc .fc-daygrid-event{background-color:#FF8E28;border-color:#FF8E28}
.fc .fc-daygrid-event:hover{background-color:#FFAC4E;border-color:#FFAC4E}
.fc .fc-day-today{background:#FFF7ED}
.fc .fc-col-header-cell-cushion{color:#6B7280}
.fc .fc-daygrid-day-number{color:#374151}
.fc .fc-button{border-radius:8px}
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new Calendar(calendarEl, {
            plugins: [ dayGridPlugin ],
            initialView: 'dayGridMonth',
            locale: 'es',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,dayGridWeek'
            },
            events: '{{ route("calendar.events") }}',
            eventColor: '#FF8F12',
            eventDidMount: function(info) {
                if (info.event.title) {
                    info.el.setAttribute('title', info.event.title);
                }
            }
        });
        calendar.render();
    });
    </script>
@endpush
@endsection