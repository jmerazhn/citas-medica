<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Consultorio — Super Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-white shadow px-6 py-4 flex items-center gap-4">
        <a href="{{ route('super-admin.consultorios.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Consultorios</a>
        <h1 class="text-lg font-bold text-gray-800">Editar: {{ $tenant->nombre }}</h1>
    </nav>

    <div class="max-w-2xl mx-auto py-8 px-4">
        <div class="bg-white rounded-lg shadow p-6">
            @if($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded text-red-700 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('super-admin.consultorios.update', $tenant) }}">
                @csrf @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug (ID)</label>
                    <input type="text" value="{{ $tenant->id }}" disabled
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 bg-gray-50 text-gray-400">
                    <p class="text-xs text-gray-500 mt-1">El slug no puede modificarse.</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nombre del consultorio <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nombre" value="{{ old('nombre', $tenant->nombre) }}" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email de contacto</label>
                    <input type="email" name="email" value="{{ old('email', $tenant->email) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="hidden" name="activo" value="0">
                        <input type="checkbox" name="activo" value="1" {{ $tenant->activo ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 rounded">
                        <span class="text-sm font-medium text-gray-700">Consultorio activo</span>
                    </label>
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition">
                        Guardar cambios
                    </button>
                    <a href="{{ route('super-admin.consultorios.index') }}"
                       class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-6 rounded-lg transition">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
