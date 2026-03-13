<x-admin-layout title="Tablas de Crecimiento" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Catálogos'],
    ['name' => 'Tablas de Crecimiento'],
]">
    <x-slot name="action">
        <x-wire-button blue href="{{ route('admin.catalogos.tablas-crecimiento.create') }}">
            <i class="fa fa-plus"></i> Nuevo Registro
        </x-wire-button>
    </x-slot>

    @livewire('admin.datatables.tabla-crecimiento-table')
</x-admin-layout>
