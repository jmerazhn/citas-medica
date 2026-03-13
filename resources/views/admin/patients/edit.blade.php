<x-admin-layout title="Pacientes" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Pacientes', 'href' => route('admin.patients.index')],
    ['name' => 'Editar Paciente'],
]">
    <form action="{{ route('admin.patients.update', $patient) }}" method="POST">
        @csrf
        @method('PUT')

        <x-wire-card class="mb-6">
            <div class="lg:flex lg:justify-between lg:items-center">
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $patient->full_name }}</p>
                    @if ($patient->telefono)
                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fa-solid fa-phone me-1"></i>{{ $patient->telefono }}
                        </p>
                    @endif
                </div>
                <div class="flex space-x-3 mt-4 lg:mt-0">
                    <x-wire-button outline gray href="{{ route('admin.patients.index') }}">
                        Volver
                    </x-wire-button>
                    <x-wire-button type="submit" primary>
                        <i class="fa-solid fa-check"></i> Guardar Cambios
                    </x-wire-button>
                </div>
            </div>
        </x-wire-card>

        <x-wire-card>
            <div class="grid lg:grid-cols-2 gap-4">

                <x-wire-input
                    label="Nombres"
                    name="nombres"
                    type="text"
                    value="{{ old('nombres', $patient->nombres) }}"
                    required />

                <x-wire-input
                    label="Apellidos"
                    name="apellidos"
                    type="text"
                    value="{{ old('apellidos', $patient->apellidos) }}"
                    required />

                <x-wire-native-select label="Sexo" name="sexo">
                    <option value="">Seleccionar...</option>
                    <option value="M" @selected(old('sexo', $patient->sexo) === 'M')>Masculino</option>
                    <option value="F" @selected(old('sexo', $patient->sexo) === 'F')>Femenino</option>
                </x-wire-native-select>

                <x-wire-input
                    label="Fecha de Nacimiento"
                    name="fecha_nacimiento"
                    type="date"
                    value="{{ old('fecha_nacimiento', $patient->fecha_nacimiento?->format('Y-m-d')) }}" />

                <x-wire-input
                    label="Madre"
                    name="madre"
                    type="text"
                    value="{{ old('madre', $patient->madre) }}" />

                <x-wire-input
                    label="Padre"
                    name="padre"
                    type="text"
                    value="{{ old('padre', $patient->padre) }}" />

                <x-wire-input
                    label="Domicilio"
                    name="domicilio"
                    type="text"
                    value="{{ old('domicilio', $patient->domicilio) }}" />

                <x-wire-input
                    label="Ciudad"
                    name="ciudad"
                    type="text"
                    value="{{ old('ciudad', $patient->ciudad) }}" />

                <x-wire-input
                    label="Teléfono"
                    name="telefono"
                    type="text"
                    value="{{ old('telefono', $patient->telefono) }}" />

                <x-wire-native-select label="Cobertura Social" name="social_coverage_id">
                    <option value="">Sin cobertura / Particular</option>
                    @foreach ($socialCoverages as $coverage)
                        <option value="{{ $coverage->id }}" @selected(old('social_coverage_id', $patient->social_coverage_id) == $coverage->id)>
                            {{ $coverage->name }}
                        </option>
                    @endforeach
                </x-wire-native-select>

                <x-wire-native-select label="Tipo de Sangre" name="blood_type_id">
                    <option value="">Seleccionar...</option>
                    @foreach ($bloodTypes as $bt)
                        <option value="{{ $bt->id }}" @selected(old('blood_type_id', $patient->blood_type_id) == $bt->id)>
                            {{ $bt->name }}
                        </option>
                    @endforeach
                </x-wire-native-select>

                <div class="lg:col-span-2">
                    <x-wire-textarea
                        label="Notas Importantes"
                        name="notas_importantes">
                        {{ old('notas_importantes', $patient->notas_importantes) }}
                    </x-wire-textarea>
                </div>

            </div>
        </x-wire-card>

    </form>
</x-admin-layout>
