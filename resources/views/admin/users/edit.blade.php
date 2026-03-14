<x-admin-layout title="Usuarios" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'url' => route('admin.dashboard'),
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Usuarios',
        'href' => route('admin.users.index'),
    ],
    [
        'name' => 'Editar',
    ],
]">
    <x-wire-card>
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div class="grid md:grid-cols-2 gap-4">
                    <x-wire-input label="Nombre Completo" name="name"
                        placeholder="Ingresa el nombre completo del usuario" required :value="old('name', $user->name)" />

                    <x-wire-input label="Correo Electrónico" name="email" type="email"
                        placeholder="Ingresa el correo electrónico del usuario" required :value="old('email', $user->email)" />

                    <x-wire-input label="Contraseña" name="password" type="password"
                        placeholder="Ingresa la contraseña del usuario" :value="old('password')" />
                    <x-wire-input label="Confirmar Contraseña" name="password_confirmation" type="password"
                        placeholder="Confirma la contraseña del usuario" :value="old('password_confirmation')" />
                </div>
                <x-wire-native-select label="Rol" name="role_id" required>
                    <option value="" disabled selected>Selecciona un rol</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" @selected(old('role_id', $user->roles->first()->id) == $role->id)>
                            {{ $role->name }}</option>
                    @endforeach
                </x-wire-native-select>

                <div class="flex justify-end">
                    <x-wire-button type="submit" blue>
                        <i class="fa fa-save"></i> Actualizar Usuario
                    </x-wire-button>
                </div>


            </div>
        </form>

    </x-wire-card>
</x-admin-layout>
