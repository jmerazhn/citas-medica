<x-admin-layout title="Registrar Patología" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Pacientes', 'href' => route('admin.patients.index')],
    ['name' => $patient->full_name, 'href' => route('admin.patients.show', $patient)],
    ['name' => 'Registrar Patología'],
]">
    <form action="{{ route('admin.patients.patologias.store', $patient) }}" method="POST">
        @csrf

        <x-wire-card class="mb-6">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">Registrar Patología — {{ $patient->full_name }}</h2>
                <div class="flex space-x-3">
                    <x-wire-button outline gray href="{{ route('admin.patients.show', $patient) }}">Volver</x-wire-button>
                    <x-wire-button type="submit" primary><i class="fa-solid fa-check"></i> Guardar</x-wire-button>
                </div>
            </div>
        </x-wire-card>

        <x-wire-card>
            <div class="grid lg:grid-cols-2 gap-4">

                <x-searchable-select
                    name="patologia_id"
                    label="Patología"
                    :options="$patologias"
                    option-value="id"
                    option-label="nombre"
                    placeholder="Seleccionar..."
                    :selected="old('patologia_id')"
                />

                <x-wire-input label="Fecha de Diagnóstico" name="fecha_diagnostico" type="date" value="{{ old('fecha_diagnostico') }}" />

                <x-wire-native-select label="Estado" name="estado" required>
                    <option value="activa" @selected(old('estado', 'activa') === 'activa')>Activa</option>
                    <option value="resuelta" @selected(old('estado') === 'resuelta')>Resuelta</option>
                </x-wire-native-select>

                <div class="lg:col-span-2">
                    <x-wire-textarea label="Notas" name="notas">{{ old('notas') }}</x-wire-textarea>
                </div>

            </div>
        </x-wire-card>

    </form>
</x-admin-layout>
