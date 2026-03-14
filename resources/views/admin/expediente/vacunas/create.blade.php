<x-admin-layout title="Registrar Vacuna" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Pacientes', 'href' => route('admin.patients.index')],
    ['name' => $patient->full_name, 'href' => route('admin.patients.show', $patient)],
    ['name' => 'Registrar Vacuna'],
]">
    <form action="{{ route('admin.patients.vacunas.store', $patient) }}" method="POST">
        @csrf

        <x-wire-card class="mb-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                <h2 class="text-lg sm:text-xl font-bold text-gray-800">Registrar Vacuna — {{ $patient->full_name }}</h2>
                <div class="flex gap-2">
                    <x-wire-button outline gray href="{{ route('admin.patients.show', $patient) }}">Volver</x-wire-button>
                    <x-wire-button type="submit" primary><i class="fa-solid fa-check"></i> Guardar</x-wire-button>
                </div>
            </div>
        </x-wire-card>

        <x-wire-card>
            <div class="grid md:grid-cols-2 gap-4">

                <x-wire-native-select label="Plan de Vacunación" name="plan_vacunacion_id">
                    <option value="">Sin plan / Independiente</option>
                    @foreach ($planes as $plan)
                        <option value="{{ $plan->id }}" @selected(old('plan_vacunacion_id') == $plan->id)>{{ $plan->nombre }}</option>
                    @endforeach
                </x-wire-native-select>

                <x-wire-input label="Vacuna" name="vacuna" value="{{ old('vacuna') }}" required />
                <x-wire-input label="Fecha de Aplicación" name="fecha_aplicacion" type="date" value="{{ old('fecha_aplicacion', date('Y-m-d')) }}" required />
                <x-wire-input label="Dosis" name="dosis" value="{{ old('dosis') }}" />
                <x-wire-input label="Lote" name="lote" value="{{ old('lote') }}" />
                <div class="md:col-span-2">
                    <x-wire-textarea label="Notas" name="notas">{{ old('notas') }}</x-wire-textarea>
                </div>

            </div>
        </x-wire-card>

    </form>
</x-admin-layout>
