@props(['title' => config('app.name', 'Laravel'), 'breadcrumbs' => []])


<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- Font Awesome Kit --}}
    <script src="https://kit.fontawesome.com/80c092f0d1.js" crossorigin="anonymous"></script>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Wireui --}}
    <wireui:scripts />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased">

    @include('layouts.includes.admin.navigation')

    @include('layouts.includes.admin.sidebar')





    <div class="p-4 sm:ml-64">
        <div class="mt-14 flex items-center">
            @include('layouts.includes.admin.breadcrumb')
            @isset($action)
                <div class="ml-auto">
                    {{ $action ?: '' }}
                </div>
            @endisset
        </div>
        {{ $slot }}
    </div>


    @stack('modals')

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>
    <script>
        Livewire.on('swal', (data) => {
            Swal.fire(data[0]);
        });
    </script>

    @if (session('swal'))
        <script>
            // Swal.fire('SweetAlert2 is ready to use!');

            Swal.fire(@json(session('swal')));
        </script>
    @endif

    <script>
        forms = document.querySelectorAll('.delete-form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: "Esta seguro?",
                    text: "No podra revertir esta accion!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, eliminar!",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
</body>

</html>
