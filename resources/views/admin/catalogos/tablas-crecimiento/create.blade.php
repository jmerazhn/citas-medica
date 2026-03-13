<x-admin-layout title="Tablas de Crecimiento" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Catálogos'],
    ['name' => 'Tablas de Crecimiento', 'href' => route('admin.catalogos.tablas-crecimiento.index')],
    ['name' => 'Nuevo'],
]">
    <x-wire-card>
        <form action="{{ route('admin.catalogos.tablas-crecimiento.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-wire-native-select label="Tipo" name="tipo" required>
                    <option value="">Seleccionar...</option>
                    @foreach(\App\Models\TablaCrecimiento::$tipos as $key => $label)
                        <option value="{{ $key }}" {{ old('tipo') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </x-wire-native-select>

                <x-wire-native-select label="Sexo" name="sexo" required>
                    <option value="">Seleccionar...</option>
                    <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                    <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Femenino</option>
                </x-wire-native-select>

                <x-wire-input label="Edad (meses)" name="edad_meses" type="number" min="0" max="228" :value="old('edad_meses')" required />
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4 mt-4">
                <x-wire-input label="P3" name="p3" type="number" step="0.01" :value="old('p3')" required />
                <x-wire-input label="P10" name="p10" type="number" step="0.01" :value="old('p10')" required />
                <x-wire-input label="P25" name="p25" type="number" step="0.01" :value="old('p25')" required />
                <x-wire-input label="P50" name="p50" type="number" step="0.01" :value="old('p50')" required />
                <x-wire-input label="P75" name="p75" type="number" step="0.01" :value="old('p75')" required />
                <x-wire-input label="P90" name="p90" type="number" step="0.01" :value="old('p90')" required />
                <x-wire-input label="P97" name="p97" type="number" step="0.01" :value="old('p97')" required />
            </div>

            <div class="flex justify-end mt-4">
                <x-wire-button type="submit" blue><i class="fa fa-save"></i> Guardar</x-wire-button>
            </div>
        </form>
    </x-wire-card>
</x-admin-layout>
