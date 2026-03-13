<x-admin-layout title="Patologías" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Catálogos'],
    ['name' => 'Patologías'],
]">
    <x-slot name="action">
        <x-wire-button blue href="{{ route('admin.catalogos.patologias.create') }}">
            <i class="fa fa-plus"></i> Nueva Patología
        </x-wire-button>
    </x-slot>

    @livewire('admin.datatables.patologia-table')
</x-admin-layout>
