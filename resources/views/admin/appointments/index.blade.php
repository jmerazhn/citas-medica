<x-admin-layout
    title="Citas"
    :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href' => route('admin.dashboard'),
        ],
        [
            'name' => 'Citas',
        ],
    ]">

    <x-slot name="action">
        @can('gestionar-citas')
        <x-wire-button blue href="{{ route('admin.appointments.create') }}">
            <i class="fa fa-plus"></i> Nueva Cita
        </x-wire-button>
        @endcan
    </x-slot>

    @livewire('admin.appointment-calendar')
</x-admin-layout>
