<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultorios — Super Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
        <div class="flex items-center gap-4">
            <a href="{{ route('super-admin.dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
            <h1 class="text-lg font-bold text-gray-800">Consultorios</h1>
        </div>
        <form method="POST" action="{{ route('super-admin.logout') }}">
            @csrf
            <button type="submit" class="text-sm text-gray-600 hover:text-red-600">Cerrar sesión</button>
        </form>
    </nav>

    <div class="max-w-6xl mx-auto py-8 px-4">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-semibold text-gray-800">Todos los consultorios</h2>
                <a href="{{ route('super-admin.consultorios.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg">
                    + Nuevo Consultorio
                </a>
            </div>

            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b">
                        <th class="pb-3">Slug (ID)</th>
                        <th class="pb-3">Nombre</th>
                        <th class="pb-3">Email</th>
                        <th class="pb-3">Estado</th>
                        <th class="pb-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tenants as $tenant)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 font-mono text-gray-600">{{ $tenant->id }}</td>
                            <td class="py-3 font-medium">{{ $tenant->nombre }}</td>
                            <td class="py-3 text-gray-600">{{ $tenant->email ?? '—' }}</td>
                            <td class="py-3">
                                @if($tenant->activo)
                                    <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs">Activo</span>
                                @else
                                    <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded text-xs">Inactivo</span>
                                @endif
                            </td>
                            <td class="py-3">
                                <div class="flex gap-2">
                                    <a href="{{ route('super-admin.consultorios.edit', $tenant) }}"
                                       class="text-blue-600 hover:underline">Editar</a>
                                    <a href="http://{{ request()->getHost() }}/{{ $tenant->id }}/admin" target="_blank"
                                       class="text-green-600 hover:underline">Ver panel</a>
                                    <form method="POST" action="{{ route('super-admin.consultorios.destroy', $tenant) }}"
                                          onsubmit="return confirm('¿Eliminar este consultorio y su base de datos?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400">No hay consultorios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">{{ $tenants->links() }}</div>
        </div>
    </div>
</body>
</html>
