@php
    use App\Models\TablaCrecimiento;
    use App\Models\Appointment;
    use Carbon\Carbon;

    $birthDate   = $patient->fecha_nacimiento;
    $sexo        = $patient->sexo; // 'M' | 'F' | null
    $chartId     = 'pesoChart_' . $patient->id . '_' . uniqid();

    // --- Mediciones reales del paciente ---
    $measurements = [];
    if ($birthDate) {
        // Use already-loaded relation if available, otherwise query
        if ($patient->relationLoaded('appointments')) {
            $appts = $patient->appointments->filter(fn($a) => $a->atencion && $a->atencion->peso);
        } else {
            $appts = Appointment::where('patient_id', $patient->id)
                ->whereHas('atencion', fn($q) => $q->whereNotNull('peso'))
                ->with(['atencion:id,appointment_id,peso'])
                ->orderBy('scheduled_at')
                ->get();
        }

        foreach ($appts->sortBy('scheduled_at') as $appt) {
            $pesoVal = (float) ($appt->atencion->peso ?? 0);
            if ($pesoVal <= 0) continue;
            $ageMeses = (int) Carbon::parse($appt->scheduled_at)->diffInMonths(Carbon::parse($birthDate));
            $measurements[] = [
                'x'     => $ageMeses,
                'y'     => $pesoVal,
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
        $maxAge = min($maxAge, 228); // cap 19 años (OMS va hasta ahí)

        $rows = TablaCrecimiento::where('tipo', 'peso')
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
<x-wire-card class="{{ $class ?? '' }}">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold text-gray-700">Evolución del Peso</h3>
        @if ($sexo)
            <span class="text-xs text-gray-500">
                Curvas OMS — {{ $sexo === 'M' ? 'Masculino' : 'Femenino' }}
            </span>
        @endif
    </div>

    @if (empty($measurements))
        <p class="text-sm text-gray-400 italic">No hay mediciones de peso registradas en las atenciones.</p>
    @endif

    <div class="relative" style="height: 320px;">
        <canvas id="{{ $chartId }}"></canvas>
    </div>
</x-wire-card>

@push('scripts')
@once
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
@endonce
<script>
(function () {
    const ages   = @js($percentileData['ages'] ?? []);
    const p3     = @js($percentileData['p3']   ?? []);
    const p10    = @js($percentileData['p10']  ?? []);
    const p25    = @js($percentileData['p25']  ?? []);
    const p50    = @js($percentileData['p50']  ?? []);
    const p75    = @js($percentileData['p75']  ?? []);
    const p90    = @js($percentileData['p90']  ?? []);
    const p97    = @js($percentileData['p97']  ?? []);
    const pts    = @js($measurements);

    // Convert reference curves to {x, y} objects
    const toXY = (arr) => ages.map((age, i) => ({ x: age, y: arr[i] }));

    // Percentile line style helper
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
            label: 'Peso del paciente',
            data: pts.map(p => ({ x: p.x, y: p.y, label: p.label })),
            borderColor: '#f97316',
            backgroundColor: '#f97316',
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
                    title: { display: true, text: 'Peso (kg)', color: '#6b7280', font: { size: 11 } },
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
                        label: (item) => {
                            return item.dataset.label + ': ' + item.parsed.y.toFixed(2) + ' kg';
                        },
                    },
                },
                legend: {
                    labels: {
                        color: '#6b7280',
                        font: { size: 11 },
                        boxWidth: 20,
                        filter: (item) => item.text !== '', // hide empty
                    },
                },
            },
        },
    });
})();
</script>
@endpush
@endif
