<x-admin-layout title="Registrar Parto" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Pacientes', 'href' => route('admin.patients.index')],
    ['name' => $patient->full_name, 'href' => route('admin.patients.show', $patient)],
    ['name' => 'Registrar Parto'],
]">
    <form action="{{ route('admin.patients.partos.store', $patient) }}" method="POST">
        @csrf

        <x-wire-card class="mb-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                <h2 class="text-lg sm:text-xl font-bold text-gray-800">Datos del Parto — {{ $patient->full_name }}</h2>
                <div class="flex gap-2">
                    <x-wire-button outline gray href="{{ route('admin.patients.show', $patient) }}">Volver</x-wire-button>
                    <x-wire-button type="submit" primary><i class="fa-solid fa-check"></i> Guardar</x-wire-button>
                </div>
            </div>
        </x-wire-card>

        <div class="grid md:grid-cols-2 gap-6" x-data="{ cesarea: {{ old('cesarea') ? 'true' : 'false' }} }">

            {{-- Datos del Parto --}}
            <x-wire-card>
                <h3 class="font-semibold text-gray-700 mb-4">Datos del Parto</h3>
                <div class="space-y-4">

                    <x-wire-input label="Fecha de Parto" name="fecha_parto" type="date"
                        value="{{ old('fecha_parto', date('Y-m-d')) }}" required />

                    <x-wire-input label="Lugar" name="lugar"
                        value="{{ old('lugar') }}" placeholder="Hospital, clínica, domicilio..." />

                    {{-- Cesárea --}}
                    <div>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="cesarea" value="1"
                                x-model="cesarea"
                                class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-medium text-gray-700">Cesárea</span>
                        </label>
                    </div>

                    <div x-show="cesarea" x-transition>
                        <x-wire-textarea label="Motivo de Cesárea" name="motivo_cesarea" rows="2">{{ old('motivo_cesarea') }}</x-wire-textarea>
                    </div>

                    {{-- Posición --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Posición</label>
                        <div class="flex gap-6">
                            @foreach (['cefalica' => 'Cefálica', 'podalica' => 'Podálica'] as $val => $lbl)
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="posicion" value="{{ $val }}"
                                        {{ old('posicion') === $val ? 'checked' : '' }}
                                        class="w-4 h-4 border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">{{ $lbl }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Tipo de Parto --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Parto</label>
                        <div class="flex gap-6">
                            @foreach (['eutocico' => 'Eutócico', 'distocico' => 'Distócico'] as $val => $lbl)
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="parto_tipo" value="{{ $val }}"
                                        {{ old('parto_tipo') === $val ? 'checked' : '' }}
                                        class="w-4 h-4 border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">{{ $lbl }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Anestesia --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Anestesia</label>
                        <div class="flex flex-wrap gap-4">
                            @foreach (['no' => 'No', 'raquidea' => 'Raquídea', 'peridural' => 'Peridural', 'total' => 'Total'] as $val => $lbl)
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="anestesia" value="{{ $val }}"
                                        {{ old('anestesia', 'no') === $val ? 'checked' : '' }}
                                        class="w-4 h-4 border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">{{ $lbl }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <x-wire-input label="Apgar" name="apgar" value="{{ old('apgar') }}" placeholder="ej. 8/9" />
                    <x-wire-input label="Parto Gamma" name="parto_gamma" value="{{ old('parto_gamma') }}" />
                    <x-wire-textarea label="Observaciones" name="observaciones" rows="3">{{ old('observaciones') }}</x-wire-textarea>

                </div>
            </x-wire-card>

            {{-- Datos del Recién Nacido --}}
            <x-wire-card>
                <h3 class="font-semibold text-gray-700 mb-4">Datos del Recién Nacido</h3>
                <div class="space-y-4">

                    <x-wire-input label="Peso (kg)" name="peso_rn" type="number" step="0.001" min="0" value="{{ old('peso_rn') }}" placeholder="ej. 3.200" />
                    <x-wire-input label="Altura (cm)" name="talla_rn" type="number" step="0.1" min="0" value="{{ old('talla_rn') }}" placeholder="ej. 50" />
                    <x-wire-input label="P.C. (cm)" name="pc_rn" type="number" step="0.1" min="0" value="{{ old('pc_rn') }}" placeholder="ej. 34" />
                    <x-wire-input label="Ombligo (días)" name="ombligo_dias" type="number" step="1" min="0" value="{{ old('ombligo_dias') }}" placeholder="ej. 7" />
                    <x-wire-textarea label="Observaciones RN" name="observaciones_rn" rows="4">{{ old('observaciones_rn') }}</x-wire-textarea>

                </div>
            </x-wire-card>

        </div>

    </form>
</x-admin-layout>
