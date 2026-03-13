<x-admin-layout title="Pacientes" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Pacientes', 'href' => route('admin.patients.index')],
    ['name' => 'Nuevo Paciente'],
]">
    <form action="{{ route('admin.patients.store') }}" method="POST">
        @csrf

        <x-wire-card class="mb-6">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">Nuevo Paciente</h2>
                <div class="flex space-x-3">
                    <x-wire-button outline gray href="{{ route('admin.patients.index') }}">
                        Volver
                    </x-wire-button>
                    <x-wire-button type="submit" primary>
                        <i class="fa-solid fa-check"></i> Guardar
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
                    value="{{ old('nombres') }}"
                    required />

                <x-wire-input
                    label="Apellidos"
                    name="apellidos"
                    type="text"
                    value="{{ old('apellidos') }}"
                    required />

                <x-wire-native-select label="Sexo" name="sexo">
                    <option value="">Seleccionar...</option>
                    <option value="M" @selected(old('sexo') === 'M')>Masculino</option>
                    <option value="F" @selected(old('sexo') === 'F')>Femenino</option>
                </x-wire-native-select>

                <x-wire-input
                    label="Fecha de Nacimiento"
                    name="fecha_nacimiento"
                    type="date"
                    value="{{ old('fecha_nacimiento') }}" />

                <x-wire-input
                    label="Madre"
                    name="madre"
                    type="text"
                    value="{{ old('madre') }}" />

                <x-wire-input
                    label="Padre"
                    name="padre"
                    type="text"
                    value="{{ old('padre') }}" />

                <x-wire-input
                    label="Domicilio"
                    name="domicilio"
                    type="text"
                    value="{{ old('domicilio') }}" />

                <x-wire-input
                    label="Ciudad"
                    name="ciudad"
                    type="text"
                    value="{{ old('ciudad') }}" />

                <x-wire-input
                    label="Teléfono"
                    name="telefono"
                    type="text"
                    value="{{ old('telefono') }}" />

                <x-wire-native-select label="Cobertura Social" name="social_coverage_id">
                    <option value="">Sin cobertura / Particular</option>
                    @foreach ($socialCoverages as $coverage)
                        <option value="{{ $coverage->id }}" @selected(old('social_coverage_id') == $coverage->id)>
                            {{ $coverage->name }}
                        </option>
                    @endforeach
                </x-wire-native-select>

                <x-wire-native-select label="Tipo de Sangre" name="blood_type_id">
                    <option value="">Seleccionar...</option>
                    @foreach ($bloodTypes as $bt)
                        <option value="{{ $bt->id }}" @selected(old('blood_type_id') == $bt->id)>
                            {{ $bt->name }}
                        </option>
                    @endforeach
                </x-wire-native-select>

                <div class="lg:col-span-2">
                    <x-wire-textarea
                        label="Notas Importantes"
                        name="notas_importantes">
                        {{ old('notas_importantes') }}
                    </x-wire-textarea>
                </div>

            </div>
        </x-wire-card>

    </form>
</x-admin-layout>
