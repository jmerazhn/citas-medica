<x-admin-layout
    title="Citas"
    :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href' => route('admin.dashboard'),
        ],
        [
            'name' => 'Citas',
            'href' => route('admin.appointments.index'),
        ],
        [
            'name' => 'Detalle de Cita',
        ],
    ]">

    <x-wire-card class="mb-4">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <div class="flex items-center gap-3">
                <h2 class="text-lg font-semibold text-gray-800">Cita #{{ $appointment->id }}</h2>
                @include('admin.appointments.status-badge', ['status' => $appointment->status])
            </div>
            <div class="flex flex-wrap gap-2">
                <x-wire-button outline gray href="{{ route('admin.appointments.index') }}">
                    Volver
                </x-wire-button>
                <x-wire-button outline blue href="{{ route('admin.appointments.edit', $appointment) }}">
                    <i class="fa fa-pen-to-square"></i> Editar
                </x-wire-button>

                @if ($appointment->status === 'pending')
                    <form method="POST" action="{{ route('admin.appointments.confirm', $appointment) }}" class="inline">
                        @csrf @method('PATCH')
                        <x-wire-button type="submit" class="bg-blue-600 hover:bg-blue-700 focus:ring-blue-500">
                            <i class="fa fa-check-circle"></i> Confirmar
                        </x-wire-button>
                    </form>
                @endif

                @if (in_array($appointment->status, ['pending', 'confirmed']))
                    <form method="POST" action="{{ route('admin.appointments.complete', $appointment) }}" class="inline">
                        @csrf @method('PATCH')
                        <x-wire-button type="submit" class="bg-green-600 hover:bg-green-700 focus:ring-green-500">
                            <i class="fa fa-flag-checkered"></i> Completar
                        </x-wire-button>
                    </form>
                @endif

                @if (!in_array($appointment->status, ['cancelled', 'completed']))
                    <button type="button"
                        x-data
                        x-on:click="$dispatch('open-modal', 'cancel-appointment')"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition">
                        <i class="fa fa-ban"></i> Cancelar
                    </button>
                @endif
            </div>
        </div>
    </x-wire-card>

    <div class="grid lg:grid-cols-2 gap-4">
        <x-wire-card>
            <h3 class="font-semibold text-gray-700 mb-4">Información de la Cita</h3>
            <dl class="space-y-3 text-sm">
                <div class="flex gap-2">
                    <dt class="font-medium text-gray-500 w-36">Paciente:</dt>
                    <dd class="text-gray-900">{{ $appointment->patient?->full_name }}</dd>
                </div>
                <div class="flex gap-2">
                    <dt class="font-medium text-gray-500 w-36">Doctor:</dt>
                    <dd class="text-gray-900">{{ $appointment->doctor->name }}</dd>
                </div>
                <div class="flex gap-2">
                    <dt class="font-medium text-gray-500 w-36">Fecha:</dt>
                    <dd class="text-gray-900">{{ $appointment->scheduled_at->format('d/m/Y') }}</dd>
                </div>
                <div class="flex gap-2">
                    <dt class="font-medium text-gray-500 w-36">Hora inicio:</dt>
                    <dd class="text-gray-900">{{ $appointment->scheduled_at->format('H:i') }}</dd>
                </div>
                <div class="flex gap-2">
                    <dt class="font-medium text-gray-500 w-36">Hora fin:</dt>
                    <dd class="text-gray-900">{{ $appointment->end_time->format('H:i') }}</dd>
                </div>
                <div class="flex gap-2">
                    <dt class="font-medium text-gray-500 w-36">Duración:</dt>
                    <dd class="text-gray-900">{{ $appointment->duration }} minutos</dd>
                </div>
                <div class="flex gap-2">
                    <dt class="font-medium text-gray-500 w-36">Motivo:</dt>
                    <dd class="text-gray-900">{{ $appointment->reason }}</dd>
                </div>
            </dl>
        </x-wire-card>

        <x-wire-card>
            <h3 class="font-semibold text-gray-700 mb-4">Notas y Cancelación</h3>
            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="font-medium text-gray-500 mb-1">Notas internas:</dt>
                    <dd class="text-gray-900">{{ $appointment->notes ?? '—' }}</dd>
                </div>
                @if ($appointment->status === 'cancelled')
                    <div>
                        <dt class="font-medium text-gray-500 mb-1">Razón de cancelación:</dt>
                        <dd class="text-red-700">{{ $appointment->cancelled_reason ?? '—' }}</dd>
                    </div>
                    <div class="flex gap-2">
                        <dt class="font-medium text-gray-500 w-36">Cancelada el:</dt>
                        <dd class="text-gray-900">{{ $appointment->cancelled_at?->format('d/m/Y H:i') ?? '—' }}</dd>
                    </div>
                @endif
                <div class="flex gap-2">
                    <dt class="font-medium text-gray-500 w-36">Creada el:</dt>
                    <dd class="text-gray-900">{{ $appointment->created_at->format('d/m/Y H:i') }}</dd>
                </div>
            </dl>
        </x-wire-card>
    </div>

    {{-- Modal de cancelación --}}
    <x-wire-modal name="cancel-appointment" max-width="md">
        <x-wire-card>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Cancelar Cita</h3>
                <button x-on:click="$dispatch('close')" class="text-gray-400 hover:text-gray-600">
                    <i class="fa fa-times"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.appointments.cancel', $appointment) }}">
                @csrf @method('PATCH')
                <div class="mb-4">
                    <x-wire-textarea
                        label="Razón de cancelación"
                        name="cancelled_reason"
                        placeholder="Indique el motivo de la cancelación...">{{ old('cancelled_reason') }}</x-wire-textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <x-wire-button outline gray x-on:click="$dispatch('close')">
                        Cerrar
                    </x-wire-button>
                    <x-wire-button type="submit" class="bg-red-600 hover:bg-red-700 focus:ring-red-500">
                        Confirmar cancelación
                    </x-wire-button>
                </div>
            </form>
        </x-wire-card>
    </x-wire-modal>
</x-admin-layout>
