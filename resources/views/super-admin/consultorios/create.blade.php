<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Consultorio — Super Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-white shadow px-6 py-4 flex items-center gap-4">
        <a href="{{ route('super-admin.consultorios.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Consultorios</a>
        <h1 class="text-lg font-bold text-gray-800">Nuevo Consultorio</h1>
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

            <form method="POST" action="{{ route('super-admin.consultorios.store') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nombre del consultorio <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" required
                        placeholder="Ej: Consultorio Pediátrico García"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Slug (identificador URL) <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center gap-2">
                        <span class="text-gray-400 text-sm">citas.hn/</span>
                        <input type="text" name="slug" value="{{ old('slug') }}" required
                            placeholder="drgarcia"
                            pattern="[a-z0-9\-_]+"
                            class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <span class="text-gray-400 text-sm">/admin</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Solo letras minúsculas, números y guiones. No se puede cambiar después.</p>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email de contacto</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        placeholder="drgarcia@ejemplo.com"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition">
                        Crear consultorio
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
