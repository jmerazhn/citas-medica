<x-admin-layout title="Motivos de Consulta" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Catálogos'],
    ['name' => 'Motivos de Consulta', 'href' => route('admin.catalogos.motivos-consulta.index')],
    ['name' => 'Editar'],
]">
    <x-wire-card>
        <form action="{{ route('admin.catalogos.motivos-consulta.update', $motivoConsulta) }}" method="POST">
            @csrf
            @method('PUT')
            <x-wire-input label="Nombre" name="nombre" :value="old('nombre', $motivoConsulta->nombre)" required />
            <div class="flex justify-end mt-4">
                <x-wire-button type="submit" blue><i class="fa fa-save"></i> Actualizar</x-wire-button>
            </div>
        </form>
    </x-wire-card>
</x-admin-layout>
