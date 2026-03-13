<x-admin-layout title="Patologías" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Catálogos'],
    ['name' => 'Patologías', 'href' => route('admin.catalogos.patologias.index')],
    ['name' => 'Editar'],
]">
    <x-wire-card>
        <form action="{{ route('admin.catalogos.patologias.update', $patologia) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-4">
                <x-wire-input label="Nombre" name="nombre" :value="old('nombre', $patologia->nombre)" required />
                <x-wire-textarea label="Descripción" name="descripcion">{{ old('descripcion', $patologia->descripcion) }}</x-wire-textarea>
            </div>
            <div class="flex justify-end mt-4">
                <x-wire-button type="submit" blue><i class="fa fa-save"></i> Actualizar</x-wire-button>
            </div>
        </form>
    </x-wire-card>
</x-admin-layout>
