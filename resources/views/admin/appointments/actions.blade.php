<div class="flex items-center space-x-2">
    <x-wire-button href="{{ route('admin.appointments.show', $appointment) }}" xs class="bg-gray-600 hover:bg-gray-700 focus:ring-gray-500">
        <i class="fa fa-eye"></i>
    </x-wire-button>

    <x-wire-button href="{{ route('admin.appointments.edit', $appointment) }}" xs class="bg-blue-600 hover:bg-blue-700 focus:ring-blue-500">
        <i class="fa fa-pen-to-square"></i>
    </x-wire-button>

    @if ($appointment->status === 'pending')
        <form method="POST" action="{{ route('admin.appointments.confirm', $appointment) }}" class="inline">
            @csrf
            @method('PATCH')
            <x-wire-button type="submit" xs class="bg-green-600 hover:bg-green-700 focus:ring-green-500">
                <i class="fa fa-check"></i>
            </x-wire-button>
        </form>
    @endif
</div>
