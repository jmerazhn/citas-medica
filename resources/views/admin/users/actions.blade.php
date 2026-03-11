<div class="flex items-center space-x-2">
    @if ($user->hasRole('Doctor'))
        <x-wire-button href="{{ route('admin.doctors.schedules.index', $user) }}" xs class="bg-green-600 hover:bg-green-700 focus:ring-green-500">
            <i class="fa fa-calendar-days"></i>
        </x-wire-button>
    @endif

    <x-wire-button href="{{ route('admin.users.edit', $user) }}" xs class="bg-blue-600 hover:bg-blue-700 focus:ring-blue-500">
        <i class="fa fa-pen-to-square"></i>
    </x-wire-button>

    <form action="{{ route('admin.users.destroy', $user) }}" 
        method="POST"
        class="delete-form">
        @csrf
        @method('DELETE')
        <x-wire-button type="submit" xs class="bg-red-600 hover:bg-red-700 focus:ring-red-500">
            <i class="fa fa-trash"></i>
        </x-wire-button>
    </form>

</div>