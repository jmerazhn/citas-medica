<x-admin-layout 
title="Usuarios"
:breadcrumbs="[
    [
        'name' => 'Dashboard', 'url' => route('admin.dashboard'),
        'href' => route('admin.dashboard')
    ],
    [
        'name' => 'Usuarios'
    ]
]">

    <x-slot name="action">
        @can('gestionar-usuarios')
        <x-wire-button blue href="{{ route('admin.users.create') }}">
            <i class="fa fa-plus"></i> Nuevo Usuario
        </x-wire-button>
        @endcan
    </x-slot>

    @livewire('admin.datatables.user-table')
</x-admin-layout>