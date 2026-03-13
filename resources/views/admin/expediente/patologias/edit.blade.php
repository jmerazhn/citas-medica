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
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">Editar Patología — {{ $patient->full_name }}</h2>
                <div class="flex space-x-3">
                    <x-wire-button outline gray href="{{ route('admin.patients.show', $patient) }}">Volver</x-wire-button>
                    <x-wire-button type="submit" primary><i class="fa-solid fa-check"></i> Actualizar</x-wire-button>
                </div>
            </div>
        </x-wire-card>

        <x-wire-card>
            <div class="grid lg:grid-cols-2 gap-4">

                <x-wire-native-select label="Patología" name="patologia_id" required>
                    <option value="">Seleccionar...</option>
                    @foreach ($patologias as $patologia)
                        <option value="{{ $patologia->id }}" @selected(old('patologia_id', $patientPatologia->patologia_id) == $patologia->id)>{{ $patologia->nombre }}</option>
                    @endforeach
                </x-wire-native-select>

                <x-wire-input label="Fecha de Diagnóstico" name="fecha_diagnostico" type="date" value="{{ old('fecha_diagnostico', $patientPatologia->fecha_diagnostico?->format('Y-m-d')) }}" />

                <x-wire-native-select label="Estado" name="estado" required>
                    <option value="activa" @selected(old('estado', $patientPatologia->estado) === 'activa')>Activa</option>
                    <option value="resuelta" @selected(old('estado', $patientPatologia->estado) === 'resuelta')>Resuelta</option>
                </x-wire-native-select>

                <div class="lg:col-span-2">
                    <x-wire-textarea label="Notas" name="notas">{{ old('notas', $patientPatologia->notas) }}</x-wire-textarea>
                </div>

            </div>
        </x-wire-card>

    </form>
</x-admin-layout>
