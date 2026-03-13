<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Super Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
        <h1 class="text-lg font-bold text-gray-800">Super Admin — {{ config('app.name') }}</h1>
        <form method="POST" action="{{ route('super-admin.logout') }}">
            @csrf
            <button type="submit" class="text-sm text-gray-600 hover:text-red-600">Cerrar sesión</button>
        </form>
    </nav>

    <div class="max-w-6xl mx-auto py-8 px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-500">Total Consultorios</p>
                <p class="text-3xl font-bold text-gray-800">{{ \App\Models\Tenant::count() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-500">Activos</p>
                <p class="text-3xl font-bold text-green-600">{{ \App\Models\Tenant::where('activo', true)->count() }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Consultorios</h2>
                <div class="flex gap-2">
                    <a href="{{ route('super-admin.consultorios.index') }}"
                       class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm px-4 py-2 rounded-lg">
                        Ver todos
                    </a>
                    <a href="{{ route('super-admin.consultorios.create') }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg">
                        + Nuevo Consultorio
                    </a>
                </div>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b">
                        <th class="pb-3">Slug</th>
                        <th class="pb-3">Nombre</th>
                        <th class="pb-3">Estado</th>
                        <th class="pb-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(\App\Models\Tenant::latest()->take(10)->get() as $tenant)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 font-mono text-gray-600">{{ $tenant->id }}</td>
                            <td class="py-3 font-medium">{{ $tenant->nombre }}</td>
                            <td class="py-3">
                                @if($tenant->activo)
                                    <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs">Activo</span>
                                @else
                                    <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded text-xs">Inactivo</span>
                                @endif
                            </td>
                            <td class="py-3">
                                <a href="http://{{ request()->getHost() }}/{{ $tenant->id }}/admin" target="_blank"
                                   class="text-green-600 hover:underline text-xs">Abrir panel →</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-gray-400">No hay consultorios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
