<x-admin-layout title="Pacientes" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'url' => route('admin.dashboard'),
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Pacientes',
        'href' => route('admin.patients.index'),
    ],
    [
        'name' => 'Nuevo Paciente',
    ],
]">

</x-admin-layout>
