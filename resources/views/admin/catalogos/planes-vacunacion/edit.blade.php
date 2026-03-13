<x-admin-layout title="Planes de Vacunación" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Catálogos'],
    ['name' => 'Planes de Vacunación', 'href' => route('admin.catalogos.planes-vacunacion.index')],
    ['name' => 'Editar'],
]">
    <x-wire-card>
        <form action="{{ route('admin.catalogos.planes-vacunacion.update', $planVacunacion) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-4">
                <x-wire-input label="Nombre" name="nombre" :value="old('nombre', $planVacunacion->nombre)" required />
                <x-wire-textarea label="Descripción" name="descripcion">{{ old('descripcion', $planVacunacion->descripcion) }}</x-wire-textarea>
            </div>
            <div class="flex justify-end mt-4">
                <x-wire-button type="submit" blue><i class="fa fa-save"></i> Actualizar</x-wire-button>
            </div>
        </form>
    </x-wire-card>
</x-admin-layout>
