<div class="grid lg:grid-cols-2 gap-4">
    <div>
        <x-searchable-select
            name="doctor_id"
            label="Doctor"
            :options="$doctors"
            option-value="id"
            option-label="name"
            placeholder="Seleccione un doctor"
            :selected="$doctorId"
            wire-model="doctorId"
        />
        @error('doctor_id')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
        <input type="date" wire:model.live="date" name="date"
            value="{{ old('date', $initialDate ?? '') }}"
            min="{{ date('Y-m-d') }}"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        @error('date')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="lg:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">Hora disponible</label>

        @if ($doctorId && $date)
            @if (count($availableSlots) > 0)
                <div class="flex flex-wrap gap-2" x-data="{ selected: '' }">
                    <input type="hidden" name="time" x-bind:value="selected">
                    @foreach ($availableSlots as $slot)
                        <button type="button"
                            x-on:click="selected = '{{ $slot }}'"
                            x-bind:class="selected === '{{ $slot }}' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'"
                            class="px-4 py-2 rounded-lg border text-sm font-medium transition-colors">
                            {{ $slot }}
                        </button>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-amber-600 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2">
                    No hay horarios disponibles para este doctor en la fecha seleccionada.
                </p>
            @endif
        @else
            <p class="text-sm text-gray-500 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2">
                Seleccione un doctor y una fecha para ver los horarios disponibles.
            </p>
        @endif

        @error('time')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>
