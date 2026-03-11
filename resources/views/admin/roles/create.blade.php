<x-admin-layout 
title="Roles"
:breadcrumbs="[
    [
        'name' => 'Dashboard', 'url' => route('admin.dashboard'),
        'href' => route('admin.dashboard')
    ],
    [
        'name' => 'Roles',
        'href' => route('admin.roles.index')
    ],
    [
        'name' => 'Nuevo'
    ]
]">
    <x-wire-card>
        <form action="{{ route('admin.roles.store') }}" method="POST">
            @csrf
            <x-wire-input 
                label="Nombre del Rol" 
                name="name" 
                placeholder="Ingrese el nombre del rol" 
                :value="old('name')" 
                required
            />
            <div class="flex justify-end mt-4">
                <x-wire-button type="submit" blue>
                    <i class="fa fa-save"></i> Guardar Rol
                </x-wire-button>
            </div>
    </x-wire-card>
</x-admin-layout>