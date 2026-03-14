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

<div x-data="{ cancelModal: false }">
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
                @can('gestionar-citas')
                <x-wire-button outline blue href="{{ route('admin.appointments.edit', $appointment) }}">
                    <i class="fa fa-pen-to-square"></i> Editar
                </x-wire-button>
                @endcan

                @can('completar-citas')
                @if (in_array($appointment->status, ['confirmed', 'completed']))
                    @if ($appointment->atencion)
                        <x-wire-button outline href="{{ route('admin.atenciones.edit', $appointment->atencion) }}"
                            class="border-purple-400 text-purple-700 hover:bg-purple-50">
                            <i class="fa-solid fa-stethoscope"></i> Ver / Editar Atención
                        </x-wire-button>
                    @else
                        <x-wire-button href="{{ route('admin.appointments.atencion.create', $appointment) }}"
                            class="bg-purple-600 hover:bg-purple-700 focus:ring-purple-500">
                            <i class="fa-solid fa-stethoscope"></i> Registrar Atención
                        </x-wire-button>
                    @endif
                @endif
                @endcan

                @can('confirmar-citas')
                @if ($appointment->status === 'pending')
                    <form method="POST" action="{{ route('admin.appointments.confirm', $appointment) }}" class="inline">
                        @csrf @method('PATCH')
                        <x-wire-button type="submit" class="bg-blue-600 hover:bg-blue-700 focus:ring-blue-500">
                            <i class="fa fa-check-circle"></i> Confirmar
                        </x-wire-button>
                    </form>
                @endif
                @endcan

                @can('completar-citas')
                @if (in_array($appointment->status, ['pending', 'confirmed']))
                    <form method="POST" action="{{ route('admin.appointments.complete', $appointment) }}" class="inline">
                        @csrf @method('PATCH')
                        <x-wire-button type="submit" class="bg-green-600 hover:bg-green-700 focus:ring-green-500">
                            <i class="fa fa-flag-checkered"></i> Completar
                        </x-wire-button>
                    </form>
                @endif
                @endcan

                @can('cancelar-citas')
                @if (!in_array($appointment->status, ['cancelled', 'completed']))
                    <button type="button"
                        @click="cancelModal = true"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition">
                        <i class="fa fa-ban"></i> Cancelar
                    </button>
                @endif
                @endcan
            </div>
        </div>
    </x-wire-card>

    <div class="grid md:grid-cols-2 gap-4">
        <x-wire-card>
            <h3 class="font-semibold text-gray-700 mb-4">Información de la Cita</h3>
            <dl class="space-y-3 text-sm">
                <div class="flex gap-2">
                    <dt class="font-medium text-gray-500 w-28 shrink-0">Paciente:</dt>
                    <dd class="text-gray-900">{{ $appointment->patient?->full_name }}</dd>
                </div>
                <div class="flex gap-2">
                    <dt class="font-medium text-gray-500 w-28 shrink-0">Doctor:</dt>
                    <dd class="text-gray-900">{{ $appointment->doctor->name }}</dd>
                </div>
                <div class="flex gap-2">
                    <dt class="font-medium text-gray-500 w-28 shrink-0">Fecha:</dt>
                    <dd class="text-gray-900">{{ $appointment->scheduled_at->format('d/m/Y') }}</dd>
                </div>
                <div class="flex gap-2">
                    <dt class="font-medium text-gray-500 w-28 shrink-0">Hora inicio:</dt>
                    <dd class="text-gray-900">{{ $appointment->scheduled_at->format('H:i') }}</dd>
                </div>
                <div class="flex gap-2">
                    <dt class="font-medium text-gray-500 w-28 shrink-0">Hora fin:</dt>
                    <dd class="text-gray-900">{{ $appointment->end_time->format('H:i') }}</dd>
                </div>
                <div class="flex gap-2">
                    <dt class="font-medium text-gray-500 w-28 shrink-0">Duración:</dt>
                    <dd class="text-gray-900">{{ $appointment->duration }} minutos</dd>
                </div>
                <div class="flex gap-2">
                    <dt class="font-medium text-gray-500 w-28 shrink-0">Motivo:</dt>
                    <dd class="text-gray-900">{{ $appointment->motivoConsulta?->nombre ?? $appointment->reason ?? '—' }}</dd>
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
                        <dt class="font-medium text-gray-500 w-28 shrink-0">Cancelada el:</dt>
                        <dd class="text-gray-900">{{ $appointment->cancelled_at?->format('d/m/Y H:i') ?? '—' }}</dd>
                    </div>
                @endif
                <div class="flex gap-2">
                    <dt class="font-medium text-gray-500 w-28 shrink-0">Creada el:</dt>
                    <dd class="text-gray-900">{{ $appointment->created_at->format('d/m/Y H:i') }}</dd>
                </div>
            </dl>
        </x-wire-card>
    </div>

    {{-- Resumen de Atención --}}
    @if ($appointment->atencion)
        @php $a = $appointment->atencion; @endphp
        <div class="mt-4 space-y-4">

            {{-- Crecimiento y Signos Vitales --}}
            <div class="grid md:grid-cols-2 gap-4">
                <x-wire-card>
                    <h3 class="font-semibold text-gray-700 mb-3">Estado de Crecimiento</h3>
                    <dl class="grid grid-cols-2 gap-x-2 gap-y-2 text-sm">
                        <div><dt class="text-gray-500">Peso</dt><dd class="text-gray-900">{{ $a->peso ?? '—' }}</dd></div>
                        <div><dt class="text-gray-500">Altura / Talla</dt><dd class="text-gray-900">{{ $a->altura ?? '—' }}</dd></div>
                        <div><dt class="text-gray-500">P.C.</dt><dd class="text-gray-900">{{ $a->pc ?? '—' }}</dd></div>
                        <div><dt class="text-gray-500">I.M.C.</dt><dd class="text-gray-900">{{ $a->imc ?? '—' }}</dd></div>
                    </dl>
                </x-wire-card>
                <x-wire-card>
                    <h3 class="font-semibold text-gray-700 mb-3">Signos Vitales</h3>
                    <dl class="grid grid-cols-2 gap-x-2 gap-y-2 text-sm">
                        <div><dt class="text-gray-500">Temperatura</dt><dd class="text-gray-900">{{ $a->temperatura ?? '—' }}</dd></div>
                        <div><dt class="text-gray-500">F.C.</dt><dd class="text-gray-900">{{ $a->fc ?? '—' }}</dd></div>
                        <div><dt class="text-gray-500">F.R.</dt><dd class="text-gray-900">{{ $a->fr ?? '—' }}</dd></div>
                        <div><dt class="text-gray-500">Presión Arterial</dt><dd class="text-gray-900">{{ $a->presion_arterial ?? '—' }}</dd></div>
                    </dl>
                </x-wire-card>
            </div>

            {{-- Gráficas de crecimiento --}}
            <div class="grid sm:grid-cols-2 gap-3">
                @include('admin.partials.growth-chart', ['patient' => $appointment->patient, 'tipo' => 'peso'])
                @include('admin.partials.growth-chart', ['patient' => $appointment->patient, 'tipo' => 'talla'])
                @include('admin.partials.growth-chart', ['patient' => $appointment->patient, 'tipo' => 'perimetro_cefalico'])
                @include('admin.partials.growth-chart', ['patient' => $appointment->patient, 'tipo' => 'imc'])
            </div>

            {{-- Clínico --}}
            <div class="grid md:grid-cols-2 gap-4">
                <x-wire-card>
                    <h3 class="font-semibold text-gray-700 mb-2">Sintomatología</h3>
                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $a->sintomatologia ?? '—' }}</p>
                </x-wire-card>
                <x-wire-card>
                    <h3 class="font-semibold text-gray-700 mb-2">Medicación Indicada</h3>
                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $a->medicacion_indicada ?? '—' }}</p>
                </x-wire-card>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <x-wire-card>
                    <h3 class="font-semibold text-gray-700 mb-2">Diagnóstico Posible</h3>
                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $a->diagnostico_posible ?? '—' }}</p>
                </x-wire-card>
                <x-wire-card>
                    <h3 class="font-semibold text-gray-700 mb-2">Diagnóstico Confirmado</h3>
                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $a->diagnostico_confirmado ?? '—' }}</p>
                </x-wire-card>
            </div>

            @if ($a->estudiosOrdenados->isNotEmpty())
            <x-wire-card>
                <h3 class="font-semibold text-gray-700 mb-3">Estudios Ordenados</h3>
                <div class="space-y-2">
                    @foreach ($a->estudiosOrdenados as $estudio)
                    <div class="border border-gray-200 rounded-lg p-3 bg-gray-50 text-sm">
                        <p class="font-medium text-gray-800">{{ $estudio->estudio }}</p>
                        @if ($estudio->resultado)
                            <p class="text-gray-600 mt-1">{{ $estudio->resultado }}</p>
                        @else
                            <p class="text-gray-400 mt-1 italic">Sin resultado registrado</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </x-wire-card>
            @endif

            @if ($a->notas)
            <x-wire-card>
                <h3 class="font-semibold text-gray-700 mb-2">Notas</h3>
                <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $a->notas }}</p>
            </x-wire-card>
            @endif

        </div>
    @endif

    {{-- Modal de cancelación --}}
    <div
        x-show="cancelModal"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center"
        style="display:none">

        <div class="fixed inset-0 bg-black/50" @click="cancelModal = false"></div>

        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md mx-4 z-10 p-4 sm:p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Cancelar Cita</h3>
                <button @click="cancelModal = false" class="text-gray-400 hover:text-gray-600">
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
                    <x-wire-button outline gray @click="cancelModal = false">
                        Cerrar
                    </x-wire-button>
                    <x-wire-button type="submit" class="bg-red-600 hover:bg-red-700 focus:ring-red-500">
                        Confirmar cancelación
                    </x-wire-button>
                </div>
            </form>
        </div>
    </div>

</div>{{-- /x-data --}}
</x-admin-layout>
