@php
$chipColors = [
    'pending'   => 'bg-yellow-100 text-yellow-800',
    'confirmed' => 'bg-blue-100 text-blue-800',
    'completed' => 'bg-green-100 text-green-800',
    'cancelled' => 'bg-gray-100 text-gray-400',
];

$borderColors = [
    'pending'   => 'border-l-yellow-400 bg-yellow-50',
    'confirmed' => 'border-l-blue-400 bg-blue-50',
    'completed' => 'border-l-green-400 bg-green-50',
    'cancelled' => 'border-l-red-300 bg-red-50 opacity-60',
];

$dayNames = ['Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa', 'Do'];

$views = [
    'list'    => ['icon' => 'fa-list',          'label' => 'Lista'],
    'daily'   => ['icon' => 'fa-calendar-day',  'label' => 'Diario'],
    'weekly'  => ['icon' => 'fa-calendar-week', 'label' => 'Semanal'],
    'monthly' => ['icon' => 'fa-calendar',      'label' => 'Mensual'],
];
@endphp

<div>
    {{-- Toolbar --}}
    <x-wire-card class="mb-4">
        <div class="flex flex-wrap justify-between items-center gap-3">

            {{-- View switcher --}}
            <div class="inline-flex rounded-lg border border-gray-300 divide-x divide-gray-300 overflow-hidden text-sm">
                @foreach ($views as $key => $cfg)
                    <button
                        wire:click="setView('{{ $key }}')"
                        class="px-4 py-2 transition {{ $calendarView === $key ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                        <i class="fa-solid {{ $cfg['icon'] }} fa-fw"></i>
                        <span class="hidden sm:inline ml-1">{{ $cfg['label'] }}</span>
                    </button>
                @endforeach
            </div>

            {{-- Navigation (calendar views only) --}}
            @if ($calendarView !== 'list')
                <div class="flex items-center gap-2">
                    <button wire:click="previous"
                        class="p-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition">
                        <i class="fa-solid fa-chevron-left text-sm"></i>
                    </button>
                    <button wire:click="today"
                        class="px-3 py-2 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition">
                        Hoy
                    </button>
                    <button wire:click="next"
                        class="p-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition">
                        <i class="fa-solid fa-chevron-right text-sm"></i>
                    </button>
                    <span class="text-sm font-semibold text-gray-700 capitalize">
                        {{ $periodLabel ?? '' }}
                    </span>
                </div>
            @endif
        </div>
    </x-wire-card>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LIST VIEW — delegates to existing rappasoft datatable --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    @if ($calendarView === 'list')
        @livewire('admin.datatables.appointment-table')
    @endif

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- DAILY VIEW --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    @if ($calendarView === 'daily')
        <x-wire-card>
            @if ($appointments->isEmpty())
                <div class="py-16 text-center text-gray-400">
                    <i class="fa-solid fa-calendar-xmark fa-2x mb-3 block"></i>
                    <p>No hay citas para este día.</p>
                </div>
            @else
                <div class="space-y-2">
                    @foreach ($appointments as $appointment)
                        <a href="{{ route('admin.appointments.show', $appointment) }}"
                            class="flex items-start gap-4 border-l-4 rounded-r-lg p-4 hover:brightness-95 transition
                                {{ $borderColors[$appointment->status] ?? 'border-l-gray-300 bg-gray-50' }}">
                            <div class="w-16 shrink-0 text-center">
                                <p class="text-lg font-bold text-gray-800 leading-none">
                                    {{ $appointment->scheduled_at->format('H:i') }}
                                </p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $appointment->duration }} min</p>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 truncate">
                                    {{ $appointment->patient?->full_name }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    <i class="fa-solid fa-user-doctor fa-fw"></i>
                                    {{ $appointment->doctor->name }}
                                </p>
                                @if ($appointment->motivoConsulta)
                                    <p class="text-sm text-gray-500">
                                        <i class="fa-solid fa-stethoscope fa-fw"></i>
                                        {{ $appointment->motivoConsulta->nombre }}
                                    </p>
                                @endif
                            </div>
                            <div class="shrink-0 pt-1">
                                @include('admin.appointments.status-badge', ['status' => $appointment->status])
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </x-wire-card>
    @endif

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- WEEKLY VIEW --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    @if ($calendarView === 'weekly')
        <div class="overflow-x-auto">
            <div class="min-w-[700px]">
                {{-- Day headers --}}
                <div class="grid grid-cols-7 gap-2 mb-2">
                    @foreach ($days as $i => $day)
                        <div class="text-center">
                            <p class="text-xs font-semibold text-gray-500 uppercase">{{ $dayNames[$i] }}</p>
                            <button wire:click="goToDay('{{ $day->format('Y-m-d') }}')"
                                class="mt-1 w-8 h-8 rounded-full text-sm font-bold mx-auto flex items-center justify-center transition
                                    {{ $day->toDateString() === $today
                                        ? 'bg-blue-600 text-white'
                                        : 'text-gray-700 hover:bg-gray-100' }}">
                                {{ $day->format('j') }}
                            </button>
                        </div>
                    @endforeach
                </div>

                {{-- Day columns --}}
                <div class="grid grid-cols-7 gap-2">
                    @foreach ($days as $day)
                        @php $dayAppts = $byDate->get($day->format('Y-m-d'), collect()); @endphp
                        <div class="border border-gray-200 rounded-lg p-2 min-h-[140px] bg-white space-y-1
                            {{ $day->toDateString() === $today ? 'ring-1 ring-blue-300' : '' }}">
                            @forelse ($dayAppts as $appointment)
                                <a href="{{ route('admin.appointments.show', $appointment) }}"
                                    class="block text-xs px-2 py-1 rounded truncate hover:opacity-80 transition
                                        {{ $chipColors[$appointment->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    <span class="font-semibold">{{ $appointment->scheduled_at->format('H:i') }}</span>
                                    {{ $appointment->patient?->full_name }}
                                </a>
                            @empty
                                <p class="text-xs text-gray-300 text-center pt-4">—</p>
                            @endforelse
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- MONTHLY VIEW --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    @if ($calendarView === 'monthly')
        <div class="overflow-x-auto">
            <div class="min-w-[700px]">
                {{-- Day name headers --}}
                <div class="grid grid-cols-7 mb-1">
                    @foreach ($dayNames as $name)
                        <div class="py-2 text-center text-xs font-semibold text-gray-500 uppercase">
                            {{ $name }}
                        </div>
                    @endforeach
                </div>

                {{-- Calendar grid --}}
                <div class="space-y-1">
                    @foreach ($weeks as $week)
                        <div class="grid grid-cols-7 gap-1">
                            @foreach ($week as $day)
                                @php
                                    $isCurrentMonth = $day->month === $monthStart->month;
                                    $isToday        = $day->toDateString() === $today;
                                    $dayAppts       = $byDate->get($day->format('Y-m-d'), collect());
                                    $extra          = max(0, $dayAppts->count() - 3);
                                @endphp
                                <div class="border rounded-lg p-1.5 min-h-[90px] flex flex-col
                                    {{ $isToday
                                        ? 'ring-1 ring-blue-400 bg-blue-50 border-blue-200'
                                        : ($isCurrentMonth ? 'bg-white border-gray-200' : 'bg-gray-50 border-gray-100 opacity-50') }}">

                                    <button wire:click="goToDay('{{ $day->format('Y-m-d') }}')"
                                        class="self-start mb-1 w-6 h-6 text-xs font-bold flex items-center justify-center rounded-full transition
                                            {{ $isToday
                                                ? 'bg-blue-600 text-white'
                                                : 'text-gray-700 hover:bg-gray-200' }}">
                                        {{ $day->format('j') }}
                                    </button>

                                    <div class="space-y-0.5 flex-1">
                                        @foreach ($dayAppts->take(3) as $appointment)
                                            <a href="{{ route('admin.appointments.show', $appointment) }}"
                                                class="block text-[10px] leading-tight px-1 py-0.5 rounded truncate hover:opacity-75 transition
                                                    {{ $chipColors[$appointment->status] ?? 'bg-gray-100 text-gray-700' }}">
                                                <span class="font-semibold">{{ $appointment->scheduled_at->format('H:i') }}</span>
                                                {{ $appointment->patient?->full_name }}
                                            </a>
                                        @endforeach

                                        @if ($extra > 0)
                                            <button wire:click="goToDay('{{ $day->format('Y-m-d') }}')"
                                                class="block text-[10px] text-blue-600 hover:underline px-1 font-medium">
                                                +{{ $extra }} más
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
