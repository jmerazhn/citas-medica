<x-admin-layout title="Editar Embarazo" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Pacientes', 'href' => route('admin.patients.index')],
    ['name' => $patient->full_name, 'href' => route('admin.patients.show', $patient)],
    ['name' => 'Editar Embarazo'],
]">
    <form action="{{ route('admin.embarazos.update', $embarazo) }}" method="POST">
        @csrf
        @method('PUT')

        <x-wire-card class="mb-6">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">Datos del Embarazo — {{ $patient->full_name }}</h2>
                <div class="flex space-x-3">
                    <x-wire-button outline gray href="{{ route('admin.patients.show', $patient) }}">Volver</x-wire-button>
                    <x-wire-button type="submit" primary><i class="fa-solid fa-check"></i> Actualizar</x-wire-button>
                </div>
            </div>
        </x-wire-card>

        <x-wire-card>
            <div class="grid lg:grid-cols-2 gap-4">

                <x-wire-input label="No. de Gestación" name="numero_embarazo" type="number" min="1"
                    value="{{ old('numero_embarazo', $embarazo->numero_embarazo) }}" />
                <x-wire-input label="Obstetra" name="obstetra"
                    value="{{ old('obstetra', $embarazo->obstetra) }}" placeholder="Nombre del obstetra" />
                <x-wire-input label="Duración (semanas)" name="semanas_gestacion" type="number" min="1" max="45"
                    value="{{ old('semanas_gestacion', $embarazo->semanas_gestacion) }}" />

                {{-- Complicaciones (checkboxes múltiples) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Complicaciones del embarazo</label>
                    <div class="flex flex-wrap gap-4">
                        @foreach (['diabetes' => 'Diabetes', 'hipertension' => 'Hipertensión', 'traumatismo' => 'Traumatismo'] as $field => $label)
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="{{ $field }}" value="1"
                                    {{ old($field, $embarazo->$field) ? 'checked' : '' }}
                                    class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm text-gray-700">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Infecciones SI/NO --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Infecciones</label>
                    <div class="flex gap-6">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="infecciones" value="1"
                                {{ old('infecciones', $embarazo->infecciones ? '1' : '0') == '1' ? 'checked' : '' }}
                                class="w-4 h-4 border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Sí</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="infecciones" value="0"
                                {{ old('infecciones', $embarazo->infecciones ? '1' : '0') == '0' ? 'checked' : '' }}
                                class="w-4 h-4 border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">No</span>
                        </label>
                    </div>
                </div>

                {{-- Asma SI/NO --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Asma</label>
                    <div class="flex gap-6">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="asma" value="1"
                                {{ old('asma', $embarazo->asma ? '1' : '0') == '1' ? 'checked' : '' }}
                                class="w-4 h-4 border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Sí</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="asma" value="0"
                                {{ old('asma', $embarazo->asma ? '1' : '0') == '0' ? 'checked' : '' }}
                                class="w-4 h-4 border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">No</span>
                        </label>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <x-wire-textarea label="Medicación" name="medicacion" rows="3" placeholder="Medicamentos tomados durante el embarazo...">{{ old('medicacion', $embarazo->medicacion) }}</x-wire-textarea>
                </div>
                <div class="lg:col-span-2">
                    <x-wire-textarea label="Observaciones" name="observaciones" rows="3" placeholder="Observaciones adicionales...">{{ old('observaciones', $embarazo->observaciones) }}</x-wire-textarea>
                </div>

            </div>
        </x-wire-card>

    </form>
</x-admin-layout>
