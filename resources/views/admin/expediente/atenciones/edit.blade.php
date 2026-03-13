<x-admin-layout title="Editar Atención" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Citas', 'href' => route('admin.appointments.index')],
    ['name' => 'Cita #' . $appointment->id, 'href' => route('admin.appointments.show', $appointment)],
    ['name' => 'Editar Atención'],
]">
    <form action="{{ route('admin.atenciones.update', $atencion) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Header --}}
        <x-wire-card class="mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Atención — {{ $appointment->patient->full_name }}</h2>
                    <div class="flex flex-wrap gap-x-4 gap-y-1 text-sm text-gray-600 mt-1">
                        <span><i class="fa-solid fa-calendar fa-fw"></i> {{ $appointment->scheduled_at->format('d/m/Y H:i') }}</span>
                        <span><i class="fa-solid fa-user-doctor fa-fw"></i> {{ $appointment->doctor->name }}</span>
                        @if ($appointment->motivoConsulta)
                            <span><i class="fa-solid fa-stethoscope fa-fw"></i> {{ $appointment->motivoConsulta->nombre }}</span>
                        @endif
                    </div>
                </div>
                <div class="flex space-x-3">
                    <x-wire-button outline gray href="{{ route('admin.appointments.show', $appointment) }}">Volver</x-wire-button>
                    <x-wire-button type="submit" primary><i class="fa-solid fa-check"></i> Guardar</x-wire-button>
                </div>
            </div>
        </x-wire-card>

        <div class="grid lg:grid-cols-2 gap-6">

            {{-- Columna izquierda --}}
            <div class="space-y-6">

                {{-- Notas importantes del paciente (solo lectura) --}}
                @if ($appointment->patient->notas_importantes)
                <x-wire-card>
                    <h3 class="text-sm font-semibold text-amber-700 mb-2"><i class="fa-solid fa-triangle-exclamation fa-fw"></i> Notas importantes del paciente</h3>
                    <p class="text-sm text-amber-800 bg-amber-50 border border-amber-200 rounded p-3">{{ $appointment->patient->notas_importantes }}</p>
                </x-wire-card>
                @endif

                {{-- Sintomatología --}}
                <x-wire-card>
                    <h3 class="font-semibold text-gray-700 mb-3">Sintomatología</h3>
                    <x-wire-textarea label="" name="sintomatologia" rows="4" placeholder="Describa los síntomas referidos por el paciente...">{{ old('sintomatologia', $atencion->sintomatologia) }}</x-wire-textarea>
                </x-wire-card>

                {{-- Estado de crecimiento --}}
                <x-wire-card>
                    <h3 class="font-semibold text-gray-700 mb-3">Estado de Crecimiento</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <x-wire-input label="Peso" name="peso" value="{{ old('peso', $atencion->peso) }}" placeholder="ej. 12.5 kg" />
                        <x-wire-input label="Altura / Talla" name="altura" value="{{ old('altura', $atencion->altura) }}" placeholder="ej. 85 cm" />
                        <x-wire-input label="P.C. (Perímetro Cefálico)" name="pc" value="{{ old('pc', $atencion->pc) }}" placeholder="ej. 46 cm" />
                        <x-wire-input label="I.M.C." name="imc" value="{{ old('imc', $atencion->imc) }}" placeholder="ej. 16.3" />
                    </div>
                </x-wire-card>

                {{-- Signos Vitales --}}
                <x-wire-card>
                    <h3 class="font-semibold text-gray-700 mb-3">Signos Vitales</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <x-wire-input label="Temperatura" name="temperatura" value="{{ old('temperatura', $atencion->temperatura) }}" placeholder="ej. 37.2 °C" />
                        <x-wire-input label="F.C. (Frec. Cardíaca)" name="fc" value="{{ old('fc', $atencion->fc) }}" placeholder="ej. 90 lpm" />
                        <x-wire-input label="F.R. (Frec. Respiratoria)" name="fr" value="{{ old('fr', $atencion->fr) }}" placeholder="ej. 22 rpm" />
                        <x-wire-input label="Presión Arterial" name="presion_arterial" value="{{ old('presion_arterial', $atencion->presion_arterial) }}" placeholder="ej. 100/60 mmHg" />
                    </div>
                </x-wire-card>

            </div>

            {{-- Columna derecha --}}
            <div class="space-y-6">

                {{-- Estudios Ordenados --}}
                <x-wire-card x-data="{
                    estudios: {{ json_encode(old('estudios') ?? $atencion->estudiosOrdenados->map(fn($e) => ['estudio' => $e->estudio, 'resultado' => $e->resultado ?? ''])->values()->toArray()) }},
                    agregar() {
                        this.estudios.push({ estudio: '', resultado: '' });
                    },
                    eliminar(index) {
                        this.estudios.splice(index, 1);
                    }
                }">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="font-semibold text-gray-700">Estudios Ordenados</h3>
                        <button type="button" @click="agregar()"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                            <i class="fa-solid fa-plus"></i> Agregar
                        </button>
                    </div>

                    <div class="space-y-3">
                        <template x-for="(item, index) in estudios" :key="index">
                            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                                <div class="flex justify-between items-start gap-2 mb-2">
                                    <div class="flex-1">
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Estudio</label>
                                        <input type="text"
                                            :name="'estudios[' + index + '][estudio]'"
                                            x-model="item.estudio"
                                            placeholder="Nombre del estudio..."
                                            class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <button type="button" @click="eliminar(index)"
                                        class="mt-5 p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded transition">
                                        <i class="fa-solid fa-trash text-sm"></i>
                                    </button>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Resultado</label>
                                    <textarea
                                        :name="'estudios[' + index + '][resultado]'"
                                        x-model="item.resultado"
                                        rows="2"
                                        placeholder="Resultado (puede completarse después)..."
                                        class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                                </div>
                            </div>
                        </template>

                        <p x-show="estudios.length === 0" class="text-sm text-gray-400 text-center py-4">
                            Sin estudios ordenados. Use el botón "Agregar" para añadir.
                        </p>
                    </div>
                </x-wire-card>

                {{-- Diagnósticos --}}
                <x-wire-card>
                    <h3 class="font-semibold text-gray-700 mb-3">Diagnóstico</h3>
                    <div class="space-y-4">
                        <x-wire-textarea label="Diagnóstico Posible" name="diagnostico_posible" rows="3">{{ old('diagnostico_posible', $atencion->diagnostico_posible) }}</x-wire-textarea>
                        <x-wire-textarea label="Diagnóstico Confirmado" name="diagnostico_confirmado" rows="3">{{ old('diagnostico_confirmado', $atencion->diagnostico_confirmado) }}</x-wire-textarea>
                    </div>
                </x-wire-card>

                {{-- Medicación --}}
                <x-wire-card>
                    <h3 class="font-semibold text-gray-700 mb-3">Medicación Indicada</h3>
                    <x-wire-textarea label="" name="medicacion_indicada" rows="4" placeholder="Liste los medicamentos indicados con dosis y frecuencia...">{{ old('medicacion_indicada', $atencion->medicacion_indicada) }}</x-wire-textarea>
                </x-wire-card>

                {{-- Notas internas --}}
                <x-wire-card>
                    <h3 class="font-semibold text-gray-700 mb-3">Notas</h3>
                    <x-wire-textarea label="" name="notas" rows="3" placeholder="Observaciones adicionales...">{{ old('notas', $atencion->notas) }}</x-wire-textarea>
                </x-wire-card>

            </div>
        </div>

    </form>
</x-admin-layout>
