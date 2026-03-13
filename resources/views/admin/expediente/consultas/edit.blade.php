<x-admin-layout title="Editar Consulta" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Pacientes', 'href' => route('admin.patients.index')],
    ['name' => $patient->full_name, 'href' => route('admin.patients.show', $patient)],
    ['name' => 'Editar Consulta'],
]">
    <form action="{{ route('admin.consultas.update', $consulta) }}" method="POST">
        @csrf
        @method('PUT')

        <x-wire-card class="mb-6">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">Editar Consulta — {{ $patient->full_name }}</h2>
                <div class="flex space-x-3">
                    <x-wire-button outline gray href="{{ route('admin.patients.show', $patient) }}">Volver</x-wire-button>
                    <x-wire-button type="submit" primary><i class="fa-solid fa-check"></i> Actualizar</x-wire-button>
                </div>
            </div>
        </x-wire-card>

        <x-wire-card>
            <div class="grid lg:grid-cols-2 gap-4">

                <x-wire-input label="Fecha" name="fecha" type="date" value="{{ old('fecha', $consulta->fecha?->format('Y-m-d')) }}" required />

                <x-wire-native-select label="Motivo de Consulta" name="motivo_consulta_id">
                    <option value="">Seleccionar...</option>
                    @foreach ($motivos as $motivo)
                        <option value="{{ $motivo->id }}" @selected(old('motivo_consulta_id', $consulta->motivo_consulta_id) == $motivo->id)>{{ $motivo->nombre }}</option>
                    @endforeach
                </x-wire-native-select>

                <div class="lg:col-span-2">
                    <x-wire-input label="Detalle del Motivo" name="motivo_detalle" value="{{ old('motivo_detalle', $consulta->motivo_detalle) }}" />
                </div>

            </div>

            <h3 class="text-base font-semibold text-gray-700 mt-6 mb-3">Signos Vitales</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                <x-wire-input label="Peso (kg)" name="peso" type="number" step="0.1" value="{{ old('peso', $consulta->peso) }}" />
                <x-wire-input label="Talla (cm)" name="talla" type="number" step="0.1" value="{{ old('talla', $consulta->talla) }}" />
                <x-wire-input label="Temperatura (°C)" name="temperatura" type="number" step="0.1" value="{{ old('temperatura', $consulta->temperatura) }}" />
                <x-wire-input label="FC (lpm)" name="fc" type="number" value="{{ old('fc', $consulta->fc) }}" />
                <x-wire-input label="FR (rpm)" name="fr" type="number" value="{{ old('fr', $consulta->fr) }}" />
                <x-wire-input label="SpO2 (%)" name="spo2" type="number" value="{{ old('spo2', $consulta->spo2) }}" />
            </div>

            <div class="grid lg:grid-cols-2 gap-4 mt-4">
                <x-wire-textarea label="Diagnóstico" name="diagnostico">{{ old('diagnostico', $consulta->diagnostico) }}</x-wire-textarea>
                <x-wire-textarea label="Tratamiento" name="tratamiento">{{ old('tratamiento', $consulta->tratamiento) }}</x-wire-textarea>
                <div class="lg:col-span-2">
                    <x-wire-textarea label="Notas" name="notas">{{ old('notas', $consulta->notas) }}</x-wire-textarea>
                </div>
            </div>
        </x-wire-card>

    </form>
</x-admin-layout>
