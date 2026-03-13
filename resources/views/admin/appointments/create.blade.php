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
            'name' => 'Nueva Cita',
        ],
    ]">

    <form action="{{ route('admin.appointments.store') }}" method="POST">
        @csrf

        <x-wire-card class="mb-4">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Nueva Cita</h2>
                <div class="flex space-x-3">
                    <x-wire-button outline gray href="{{ route('admin.appointments.index') }}">
                        Volver
                    </x-wire-button>
                    <x-wire-button type="submit" primary>
                        <i class="fa-solid fa-check"></i>
                        Guardar Cita
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
                        :value="old('patient_id')"
                        searchable
                    />
                    @error('patient_id')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Doctor, Fecha y Slots (Livewire) --}}
                @livewire('admin.appointment-form', ['doctors' => $doctors])

                {{-- Motivo --}}
                <div>
                    <x-wire-input
                        label="Motivo de consulta"
                        name="reason"
                        type="text"
                        value="{{ old('reason') }}"
                        placeholder="Describa el motivo de la consulta"
                        class="w-full" />
                </div>

                {{-- Notas --}}
                <div>
                    <x-wire-textarea
                        label="Notas internas (opcional)"
                        name="notes">{{ old('notes') }}</x-wire-textarea>
                </div>
            </div>
        </x-wire-card>
    </form>
</x-admin-layout>
