<x-admin-layout title="Pacientes" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Pacientes', 'href' => route('admin.patients.index')],
    ['name' => $patient->full_name],
]">

    {{-- Header --}}
    <x-wire-card class="mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $patient->full_name }}</h2>
                <div class="mt-1 flex flex-wrap gap-x-4 gap-y-1 text-sm text-gray-600">
                    @if ($patient->fecha_nacimiento)
                        <span><i class="fa-solid fa-calendar-days fa-fw"></i> {{ $patient->fecha_nacimiento->format('d/m/Y') }} ({{ $patient->fecha_nacimiento->age }} años)</span>
                    @endif
                    @if ($patient->sexo)
                        <span><i class="fa-solid fa-venus-mars fa-fw"></i> {{ $patient->sexo === 'M' ? 'Masculino' : 'Femenino' }}</span>
                    @endif
                    @if ($patient->bloodType)
                        <span><i class="fa-solid fa-droplet fa-fw"></i> {{ $patient->bloodType->name }}</span>
                    @endif
                    @if ($patient->socialCoverage)
                        <span><i class="fa-solid fa-shield-halved fa-fw"></i> {{ $patient->socialCoverage->name }}</span>
                    @endif
                    @if ($patient->telefono)
                        <span><i class="fa-solid fa-phone fa-fw"></i> {{ $patient->telefono }}</span>
                    @endif
                </div>
            </div>
            <div class="flex space-x-2">
                <x-wire-button outline gray href="{{ route('admin.patients.edit', $patient) }}">
                    <i class="fa-solid fa-pen-to-square"></i> Editar
                </x-wire-button>
            </div>
        </div>
    </x-wire-card>

    {{-- Tabs --}}
    <div x-data="{ tab: window.location.hash || '#consultas' }" x-init="$watch('tab', v => window.location.hash = v)">

        <div class="border-b border-gray-200 mb-4">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500">
                @foreach([
                    ['#consultas',  'fa-stethoscope',    'Consultas'],
                    ['#vacunas',    'fa-syringe',        'Vacunas'],
                    ['#patologias', 'fa-notes-medical',  'Patologías'],
                    ['#embarazos',  'fa-person-pregnant','Embarazos'],
                    ['#partos',     'fa-baby',           'Partos'],
                ] as [$id, $icon, $label])
                <li class="me-2">
                    <button @click="tab = '{{ $id }}'"
                        :class="tab === '{{ $id }}' ? 'text-blue-600 border-blue-600 border-b-2 active' : 'hover:text-gray-600 hover:border-gray-300'"
                        class="inline-flex items-center justify-center gap-2 p-4 rounded-t-lg">
                        <i class="fa-solid {{ $icon }}"></i> {{ $label }}
                    </button>
                </li>
                @endforeach
            </ul>
        </div>

        {{-- Consultas --}}
        <div x-show="tab === '#consultas'">
            <div class="flex justify-end mb-3">
                <x-wire-button blue href="{{ route('admin.patients.consultas.create', $patient) }}">
                    <i class="fa fa-plus"></i> Nueva Consulta
                </x-wire-button>
            </div>
            @forelse ($patient->consultas as $consulta)
            <x-wire-card class="mb-3">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-1">
                            <span class="font-semibold text-gray-800">{{ $consulta->fecha?->format('d/m/Y') }}</span>
                            @if ($consulta->motivoConsulta)
                                <span class="text-sm text-gray-600">— {{ $consulta->motivoConsulta->nombre }}</span>
                            @endif
                            @if ($consulta->motivo_detalle)
                                <span class="text-sm text-gray-500">{{ $consulta->motivo_detalle }}</span>
                            @endif
                        </div>
                        <div class="flex flex-wrap gap-3 text-sm text-gray-600 mb-2">
                            @if ($consulta->peso) <span><i class="fa-solid fa-weight-scale fa-fw"></i> {{ $consulta->peso }} kg</span> @endif
                            @if ($consulta->talla) <span><i class="fa-solid fa-ruler-vertical fa-fw"></i> {{ $consulta->talla }} cm</span> @endif
                            @if ($consulta->temperatura) <span><i class="fa-solid fa-temperature-half fa-fw"></i> {{ $consulta->temperatura }} °C</span> @endif
                            @if ($consulta->fc) <span>FC: {{ $consulta->fc }} lpm</span> @endif
                            @if ($consulta->fr) <span>FR: {{ $consulta->fr }} rpm</span> @endif
                            @if ($consulta->spo2) <span>SpO2: {{ $consulta->spo2 }}%</span> @endif
                        </div>
                        @if ($consulta->diagnostico)
                            <p class="text-sm"><span class="font-medium">Diagnóstico:</span> {{ $consulta->diagnostico }}</p>
                        @endif
                        @if ($consulta->tratamiento)
                            <p class="text-sm"><span class="font-medium">Tratamiento:</span> {{ $consulta->tratamiento }}</p>
                        @endif
                    </div>
                    <div class="flex items-center space-x-2 ml-4">
                        <x-wire-button href="{{ route('admin.consultas.edit', $consulta) }}" xs class="bg-blue-600 hover:bg-blue-700 focus:ring-blue-500">
                            <i class="fa fa-pen-to-square"></i>
                        </x-wire-button>
                        <form action="{{ route('admin.consultas.destroy', $consulta) }}" method="POST" class="delete-form">
                            @csrf @method('DELETE')
                            <x-wire-button type="submit" xs class="bg-red-600 hover:bg-red-700 focus:ring-red-500">
                                <i class="fa fa-trash"></i>
                            </x-wire-button>
                        </form>
                    </div>
                </div>
            </x-wire-card>
            @empty
            <p class="text-gray-500 text-sm text-center py-8">No hay consultas registradas.</p>
            @endforelse
        </div>

        {{-- Vacunas --}}
        <div x-show="tab === '#vacunas'">
            <div class="flex justify-end mb-3">
                <x-wire-button blue href="{{ route('admin.patients.vacunas.create', $patient) }}">
                    <i class="fa fa-plus"></i> Registrar Vacuna
                </x-wire-button>
            </div>
            @forelse ($patient->vacunas as $vacuna)
            <x-wire-card class="mb-3">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-1">
                            <span class="font-semibold text-gray-800">{{ $vacuna->vacuna }}</span>
                            <span class="text-sm text-gray-600">{{ $vacuna->fecha_aplicacion?->format('d/m/Y') }}</span>
                            @if ($vacuna->planVacunacion)
                                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">{{ $vacuna->planVacunacion->nombre }}</span>
                            @endif
                        </div>
                        <div class="flex flex-wrap gap-3 text-sm text-gray-600">
                            @if ($vacuna->dosis) <span>Dosis: {{ $vacuna->dosis }}</span> @endif
                            @if ($vacuna->lote) <span>Lote: {{ $vacuna->lote }}</span> @endif
                        </div>
                        @if ($vacuna->notas) <p class="text-sm text-gray-500 mt-1">{{ $vacuna->notas }}</p> @endif
                    </div>
                    <div class="flex items-center space-x-2 ml-4">
                        <x-wire-button href="{{ route('admin.vacunas.edit', $vacuna) }}" xs class="bg-blue-600 hover:bg-blue-700 focus:ring-blue-500">
                            <i class="fa fa-pen-to-square"></i>
                        </x-wire-button>
                        <form action="{{ route('admin.vacunas.destroy', $vacuna) }}" method="POST" class="delete-form">
                            @csrf @method('DELETE')
                            <x-wire-button type="submit" xs class="bg-red-600 hover:bg-red-700 focus:ring-red-500">
                                <i class="fa fa-trash"></i>
                            </x-wire-button>
                        </form>
                    </div>
                </div>
            </x-wire-card>
            @empty
            <p class="text-gray-500 text-sm text-center py-8">No hay vacunas registradas.</p>
            @endforelse
        </div>

        {{-- Patologías --}}
        <div x-show="tab === '#patologias'">
            <div class="flex justify-end mb-3">
                <x-wire-button blue href="{{ route('admin.patients.patologias.create', $patient) }}">
                    <i class="fa fa-plus"></i> Registrar Patología
                </x-wire-button>
            </div>
            @forelse ($patient->patologias as $pp)
            <x-wire-card class="mb-3">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-1">
                            <span class="font-semibold text-gray-800">{{ $pp->patologia->nombre }}</span>
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $pp->estado === 'activa' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                {{ ucfirst($pp->estado) }}
                            </span>
                            @if ($pp->fecha_diagnostico)
                                <span class="text-sm text-gray-600">{{ $pp->fecha_diagnostico->format('d/m/Y') }}</span>
                            @endif
                        </div>
                        @if ($pp->notas) <p class="text-sm text-gray-500">{{ $pp->notas }}</p> @endif
                    </div>
                    <div class="flex items-center space-x-2 ml-4">
                        <x-wire-button href="{{ route('admin.patologias.edit', $pp) }}" xs class="bg-blue-600 hover:bg-blue-700 focus:ring-blue-500">
                            <i class="fa fa-pen-to-square"></i>
                        </x-wire-button>
                        <form action="{{ route('admin.patologias.destroy', $pp) }}" method="POST" class="delete-form">
                            @csrf @method('DELETE')
                            <x-wire-button type="submit" xs class="bg-red-600 hover:bg-red-700 focus:ring-red-500">
                                <i class="fa fa-trash"></i>
                            </x-wire-button>
                        </form>
                    </div>
                </div>
            </x-wire-card>
            @empty
            <p class="text-gray-500 text-sm text-center py-8">No hay patologías registradas.</p>
            @endforelse
        </div>

        {{-- Embarazos --}}
        <div x-show="tab === '#embarazos'">
            <div class="flex justify-end mb-3">
                <x-wire-button blue href="{{ route('admin.patients.embarazos.create', $patient) }}">
                    <i class="fa fa-plus"></i> Registrar Embarazo
                </x-wire-button>
            </div>
            @forelse ($patient->embarazos as $embarazo)
            <x-wire-card class="mb-3">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-1">
                            @if ($embarazo->numero_embarazo)
                                <span class="font-semibold text-gray-800">Embarazo #{{ $embarazo->numero_embarazo }}</span>
                            @else
                                <span class="font-semibold text-gray-800">Embarazo</span>
                            @endif
                            @if ($embarazo->semanas_gestacion)
                                <span class="text-sm text-gray-600">{{ $embarazo->semanas_gestacion }} semanas</span>
                            @endif
                        </div>
                        <div class="flex flex-wrap gap-3 text-sm text-gray-600">
                            @if ($embarazo->fecha_ultima_menstruacion) <span>FUM: {{ $embarazo->fecha_ultima_menstruacion->format('d/m/Y') }}</span> @endif
                            @if ($embarazo->fecha_probable_parto) <span>FPP: {{ $embarazo->fecha_probable_parto->format('d/m/Y') }}</span> @endif
                        </div>
                        @if ($embarazo->notas) <p class="text-sm text-gray-500 mt-1">{{ $embarazo->notas }}</p> @endif
                    </div>
                    <div class="flex items-center space-x-2 ml-4">
                        <x-wire-button href="{{ route('admin.embarazos.edit', $embarazo) }}" xs class="bg-blue-600 hover:bg-blue-700 focus:ring-blue-500">
                            <i class="fa fa-pen-to-square"></i>
                        </x-wire-button>
                        <form action="{{ route('admin.embarazos.destroy', $embarazo) }}" method="POST" class="delete-form">
                            @csrf @method('DELETE')
                            <x-wire-button type="submit" xs class="bg-red-600 hover:bg-red-700 focus:ring-red-500">
                                <i class="fa fa-trash"></i>
                            </x-wire-button>
                        </form>
                    </div>
                </div>
            </x-wire-card>
            @empty
            <p class="text-gray-500 text-sm text-center py-8">No hay embarazos registrados.</p>
            @endforelse
        </div>

        {{-- Partos --}}
        <div x-show="tab === '#partos'">
            <div class="flex justify-end mb-3">
                <x-wire-button blue href="{{ route('admin.patients.partos.create', $patient) }}">
                    <i class="fa fa-plus"></i> Registrar Parto
                </x-wire-button>
            </div>
            @forelse ($patient->partos as $parto)
            <x-wire-card class="mb-3">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-1">
                            <span class="font-semibold text-gray-800">{{ $parto->fecha_parto?->format('d/m/Y') }}</span>
                            <span class="text-xs bg-gray-100 text-gray-700 px-2 py-0.5 rounded-full">{{ ucfirst($parto->tipo_parto) }}</span>
                            @if ($parto->semanas_gestacion)
                                <span class="text-sm text-gray-600">{{ $parto->semanas_gestacion }} semanas</span>
                            @endif
                        </div>
                        <div class="flex flex-wrap gap-3 text-sm text-gray-600">
                            @if ($parto->peso_rn) <span>Peso RN: {{ $parto->peso_rn }} kg</span> @endif
                            @if ($parto->talla_rn) <span>Talla RN: {{ $parto->talla_rn }} cm</span> @endif
                            @if ($parto->apgar_1 !== null) <span>Apgar 1': {{ $parto->apgar_1 }}</span> @endif
                            @if ($parto->apgar_5 !== null) <span>Apgar 5': {{ $parto->apgar_5 }}</span> @endif
                        </div>
                        @if ($parto->complicaciones) <p class="text-sm text-gray-500 mt-1"><span class="font-medium">Complicaciones:</span> {{ $parto->complicaciones }}</p> @endif
                        @if ($parto->notas) <p class="text-sm text-gray-500">{{ $parto->notas }}</p> @endif
                    </div>
                    <div class="flex items-center space-x-2 ml-4">
                        <x-wire-button href="{{ route('admin.partos.edit', $parto) }}" xs class="bg-blue-600 hover:bg-blue-700 focus:ring-blue-500">
                            <i class="fa fa-pen-to-square"></i>
                        </x-wire-button>
                        <form action="{{ route('admin.partos.destroy', $parto) }}" method="POST" class="delete-form">
                            @csrf @method('DELETE')
                            <x-wire-button type="submit" xs class="bg-red-600 hover:bg-red-700 focus:ring-red-500">
                                <i class="fa fa-trash"></i>
                            </x-wire-button>
                        </form>
                    </div>
                </div>
            </x-wire-card>
            @empty
            <p class="text-gray-500 text-sm text-center py-8">No hay partos registrados.</p>
            @endforelse
        </div>

    </div>

</x-admin-layout>
