@php
    use App\Models\TablaCrecimiento;
    use App\Models\Appointment;
    use Carbon\Carbon;

    // --- Config por tipo ---
    $tipoConfig = [
        'peso' => [
            'field'  => 'peso',
            'label'  => 'Evolución del Peso',
            'unit'   => 'kg',
            'color'  => '#f97316',
            'btnTxt' => 'Ver gráfica de peso',
        ],
        'talla' => [
            'field'  => 'altura',
            'label'  => 'Evolución de la Talla',
            'unit'   => 'cm',
            'color'  => '#3b82f6',
            'btnTxt' => 'Ver gráfica de talla',
        ],
        'perimetro_cefalico' => [
            'field'  => 'pc',
            'label'  => 'Evolución del Perímetro Cefálico',
            'unit'   => 'cm',
            'color'  => '#8b5cf6',
            'btnTxt' => 'Ver gráfica de P.C.',
        ],
        'imc' => [
            'field'  => 'imc',
            'label'  => 'Evolución del IMC',
            'unit'   => 'kg/m²',
            'color'  => '#ec4899',
            'btnTxt' => 'Ver gráfica de IMC',
        ],
    ];

    $tipo    = $tipo ?? 'peso';
    $cfg     = $tipoConfig[$tipo];
    $field   = $cfg['field'];

    $birthDate = $patient->fecha_nacimiento;
    $sexo      = $patient->sexo;
    $chartId   = 'growthChart_' . $tipo . '_' . $patient->id . '_' . uniqid();
    $initFn    = 'initChart_' . preg_replace('/\W/', '_', $chartId);

    // --- Mediciones reales del paciente ---
    $measurements = [];
    if ($birthDate) {
        if ($patient->relationLoaded('appointments')) {
            $appts = $patient->appointments->filter(fn($a) => $a->atencion && $a->atencion->$field);
        } else {
            $appts = Appointment::where('patient_id', $patient->id)
                ->whereHas('atencion', fn($q) => $q->whereNotNull($field))
                ->with(['atencion:id,appointment_id,' . $field])
                ->orderBy('scheduled_at')
                ->get();
        }

        foreach ($appts->sortBy('scheduled_at') as $appt) {
            $val = (float) ($appt->atencion->$field ?? 0);
            if ($val <= 0) continue;
            $ageMeses = (int) Carbon::parse($birthDate)->diffInMonths(Carbon::parse($appt->scheduled_at));
            $measurements[] = [
                'x'     => $ageMeses,
                'y'     => $val,
                'label' => Carbon::parse($appt->scheduled_at)->format('d/m/Y'),
            ];
        }
    }

    // --- Curvas de referencia (percentiles OMS) ---
    $percentileData = [];
    if ($sexo) {
        $maxAge = $birthDate ? max(0, (int) Carbon::parse($birthDate)->diffInMonths(now())) : 60;
        if (!empty($measurements)) {
            $maxAge = max($maxAge, max(array_column($measurements, 'x')) + 6);
        }
        $maxAge = min($maxAge, 228);

        $rows = TablaCrecimiento::where('tipo', $tipo)
            ->where('sexo', $sexo)
            ->where('edad_meses', '<=', $maxAge)
            ->orderBy('edad_meses')
            ->get();

        if ($rows->isNotEmpty()) {
            $percentileData = [
                'ages' => $rows->pluck('edad_meses')->toArray(),
                'p3'   => $rows->pluck('p3')->map(fn($v) => (float) $v)->toArray(),
                'p10'  => $rows->pluck('p10')->map(fn($v) => (float) $v)->toArray(),
                'p25'  => $rows->pluck('p25')->map(fn($v) => (float) $v)->toArray(),
                'p50'  => $rows->pluck('p50')->map(fn($v) => (float) $v)->toArray(),
                'p75'  => $rows->pluck('p75')->map(fn($v) => (float) $v)->toArray(),
                'p90'  => $rows->pluck('p90')->map(fn($v) => (float) $v)->toArray(),
                'p97'  => $rows->pluck('p97')->map(fn($v) => (float) $v)->toArray(),
            ];
        }
    }

    $hasData = !empty($measurements) || !empty($percentileData);
@endphp

@if ($hasData)
<div
    x-data="{ open: false, initialized: false }"
    x-effect="if (open && !initialized) { initialized = true; {{ $initFn }}(); }"
    class="{{ $class ?? '' }}">

    <button
        type="button"
        @click="open = !open"
        class="flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-800 transition mb-2">
        <i class="fa-solid fa-chart-line"></i>
        <span x-text="open ? 'Ocultar gráfica' : '{{ $cfg['btnTxt'] }}'">{{ $cfg['btnTxt'] }}</span>
        <i class="fa-solid fa-chevron-down transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
    </button>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-1"
        style="display:none">

        <div class="border border-gray-200 rounded-xl bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold text-gray-700 text-sm">{{ $cfg['label'] }}</h3>
                @if ($sexo)
                    <span class="text-xs text-gray-400">
                        Curvas OMS — {{ $sexo === 'M' ? 'Masculino' : 'Femenino' }}
                    </span>
                @endif
            </div>

            @if (empty($measurements))
                <p class="text-sm text-gray-400 italic">No hay mediciones registradas en las atenciones.</p>
            @endif

            <div class="relative" style="height: 300px;">
                <canvas id="{{ $chartId }}"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
@once
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
@endonce
<script>
function {{ $initFn }}() {
    const ages = @js($percentileData['ages'] ?? []);
    const p3   = @js($percentileData['p3']   ?? []);
    const p10  = @js($percentileData['p10']  ?? []);
    const p25  = @js($percentileData['p25']  ?? []);
    const p50  = @js($percentileData['p50']  ?? []);
    const p75  = @js($percentileData['p75']  ?? []);
    const p90  = @js($percentileData['p90']  ?? []);
    const p97  = @js($percentileData['p97']  ?? []);
    const pts  = @js($measurements);
    const unit = @js($cfg['unit']);
    const ptColor = @js($cfg['color']);

    const toXY = (arr) => ages.map((age, i) => ({ x: age, y: arr[i] }));

    const pLine = (label, data, color, dash = []) => ({
        label,
        data,
        borderColor: color,
        borderWidth: dash.length ? 1 : 1.5,
        borderDash: dash,
        pointRadius: 0,
        fill: false,
        tension: 0.4,
        order: 2,
    });

    const datasets = [];

    if (ages.length) {
        datasets.push(
            pLine('P97', toXY(p97), '#94a3b8', [4, 3]),
            pLine('P90', toXY(p90), '#7dd3fc', [3, 2]),
            pLine('P75', toXY(p75), '#86efac', [3, 2]),
            pLine('P50', toXY(p50), '#4ade80', []),
            pLine('P25', toXY(p25), '#86efac', [3, 2]),
            pLine('P10', toXY(p10), '#7dd3fc', [3, 2]),
            pLine('P3',  toXY(p3),  '#94a3b8', [4, 3]),
        );
    }

    if (pts.length) {
        datasets.push({
            label: @js($cfg['label']),
            data: pts.map(p => ({ x: p.x, y: p.y, label: p.label })),
            borderColor: ptColor,
            backgroundColor: ptColor,
            borderWidth: 2,
            pointRadius: 5,
            pointHoverRadius: 7,
            fill: false,
            tension: 0.3,
            order: 1,
        });
    }

    const ctx = document.getElementById('{{ $chartId }}');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'line',
        data: { datasets },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'nearest', intersect: false },
            scales: {
                x: {
                    type: 'linear',
                    title: { display: true, text: 'Edad (meses)', color: '#6b7280', font: { size: 11 } },
                    ticks: {
                        color: '#6b7280',
                        callback: (v) => {
                            if (v % 12 === 0) return v === 0 ? '0' : (v / 12) + 'a';
                            if (v % 6 === 0) return v + 'm';
                            return null;
                        },
                    },
                    grid: { color: '#f3f4f6' },
                },
                y: {
                    title: { display: true, text: unit, color: '#6b7280', font: { size: 11 } },
                    ticks: { color: '#6b7280' },
                    grid: { color: '#f3f4f6' },
                },
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        title: (items) => {
                            const raw = items[0].raw;
                            if (raw.label) return raw.label;
                            const m = items[0].parsed.x;
                            return m < 24 ? m + ' meses' : Math.floor(m / 12) + 'a ' + (m % 12) + 'm';
                        },
                        label: (item) => item.dataset.label + ': ' + item.parsed.y.toFixed(2) + ' ' + unit,
                    },
                },
                legend: {
                    labels: { color: '#6b7280', font: { size: 11 }, boxWidth: 20 },
                },
            },
        },
    });
}
</script>
@endpush
@endif
