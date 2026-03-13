@props([
    'name',
    'options'     => [],
    'optionValue' => 'id',
    'optionLabel' => 'name',
    'placeholder' => 'Seleccione...',
    'selected'    => null,
    'label'       => null,
    'wireModel'   => null,
])

@php
    $optsList = collect($options)->map(fn($o) => [
        'value' => (string) data_get($o, $optionValue),
        'label' => (string) data_get($o, $optionLabel),
    ])->values()->toArray();

    $initValue = $selected !== null ? (string) $selected : null;
    $found      = $initValue ? collect($optsList)->firstWhere('value', $initValue) : null;
    $initLabel  = $found ? $found['label'] : '';
@endphp

<div
    x-data="{
        open: false,
        search: '',
        value: @js($initValue),
        label: @js($initLabel),
        options: @js($optsList),
        get filtered() {
            if (!this.search) return this.options;
            const q = this.search.toLowerCase();
            return this.options.filter(o => o.label.toLowerCase().includes(q));
        },
        select(opt) {
            this.value = opt.value;
            this.label = opt.label;
            this.search = '';
            this.open = false;
            @if ($wireModel)
                $wire.set('{{ $wireModel }}', opt.value);
            @endif
        },
        init() {
            this.$watch('open', v => {
                if (v) this.$nextTick(() => this.$refs.si?.focus());
            });
        }
    }"
    x-on:click.outside="open = false"
    class="relative">

    @if ($label)
        <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
    @endif

    <input type="hidden" name="{{ $name }}" x-bind:value="value">

    <button
        type="button"
        @click="open = !open"
        class="w-full flex items-center justify-between gap-2 border border-gray-300 rounded-lg px-3 py-2 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
        :class="value ? 'text-gray-900' : 'text-gray-400'">
        <span class="truncate" x-text="label || '{{ $placeholder }}'"></span>
        <i class="fa-solid fa-chevron-down text-xs text-gray-400 shrink-0 transition-transform duration-150"
           :class="{ 'rotate-180': open }"></i>
    </button>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 -translate-y-1 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg"
        style="display:none">

        <div class="p-2 border-b border-gray-100">
            <input
                type="text"
                x-model="search"
                x-ref="si"
                placeholder="Buscar..."
                @click.stop
                class="w-full border border-gray-300 rounded-md px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <ul class="max-h-52 overflow-y-auto py-1">
            <template x-for="opt in filtered" :key="opt.value">
                <li
                    @click="select(opt)"
                    x-text="opt.label"
                    :class="value === opt.value
                        ? 'bg-blue-50 text-blue-700 font-medium'
                        : 'text-gray-700 hover:bg-gray-50'"
                    class="px-3 py-2 text-sm cursor-pointer">
                </li>
            </template>
            <li x-show="filtered.length === 0"
                class="px-3 py-2 text-sm text-center text-gray-400">
                Sin resultados.
            </li>
        </ul>
    </div>
</div>
