<x-admin-layout 
title="Pacientes"
:breadcrumbs="[
    [
        'name' => 'Dashboard', 'url' => route('admin.dashboard'),
        'href' => route('admin.dashboard')
    ],
    [
        'name' => 'Pacientes'
    ]
]">

    <x-slot name="action">
        <x-wire-button blue href="{{ route('admin.patients.create') }}">
            <i class="fa fa-plus"></i> Nuevo Paciente
        </x-wire-button>
    </x-slot>

    @livewire('admin.datatables.patient-table')
</x-admin-layout>