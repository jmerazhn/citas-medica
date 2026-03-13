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
            'name' => 'Editar Cita',
        ],
    ]">

    <form action="{{ route('admin.appointments.update', $appointment) }}" method="POST">
        @csrf
        @method('PUT')

        <x-wire-card class="mb-4">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Editar Cita #{{ $appointment->id }}</h2>
                <div class="flex space-x-3">
                    <x-wire-button outline gray href="{{ route('admin.appointments.show', $appointment) }}">
                        Volver
                    </x-wire-button>
                    <x-wire-button type="submit" primary>
                        <i class="fa-solid fa-check"></i>
                        Guardar Cambios
                    </x-wire-button>
                </div>
            </div>
        </x-wire-card>

        <x-wire-card>
            <div class="space-y-6">
                {{-- Paciente --}}
                <div>
                    <x-wire-select
                        name="patient_id"
                        label="Paciente"
                        :options="$patients"
                        option-value="id"
                        option-label="full_name"
                        placeholder="Seleccione un paciente"
                        :value="old('patient_id', $appointment->patient_id)"
                        searchable
                    />
                    @error('patient_id')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Doctor, Fecha y Slots (Livewire) --}}
                @livewire('admin.appointment-form', [
                    'doctors'          => $doctors,
                    'initialDoctorId'  => old('doctor_id', $appointment->doctor_id),
                    'initialDate'      => old('date', $appointment->scheduled_at->format('Y-m-d')),
                ])

                {{-- Hora manual (fallback si Livewire no seleccionó) --}}
                <input type="hidden" name="time" value="{{ old('time', $appointment->scheduled_at->format('H:i')) }}">

                {{-- Motivo --}}
                <div>
                    <x-wire-input
                        label="Motivo de consulta"
                        name="reason"
                        type="text"
                        value="{{ old('reason', $appointment->reason) }}"
                        class="w-full" />
                </div>

                {{-- Notas --}}
                <div>
                    <x-wire-textarea
                        label="Notas internas"
                        name="notes">{{ old('notes', $appointment->notes) }}</x-wire-textarea>
                </div>

                {{-- Estado --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select name="status"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="pending"   @selected(old('status', $appointment->status) === 'pending')>Pendiente</option>
                        <option value="confirmed" @selected(old('status', $appointment->status) === 'confirmed')>Confirmada</option>
                        <option value="completed" @selected(old('status', $appointment->status) === 'completed')>Completada</option>
                        <option value="cancelled" @selected(old('status', $appointment->status) === 'cancelled')>Cancelada</option>
                    </select>
                </div>
            </div>
        </x-wire-card>
    </form>
</x-admin-layout>
