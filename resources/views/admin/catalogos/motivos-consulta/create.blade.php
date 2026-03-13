<x-admin-layout title="Motivos de Consulta" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Catálogos'],
    ['name' => 'Motivos de Consulta', 'href' => route('admin.catalogos.motivos-consulta.index')],
    ['name' => 'Nuevo'],
]">
    <x-wire-card>
        <form action="{{ route('admin.catalogos.motivos-consulta.store') }}" method="POST">
            @csrf
            <x-wire-input label="Nombre" name="nombre" :value="old('nombre')" required />
            <div class="flex justify-end mt-4">
                <x-wire-button type="submit" blue><i class="fa fa-save"></i> Guardar</x-wire-button>
            </div>
        </form>
    </x-wire-card>
</x-admin-layout>
