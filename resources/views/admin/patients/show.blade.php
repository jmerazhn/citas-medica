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
    <div x-data="{ tab: window.location.hash || '#citas' }" x-init="$watch('tab', v => window.location.hash = v)">

        <div class="border-b border-gray-200 mb-4">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500">
                @foreach([
                    ['#citas',      'fa-calendar-check', 'Citas'],
                    ['#vacunas',    'fa-syringe',        'Vacunas'],
                    ['#patologias', 'fa-notes-medical',  'Patologías'],
                    ['#perinatal',  'fa-baby',           'Antecedentes Perinatales'],
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

        {{-- Citas --}}
        <div x-show="tab === '#citas'">
            @php
                $statusColors = [
                    'pending'   => 'border-l-yellow-400 bg-yellow-50',
                    'confirmed' => 'border-l-blue-400 bg-blue-50',
                    'completed' => 'border-l-green-400 bg-green-50',
                    'cancelled' => 'border-l-red-300 bg-red-50',
                ];
                $statusLabels = [
                    'pending'   => 'Pendiente',
                    'confirmed' => 'Confirmada',
                    'completed' => 'Completada',
                    'cancelled' => 'Cancelada',
                ];
                $statusBadge = [
                    'pending'   => 'bg-yellow-100 text-yellow-800',
                    'confirmed' => 'bg-blue-100 text-blue-800',
                    'completed' => 'bg-green-100 text-green-800',
                    'cancelled' => 'bg-red-100 text-red-700',
                ];
            @endphp

            {{-- Gráficas de crecimiento --}}
            <div class="grid sm:grid-cols-2 gap-3 mb-4">
                @include('admin.partials.growth-chart', ['patient' => $patient, 'tipo' => 'peso'])
                @include('admin.partials.growth-chart', ['patient' => $patient, 'tipo' => 'talla'])
                @include('admin.partials.growth-chart', ['patient' => $patient, 'tipo' => 'perimetro_cefalico'])
                @include('admin.partials.growth-chart', ['patient' => $patient, 'tipo' => 'imc'])
            </div>

            @forelse ($patient->appointments as $appt)
            <div x-data="{ open: false }" class="mb-3">
                {{-- Cabecera de la cita --}}
                <div
                    @click="open = !open"
                    class="border-l-4 rounded-r-lg p-4 cursor-pointer select-none hover:brightness-95 transition
                        {{ $statusColors[$appt->status] ?? 'border-l-gray-300 bg-gray-50' }}">

                    <div class="flex flex-wrap justify-between items-center gap-2">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="font-semibold text-gray-800">
                                {{ $appt->scheduled_at->format('d/m/Y H:i') }}
                            </span>
                            <span class="text-sm text-gray-600">
                                <i class="fa-solid fa-user-doctor fa-fw"></i> {{ $appt->doctor->name }}
                            </span>
                            @if ($appt->motivoConsulta)
                                <span class="text-sm text-gray-600">
                                    <i class="fa-solid fa-stethoscope fa-fw"></i> {{ $appt->motivoConsulta->nombre }}
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                                {{ $statusBadge[$appt->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $statusLabels[$appt->status] ?? $appt->status }}
                            </span>
                            @if ($appt->atencion)
                                <span class="text-xs bg-purple-100 text-purple-700 px-2 py-0.5 rounded-full font-semibold">
                                    <i class="fa-solid fa-notes-medical fa-fw"></i> Con atención
                                </span>
                            @endif
                            <i class="fa-solid fa-chevron-down text-gray-400 text-xs transition-transform duration-200"
                               :class="{ 'rotate-180': open }"></i>
                        </div>
                    </div>
                </div>

                {{-- Detalle expandible --}}
                <div x-show="open" x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="border border-t-0 border-gray-200 rounded-b-lg bg-white p-4 space-y-4"
                     style="display:none">

                    @if ($appt->atencion)
                        @php $a = $appt->atencion; @endphp

                        {{-- Crecimiento y Signos Vitales --}}
                        @if ($a->peso || $a->altura || $a->pc || $a->imc || $a->temperatura || $a->fc || $a->fr || $a->presion_arterial)
                        <div class="grid sm:grid-cols-2 gap-4">
                            @if ($a->peso || $a->altura || $a->pc || $a->imc)
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Crecimiento</p>
                                <dl class="grid grid-cols-2 gap-x-3 gap-y-1 text-sm">
                                    @if ($a->peso)    <div><dt class="text-gray-500">Peso</dt>    <dd>{{ $a->peso }}</dd></div> @endif
                                    @if ($a->altura)  <div><dt class="text-gray-500">Talla</dt>   <dd>{{ $a->altura }}</dd></div> @endif
                                    @if ($a->pc)      <div><dt class="text-gray-500">P.C.</dt>    <dd>{{ $a->pc }}</dd></div> @endif
                                    @if ($a->imc)     <div><dt class="text-gray-500">I.M.C.</dt>  <dd>{{ $a->imc }}</dd></div> @endif
                                </dl>
                            </div>
                            @endif
                            @if ($a->temperatura || $a->fc || $a->fr || $a->presion_arterial)
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Signos Vitales</p>
                                <dl class="grid grid-cols-2 gap-x-3 gap-y-1 text-sm">
                                    @if ($a->temperatura)     <div><dt class="text-gray-500">Temp.</dt> <dd>{{ $a->temperatura }}</dd></div> @endif
                                    @if ($a->fc)              <div><dt class="text-gray-500">F.C.</dt>  <dd>{{ $a->fc }}</dd></div> @endif
                                    @if ($a->fr)              <div><dt class="text-gray-500">F.R.</dt>  <dd>{{ $a->fr }}</dd></div> @endif
                                    @if ($a->presion_arterial)<div><dt class="text-gray-500">P.A.</dt>  <dd>{{ $a->presion_arterial }}</dd></div> @endif
                                </dl>
                            </div>
                            @endif
                        </div>
                        @endif

                        {{-- Clínico --}}
                        @if ($a->sintomatologia)
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Sintomatología</p>
                            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $a->sintomatologia }}</p>
                        </div>
                        @endif

                        @if ($a->diagnostico_posible || $a->diagnostico_confirmado)
                        <div class="grid sm:grid-cols-2 gap-4">
                            @if ($a->diagnostico_posible)
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Diagnóstico Posible</p>
                                <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $a->diagnostico_posible }}</p>
                            </div>
                            @endif
                            @if ($a->diagnostico_confirmado)
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Diagnóstico Confirmado</p>
                                <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $a->diagnostico_confirmado }}</p>
                            </div>
                            @endif
                        </div>
                        @endif

                        @if ($a->medicacion_indicada)
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Medicación Indicada</p>
                            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $a->medicacion_indicada }}</p>
                        </div>
                        @endif

                        @if ($a->estudiosOrdenados->isNotEmpty())
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Estudios Ordenados</p>
                            <div class="space-y-1.5">
                                @foreach ($a->estudiosOrdenados as $estudio)
                                <div class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50">
                                    <span class="font-medium text-gray-800">{{ $estudio->estudio }}</span>
                                    @if ($estudio->resultado)
                                        <span class="text-gray-500 ml-2">— {{ $estudio->resultado }}</span>
                                    @else
                                        <span class="text-gray-400 ml-2 italic">Sin resultado</span>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if ($a->notas)
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Notas</p>
                            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $a->notas }}</p>
                        </div>
                        @endif

                        <div class="pt-1 flex justify-end">
                            <a href="{{ route('admin.atenciones.edit', $a) }}"
                               class="text-xs text-purple-600 hover:underline">
                                <i class="fa-solid fa-pen-to-square fa-fw"></i> Editar atención
                            </a>
                        </div>

                    @else
                        <p class="text-sm text-gray-400 italic text-center py-2">
                            Esta cita no tiene atención registrada.
                        </p>
                        @if (in_array($appt->status, ['confirmed', 'completed']))
                        <div class="flex justify-center">
                            <a href="{{ route('admin.appointments.atencion.create', $appt) }}"
                               class="text-xs text-purple-600 hover:underline">
                                <i class="fa-solid fa-plus fa-fw"></i> Registrar atención
                            </a>
                        </div>
                        @endif
                    @endif
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-sm text-center py-8">No hay citas registradas para este paciente.</p>
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

        {{-- Antecedentes Perinatales --}}
        <div x-show="tab === '#perinatal'">
            @php
                $embarazo = $patient->embarazos->first();
                $parto    = $patient->partos->first();
            @endphp

            <div class="grid lg:grid-cols-2 gap-6">

                {{-- Embarazo --}}
                <x-wire-card>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-gray-700">
                            <i class="fa-solid fa-person-pregnant fa-fw text-pink-400"></i> Datos del Embarazo
                        </h3>
                        @if ($embarazo)
                            <div class="flex gap-2">
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
                        @else
                            <x-wire-button blue xs href="{{ route('admin.patients.embarazos.create', $patient) }}">
                                <i class="fa fa-plus"></i> Registrar
                            </x-wire-button>
                        @endif
                    </div>

                    @if ($embarazo)
                        <dl class="space-y-2 text-sm">
                            @if ($embarazo->numero_embarazo)
                                <div class="flex gap-2"><dt class="text-gray-500 w-40">No. de Gestación</dt><dd class="text-gray-900">{{ $embarazo->numero_embarazo }}</dd></div>
                            @endif
                            @if ($embarazo->obstetra)
                                <div class="flex gap-2"><dt class="text-gray-500 w-40">Obstetra</dt><dd class="text-gray-900">{{ $embarazo->obstetra }}</dd></div>
                            @endif
                            @if ($embarazo->semanas_gestacion)
                                <div class="flex gap-2"><dt class="text-gray-500 w-40">Duración</dt><dd class="text-gray-900">{{ $embarazo->semanas_gestacion }} semanas</dd></div>
                            @endif
                            @php
                                $complicaciones = array_filter([
                                    $embarazo->diabetes     ? 'Diabetes'      : null,
                                    $embarazo->hipertension ? 'Hipertensión'  : null,
                                    $embarazo->traumatismo  ? 'Traumatismo'   : null,
                                ]);
                            @endphp
                            <div class="flex gap-2">
                                <dt class="text-gray-500 w-40">Complicaciones</dt>
                                <dd class="text-gray-900">{{ $complicaciones ? implode(', ', $complicaciones) : 'Ninguna' }}</dd>
                            </div>
                            <div class="flex gap-2"><dt class="text-gray-500 w-40">Infecciones</dt><dd class="text-gray-900">{{ $embarazo->infecciones ? 'Sí' : 'No' }}</dd></div>
                            <div class="flex gap-2"><dt class="text-gray-500 w-40">Asma</dt><dd class="text-gray-900">{{ $embarazo->asma ? 'Sí' : 'No' }}</dd></div>
                            @if ($embarazo->medicacion)
                                <div><dt class="text-gray-500 mb-1">Medicación</dt><dd class="text-gray-700 whitespace-pre-wrap">{{ $embarazo->medicacion }}</dd></div>
                            @endif
                            @if ($embarazo->observaciones)
                                <div><dt class="text-gray-500 mb-1">Observaciones</dt><dd class="text-gray-700 whitespace-pre-wrap">{{ $embarazo->observaciones }}</dd></div>
                            @endif
                        </dl>
                    @else
                        <p class="text-sm text-gray-400 text-center py-4">Sin datos registrados.</p>
                    @endif
                </x-wire-card>

                {{-- Parto --}}
                <x-wire-card>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-gray-700">
                            <i class="fa-solid fa-baby fa-fw text-blue-400"></i> Datos del Parto / Nacimiento
                        </h3>
                        @if ($parto)
                            <div class="flex gap-2">
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
                        @else
                            <x-wire-button blue xs href="{{ route('admin.patients.partos.create', $patient) }}">
                                <i class="fa fa-plus"></i> Registrar
                            </x-wire-button>
                        @endif
                    </div>

                    @if ($parto)
                        @php
                            $posiciones  = ['cefalica' => 'Cefálica', 'podalica' => 'Podálica'];
                            $partoTipos  = ['eutocico' => 'Eutócico', 'distocico' => 'Distócico'];
                            $anestesias  = ['no' => 'No', 'raquidea' => 'Raquídea', 'peridural' => 'Peridural', 'total' => 'Total'];
                        @endphp

                        {{-- Datos del parto --}}
                        <dl class="space-y-2 text-sm mb-4">
                            @if ($parto->fecha_parto)
                                <div class="flex gap-2"><dt class="text-gray-500 w-36">Fecha</dt><dd class="text-gray-900">{{ $parto->fecha_parto->format('d/m/Y') }}</dd></div>
                            @endif
                            @if ($parto->lugar)
                                <div class="flex gap-2"><dt class="text-gray-500 w-36">Lugar</dt><dd class="text-gray-900">{{ $parto->lugar }}</dd></div>
                            @endif
                            <div class="flex gap-2"><dt class="text-gray-500 w-36">Cesárea</dt><dd class="text-gray-900">{{ $parto->cesarea ? 'Sí' : 'No' }}</dd></div>
                            @if ($parto->cesarea && $parto->motivo_cesarea)
                                <div><dt class="text-gray-500 mb-1">Motivo cesárea</dt><dd class="text-gray-700">{{ $parto->motivo_cesarea }}</dd></div>
                            @endif
                            @if ($parto->posicion)
                                <div class="flex gap-2"><dt class="text-gray-500 w-36">Posición</dt><dd class="text-gray-900">{{ $posiciones[$parto->posicion] ?? $parto->posicion }}</dd></div>
                            @endif
                            @if ($parto->parto_tipo)
                                <div class="flex gap-2"><dt class="text-gray-500 w-36">Parto</dt><dd class="text-gray-900">{{ $partoTipos[$parto->parto_tipo] ?? $parto->parto_tipo }}</dd></div>
                            @endif
                            @if ($parto->anestesia)
                                <div class="flex gap-2"><dt class="text-gray-500 w-36">Anestesia</dt><dd class="text-gray-900">{{ $anestesias[$parto->anestesia] ?? $parto->anestesia }}</dd></div>
                            @endif
                            @if ($parto->apgar)
                                <div class="flex gap-2"><dt class="text-gray-500 w-36">Apgar</dt><dd class="text-gray-900">{{ $parto->apgar }}</dd></div>
                            @endif
                            @if ($parto->parto_gamma)
                                <div class="flex gap-2"><dt class="text-gray-500 w-36">Parto Gamma</dt><dd class="text-gray-900">{{ $parto->parto_gamma }}</dd></div>
                            @endif
                            @if ($parto->observaciones)
                                <div><dt class="text-gray-500 mb-1">Observaciones</dt><dd class="text-gray-700 whitespace-pre-wrap">{{ $parto->observaciones }}</dd></div>
                            @endif
                        </dl>

                        {{-- Datos del RN --}}
                        @if ($parto->peso_rn || $parto->talla_rn || $parto->pc_rn || $parto->ombligo_dias || $parto->observaciones_rn)
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-2 border-t pt-3">Recién Nacido</p>
                        <dl class="space-y-2 text-sm">
                            @if ($parto->peso_rn)
                                <div class="flex gap-2"><dt class="text-gray-500 w-36">Peso</dt><dd class="text-gray-900">{{ $parto->peso_rn }}</dd></div>
                            @endif
                            @if ($parto->talla_rn)
                                <div class="flex gap-2"><dt class="text-gray-500 w-36">Altura</dt><dd class="text-gray-900">{{ $parto->talla_rn }}</dd></div>
                            @endif
                            @if ($parto->pc_rn)
                                <div class="flex gap-2"><dt class="text-gray-500 w-36">P.C.</dt><dd class="text-gray-900">{{ $parto->pc_rn }}</dd></div>
                            @endif
                            @if ($parto->ombligo_dias)
                                <div class="flex gap-2"><dt class="text-gray-500 w-36">Ombligo</dt><dd class="text-gray-900">{{ $parto->ombligo_dias }}</dd></div>
                            @endif
                            @if ($parto->observaciones_rn)
                                <div><dt class="text-gray-500 mb-1">Observaciones RN</dt><dd class="text-gray-700 whitespace-pre-wrap">{{ $parto->observaciones_rn }}</dd></div>
                            @endif
                        </dl>
                        @endif
                    @else
                        <p class="text-sm text-gray-400 text-center py-4">Sin datos registrados.</p>
                    @endif
                </x-wire-card>

            </div>
        </div>

    </div>

</x-admin-layout>
