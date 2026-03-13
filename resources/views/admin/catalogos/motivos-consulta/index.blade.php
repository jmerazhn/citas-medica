<x-admin-layout title="Motivos de Consulta" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Catálogos'],
    ['name' => 'Motivos de Consulta'],
]">
    <x-slot name="action">
        <x-wire-button blue href="{{ route('admin.catalogos.motivos-consulta.create') }}">
            <i class="fa fa-plus"></i> Nuevo Motivo
        </x-wire-button>
    </x-slot>

    @livewire('admin.datatables.motivo-consulta-table')
</x-admin-layout>
