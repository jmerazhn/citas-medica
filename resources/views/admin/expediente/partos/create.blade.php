<x-admin-layout title="Registrar Parto" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Pacientes', 'href' => route('admin.patients.index')],
    ['name' => $patient->full_name, 'href' => route('admin.patients.show', $patient)],
    ['name' => 'Registrar Parto'],
]">
    <form action="{{ route('admin.patients.partos.store', $patient) }}" method="POST">
        @csrf

        <x-wire-card class="mb-6">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">Registrar Parto — {{ $patient->full_name }}</h2>
                <p class="text-sm text-gray-500">Datos obstétricos de la madre del paciente</p>
                <div class="flex space-x-3">
                    <x-wire-button outline gray href="{{ route('admin.patients.show', $patient) }}">Volver</x-wire-button>
                    <x-wire-button type="submit" primary><i class="fa-solid fa-check"></i> Guardar</x-wire-button>
                </div>
            </div>
        </x-wire-card>

        <x-wire-card>
            <div class="grid lg:grid-cols-2 gap-4">

                @if ($embarazos->isNotEmpty())
                <x-wire-native-select label="Embarazo relacionado (opcional)" name="embarazo_id">
                    <option value="">Sin embarazo vinculado</option>
                    @foreach ($embarazos as $embarazo)
                        <option value="{{ $embarazo->id }}" @selected(old('embarazo_id') == $embarazo->id)>
                            Embarazo #{{ $embarazo->numero_embarazo ?? $embarazo->id }}
                            @if($embarazo->fecha_probable_parto) — FPP: {{ $embarazo->fecha_probable_parto->format('d/m/Y') }} @endif
                        </option>
                    @endforeach
                </x-wire-native-select>
                @endif

                <x-wire-input label="Fecha de Parto" name="fecha_parto" type="date" value="{{ old('fecha_parto', date('Y-m-d')) }}" required />

                <x-wire-native-select label="Tipo de Parto" name="tipo_parto" required>
                    <option value="vaginal" @selected(old('tipo_parto', 'vaginal') === 'vaginal')>Vaginal</option>
                    <option value="cesarea" @selected(old('tipo_parto') === 'cesarea')>Cesárea</option>
                </x-wire-native-select>

                <x-wire-input label="Semanas de Gestación" name="semanas_gestacion" type="number" min="20" max="45" value="{{ old('semanas_gestacion') }}" />

            </div>

            <h3 class="text-base font-semibold text-gray-700 mt-6 mb-3">Datos del Recién Nacido</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <x-wire-input label="Peso RN (kg)" name="peso_rn" type="number" step="0.001" value="{{ old('peso_rn') }}" />
                <x-wire-input label="Talla RN (cm)" name="talla_rn" type="number" step="0.1" value="{{ old('talla_rn') }}" />
                <x-wire-input label="Apgar 1 min" name="apgar_1" type="number" min="0" max="10" value="{{ old('apgar_1') }}" />
                <x-wire-input label="Apgar 5 min" name="apgar_5" type="number" min="0" max="10" value="{{ old('apgar_5') }}" />
            </div>

            <div class="grid lg:grid-cols-2 gap-4 mt-4">
                <x-wire-textarea label="Complicaciones" name="complicaciones">{{ old('complicaciones') }}</x-wire-textarea>
                <x-wire-textarea label="Notas" name="notas">{{ old('notas') }}</x-wire-textarea>
            </div>
        </x-wire-card>

    </form>
</x-admin-layout>
