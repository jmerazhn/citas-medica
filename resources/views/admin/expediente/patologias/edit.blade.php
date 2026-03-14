<x-admin-layout title="Editar Patología" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Pacientes', 'href' => route('admin.patients.index')],
    ['name' => $patient->full_name, 'href' => route('admin.patients.show', $patient)],
    ['name' => 'Editar Patología'],
]">
    <form action="{{ route('admin.patologias.update', $patientPatologia) }}" method="POST">
        @csrf
        @method('PUT')

        <x-wire-card class="mb-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                <h2 class="text-lg sm:text-xl font-bold text-gray-800">Editar Patología — {{ $patient->full_name }}</h2>
                <div class="flex gap-2">
                    <x-wire-button outline gray href="{{ route('admin.patients.show', $patient) }}">Volver</x-wire-button>
                    <x-wire-button type="submit" primary><i class="fa-solid fa-check"></i> Actualizar</x-wire-button>
                </div>
            </div>
        </x-wire-card>

        <x-wire-card>
            <div class="grid md:grid-cols-2 gap-4">

                <x-searchable-select
                    name="patologia_id"
                    label="Patología"
                    :options="$patologias"
                    option-value="id"
                    option-label="nombre"
                    placeholder="Seleccionar..."
                    :selected="old('patologia_id', $patientPatologia->patologia_id)"
                />

                <x-wire-input label="Fecha de Diagnóstico" name="fecha_diagnostico" type="date" value="{{ old('fecha_diagnostico', $patientPatologia->fecha_diagnostico?->format('Y-m-d')) }}" />

                <x-wire-native-select label="Estado" name="estado" required>
                    <option value="activa" @selected(old('estado', $patientPatologia->estado) === 'activa')>Activa</option>
                    <option value="resuelta" @selected(old('estado', $patientPatologia->estado) === 'resuelta')>Resuelta</option>
                </x-wire-native-select>

                <div class="md:col-span-2">
                    <x-wire-textarea label="Notas" name="notas">{{ old('notas', $patientPatologia->notas) }}</x-wire-textarea>
                </div>

            </div>
        </x-wire-card>

    </form>
</x-admin-layout>
