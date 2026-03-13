<div class="flex items-center space-x-2">
    <x-wire-button href="{{ route('admin.catalogos.coberturas-sociales.edit', $socialCoverage) }}" xs class="bg-blue-600 hover:bg-blue-700 focus:ring-blue-500">
        <i class="fa fa-pen-to-square"></i>
    </x-wire-button>
    <form action="{{ route('admin.catalogos.coberturas-sociales.destroy', $socialCoverage) }}" method="POST" class="delete-form">
        @csrf
        @method('DELETE')
        <x-wire-button type="submit" xs class="bg-red-600 hover:bg-red-700 focus:ring-red-500">
            <i class="fa fa-trash"></i>
        </x-wire-button>
    </form>
</div>
