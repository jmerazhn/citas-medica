<x-admin-layout
title="Roles"
:breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Roles',     'href' => route('admin.roles.index')],
    ['name' => 'Editar'],
]">
    <form action="{{ route('admin.roles.update', $role) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-6">

            {{-- Nombre --}}
            <x-wire-card>
                <h3 class="text-base font-semibold text-gray-700 dark:text-gray-200 mb-4">Información del rol</h3>

                @if($protected)
                    <div class="flex items-center gap-2 p-3 mb-4 text-sm text-amber-800 bg-amber-50 rounded-lg border border-amber-200">
                        <i class="fa fa-lock text-amber-500"></i>
                        <span>Este es un rol predeterminado. El nombre no puede modificarse, pero puedes ajustar sus permisos.</span>
                    </div>
                    <x-wire-input
                        label="Nombre del Rol"
                        :value="$role->name"
                        disabled
                    />
                @else
                    <x-wire-input
                        label="Nombre del Rol"
                        name="name"
                        placeholder="Ingrese el nombre del rol"
                        :value="old('name', $role->name)"
                        required
                    />
                @endif
            </x-wire-card>

            {{-- Permisos --}}
            <x-wire-card>
                <h3 class="text-base font-semibold text-gray-700 dark:text-gray-200 mb-1">Permisos</h3>
                <p class="text-sm text-gray-500 mb-6">Selecciona los permisos que tendrá este rol.</p>

                <div class="space-y-6">
                    @foreach($groups as $groupName => $groupPermissions)
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide">
                                    {{ $groupName }}
                                </h4>
                                <button type="button"
                                    class="text-xs text-blue-600 hover:underline toggle-group"
                                    data-group="{{ Str::slug($groupName) }}">
                                    Seleccionar todo
                                </button>
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3" data-group-boxes="{{ Str::slug($groupName) }}">
                                @foreach($groupPermissions as $permission)
                                    <label class="flex items-center gap-2 p-3 rounded-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer select-none">
                                        <input
                                            type="checkbox"
                                            name="permissions[]"
                                            value="{{ $permission }}"
                                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                                            {{ $role->hasPermissionTo($permission) ? 'checked' : '' }}
                                        >
                                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $permission }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        @if(!$loop->last)
                            <hr class="border-gray-200 dark:border-gray-600">
                        @endif
                    @endforeach
                </div>
            </x-wire-card>

            {{-- Acciones --}}
            <div class="flex justify-end gap-3">
                <x-wire-button href="{{ route('admin.roles.index') }}" flat>
                    Cancelar
                </x-wire-button>
                <x-wire-button type="submit" blue>
                    <i class="fa fa-save"></i> Guardar cambios
                </x-wire-button>
            </div>

        </div>
    </form>

    @push('scripts')
    <script>
        document.querySelectorAll('.toggle-group').forEach(btn => {
            btn.addEventListener('click', () => {
                const group = btn.dataset.group;
                const boxes = document.querySelectorAll(`[data-group-boxes="${group}"] input[type="checkbox"]`);
                const allChecked = [...boxes].every(cb => cb.checked);
                boxes.forEach(cb => cb.checked = !allChecked);
                btn.textContent = allChecked ? 'Seleccionar todo' : 'Deseleccionar todo';
            });
        });
    </script>
    @endpush
</x-admin-layout>
