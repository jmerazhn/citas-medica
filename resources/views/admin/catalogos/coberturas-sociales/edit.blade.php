<x-admin-layout title="Coberturas Sociales" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Catálogos'],
    ['name' => 'Coberturas Sociales', 'href' => route('admin.catalogos.coberturas-sociales.index')],
    ['name' => 'Editar'],
]">
    <x-wire-card>
        <form action="{{ route('admin.catalogos.coberturas-sociales.update', $socialCoverage) }}" method="POST">
            @csrf
            @method('PUT')
            <x-wire-input label="Nombre" name="name" :value="old('name', $socialCoverage->name)" required />
            <div class="flex justify-end mt-4">
                <x-wire-button type="submit" blue><i class="fa fa-save"></i> Actualizar</x-wire-button>
            </div>
        </form>
    </x-wire-card>
</x-admin-layout>
