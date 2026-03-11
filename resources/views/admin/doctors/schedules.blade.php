<x-admin-layout
    title="Horarios"
    :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href' => route('admin.dashboard'),
        ],
        [
            'name' => 'Usuarios',
            'href' => route('admin.users.index'),
        ],
        [
            'name' => $user->name,
        ],
        [
            'name' => 'Horarios',
        ],
    ]">

    <form action="{{ route('admin.doctors.schedules.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <x-wire-card class="mb-4">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Horario semanal</h2>
                    <p class="text-sm text-gray-500">Dr. {{ $user->name }}</p>
                </div>
                <div class="flex space-x-3">
                    <x-wire-button outline gray href="{{ route('admin.users.show', $user) }}">
                        Volver
                    </x-wire-button>
                    <x-wire-button type="submit" primary>
                        <i class="fa-solid fa-check"></i>
                        Guardar Horarios
                    </x-wire-button>
                </div>
            </div>
        </x-wire-card>

        <x-wire-card>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs font-semibold text-gray-500 uppercase border-b border-gray-200">
                        <tr>
                            <th class="pb-3 pr-6">Activo</th>
                            <th class="pb-3 pr-6">Día</th>
                            <th class="pb-3 pr-6">Hora de inicio</th>
                            <th class="pb-3">Hora de fin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($days as $dayNum => $dayName)
                            @php $schedule = $schedules->get($dayNum); @endphp
                            <tr class="py-2">
                                <td class="py-3 pr-6">
                                    <input type="hidden" name="schedules[{{ $dayNum }}][day_of_week]" value="{{ $dayNum }}">
                                    <input type="checkbox"
                                        name="schedules[{{ $dayNum }}][is_active]"
                                        value="1"
                                        {{ $schedule?->is_active ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                </td>
                                <td class="py-3 pr-6 font-medium text-gray-700">{{ $dayName }}</td>
                                <td class="py-3 pr-6">
                                    <input type="time"
                                        name="schedules[{{ $dayNum }}][start_time]"
                                        value="{{ $schedule?->start_time ?? '08:00' }}"
                                        class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </td>
                                <td class="py-3">
                                    <input type="time"
                                        name="schedules[{{ $dayNum }}][end_time]"
                                        value="{{ $schedule?->end_time ?? '17:00' }}"
                                        class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-wire-card>
    </form>
</x-admin-layout>
