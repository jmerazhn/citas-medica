<x-admin-layout title="Coberturas Sociales" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Catálogos'],
    ['name' => 'Coberturas Sociales'],
]">
    <x-slot name="action">
        <x-wire-button blue href="{{ route('admin.catalogos.coberturas-sociales.create') }}">
            <i class="fa fa-plus"></i> Nueva Cobertura
        </x-wire-button>
    </x-slot>

    @livewire('admin.datatables.social-coverage-table')
</x-admin-layout>
