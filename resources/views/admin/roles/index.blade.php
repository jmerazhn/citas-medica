<x-admin-layout 
title="Roles"
:breadcrumbs="[
    [
        'name' => 'Dashboard', 'url' => route('admin.dashboard'),
        'href' => route('admin.dashboard')
    ],
    [
        'name' => 'Roles'
    ]
]">

    <x-slot name="action">
        <x-wire-button blue href="{{ route('admin.roles.create') }}">
            <i class="fa fa-plus"></i> Nuevo Rol
        </x-wire-button>
    </x-slot>

    @livewire('admin.datatables.role-table')
</x-admin-layout>