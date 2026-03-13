<x-admin-layout title="Planes de Vacunación" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Catálogos'],
    ['name' => 'Planes de Vacunación', 'href' => route('admin.catalogos.planes-vacunacion.index')],
    ['name' => 'Nuevo'],
]">
    <x-wire-card>
        <form action="{{ route('admin.catalogos.planes-vacunacion.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-4">
                <x-wire-input label="Nombre" name="nombre" :value="old('nombre')" required />
                <x-wire-textarea label="Descripción" name="descripcion">{{ old('descripcion') }}</x-wire-textarea>
            </div>
            <div class="flex justify-end mt-4">
                <x-wire-button type="submit" blue><i class="fa fa-save"></i> Guardar</x-wire-button>
            </div>
        </form>
    </x-wire-card>
</x-admin-layout>
