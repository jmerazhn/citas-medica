<x-admin-layout title="Registrar Embarazo" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Pacientes', 'href' => route('admin.patients.index')],
    ['name' => $patient->full_name, 'href' => route('admin.patients.show', $patient)],
    ['name' => 'Registrar Embarazo'],
]">
    <form action="{{ route('admin.patients.embarazos.store', $patient) }}" method="POST">
        @csrf

        <x-wire-card class="mb-6">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">Registrar Embarazo — {{ $patient->full_name }}</h2>
                <p class="text-sm text-gray-500">Datos obstétricos de la madre del paciente</p>
                <div class="flex space-x-3">
                    <x-wire-button outline gray href="{{ route('admin.patients.show', $patient) }}">Volver</x-wire-button>
                    <x-wire-button type="submit" primary><i class="fa-solid fa-check"></i> Guardar</x-wire-button>
                </div>
            </div>
        </x-wire-card>

        <x-wire-card>
            <div class="grid lg:grid-cols-2 gap-4">

                <x-wire-input label="N° de Embarazo" name="numero_embarazo" type="number" min="1" value="{{ old('numero_embarazo') }}" />
                <x-wire-input label="Fecha Última Menstruación" name="fecha_ultima_menstruacion" type="date" value="{{ old('fecha_ultima_menstruacion') }}" />
                <x-wire-input label="Fecha Probable de Parto" name="fecha_probable_parto" type="date" value="{{ old('fecha_probable_parto') }}" />
                <x-wire-input label="Semanas de Gestación" name="semanas_gestacion" type="number" min="1" max="45" value="{{ old('semanas_gestacion') }}" />
                <div class="lg:col-span-2">
                    <x-wire-textarea label="Notas" name="notas">{{ old('notas') }}</x-wire-textarea>
                </div>

            </div>
        </x-wire-card>

    </form>
</x-admin-layout>
