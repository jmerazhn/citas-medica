<x-admin-layout title="Planes de Vacunación" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Catálogos'],
    ['name' => 'Planes de Vacunación'],
]">
    <x-slot name="action">
        <x-wire-button blue href="{{ route('admin.catalogos.planes-vacunacion.create') }}">
            <i class="fa fa-plus"></i> Nuevo Plan
        </x-wire-button>
    </x-slot>

    @livewire('admin.datatables.plan-vacunacion-table')
</x-admin-layout>
