<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Inscripciones Activas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Notifications Section --}}
            @if ($notifications->count())
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
                    <p class="font-bold">Tienes pasos de secuencia pendientes:</p>
                    <ul>
                        @foreach ($notifications as $notification)
                            <li class="mb-2">
                                {{ $notification->data['message'] }}
                                <div class="inline-block ml-4">
                                    {{-- WhatsApp quick action --}}
                                    @if (!empty($notification->data['contact_phone']))
                                        @php
                                            $phoneDigits = preg_replace('/\D+/', '', $notification->data['contact_phone']);
                                            $waMessage = rawurlencode("Hola {$notification->data['enrollable_name']}, te contactamos desde {$notification->data['sequence_name']} respecto a: {$notification->data['step_subject']}");
                                            $waUrl = "https://wa.me/{$phoneDigits}?text={$waMessage}";
                                        @endphp
                                        <a href="{{ $waUrl }}" target="_blank" class="text-green-600 hover:text-green-900 mr-2" title="Enviar WhatsApp"><i class="fab fa-whatsapp fa-lg"></i></a>
                                    @endif

                                    {{-- Video call quick action --}}
                                    @if (!empty($notification->data['video_link']))
                                        <a href="{{ $notification->data['video_link'] }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 mr-2" title="Abrir videollamada"><i class="fas fa-video fa-lg"></i></a>
                                    @endif

                                    @if ($notification->data['task_id'])
                                        <form action="{{ route('enrollments.completeStep', $notification->data['enrollment_id']) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="task_id" value="{{ $notification->data['task_id'] }}">
                                            <button type="submit" class="text-sm bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 rounded">Marcar como Completado</button>
                                        </form>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Contacto / Lead
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Secuencia
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Próximo Paso
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fecha de Vencimiento
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($enrollments as $enrollment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{-- Asumiendo que tanto Lead como Contact tienen un campo 'name' --}}
                                            {{ $enrollment->enrollable->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $enrollment->sequence->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $enrollment->currentStep->subject ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $enrollment->next_step_due_at ? \Carbon\Carbon::parse($enrollment->next_step_due_at)->format('d/m/Y') : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-3">
                                                {{-- WhatsApp quick action (if phone exists) --}}
                                                @php
                                                    $phone = $enrollment->enrollable->phone ?? null;
                                                @endphp
                                                @if($phone)
                                                    @php
                                                        // Normalize phone: remove non-digits
                                                        $phoneDigits = preg_replace('/\D+/', '', $phone);
                                                        $waMessage = rawurlencode("Hola {$enrollment->enrollable->name}, te contactamos desde {$enrollment->sequence->name} respecto a: {$enrollment->currentStep->subject}");
                                                        $waUrl = "https://wa.me/{$phoneDigits}?text={$waMessage}";
                                                    @endphp
                                                    <a href="{{ $waUrl }}" target="_blank" class="text-green-600 hover:text-green-900" title="Enviar WhatsApp"><i class="fab fa-whatsapp fa-lg"></i></a>
                                                @endif

                                                {{-- Video call quick action --}}
                                                @php
                                                    $latestVideoCallTask = $enrollment->latestVideoCallTask;
                                                    $videoLink = $latestVideoCallTask ? $latestVideoCallTask->video_link : null;
                                                @endphp
                                                @if($videoLink)
                                                    <a href="{{ $videoLink }}" target="_blank" class="text-indigo-600 hover:text-indigo-900" title="Abrir videollamada"><i class="fas fa-video fa-lg"></i></a>
                                                @endif

                                                <form action="{{ route('enrollments.destroy', $enrollment) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta inscripción?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            No hay inscripciones activas en este momento.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
