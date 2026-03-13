<?php

namespace App\Http\Controllers\Admin\Expediente;

use App\Http\Controllers\Controller;
use App\Models\Atencion;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AtencionController extends Controller
{
    public function create(Appointment $appointment)
    {
        abort_if($appointment->atencion, 404);

        $appointment->load(['patient', 'doctor', 'motivoConsulta']);

        return view('admin.expediente.atenciones.create', compact('appointment'));
    }

    public function store(Request $request, Appointment $appointment)
    {
        abort_if($appointment->atencion, 404);

        $data = $request->validate([
            'sintomatologia'        => 'nullable|string',
            'notas'                 => 'nullable|string',
            'peso'                  => 'nullable|string|max:50',
            'altura'                => 'nullable|string|max:50',
            'pc'                    => 'nullable|string|max:50',
            'imc'                   => 'nullable|string|max:50',
            'temperatura'           => 'nullable|string|max:50',
            'fc'                    => 'nullable|string|max:50',
            'fr'                    => 'nullable|string|max:50',
            'presion_arterial'      => 'nullable|string|max:50',
            'diagnostico_posible'   => 'nullable|string',
            'diagnostico_confirmado'=> 'nullable|string',
            'medicacion_indicada'   => 'nullable|string',
            'estudios'              => 'nullable|array',
            'estudios.*.estudio'    => 'required|string|max:255',
            'estudios.*.resultado'  => 'nullable|string',
        ]);

        DB::transaction(function () use ($data, $appointment) {
            $atencion = Atencion::create([
                'appointment_id'         => $appointment->id,
                'sintomatologia'         => $data['sintomatologia'] ?? null,
                'notas'                  => $data['notas'] ?? null,
                'peso'                   => $data['peso'] ?? null,
                'altura'                 => $data['altura'] ?? null,
                'pc'                     => $data['pc'] ?? null,
                'imc'                    => $data['imc'] ?? null,
                'temperatura'            => $data['temperatura'] ?? null,
                'fc'                     => $data['fc'] ?? null,
                'fr'                     => $data['fr'] ?? null,
                'presion_arterial'       => $data['presion_arterial'] ?? null,
                'diagnostico_posible'    => $data['diagnostico_posible'] ?? null,
                'diagnostico_confirmado' => $data['diagnostico_confirmado'] ?? null,
                'medicacion_indicada'    => $data['medicacion_indicada'] ?? null,
            ]);

            foreach ($data['estudios'] ?? [] as $item) {
                if (!empty($item['estudio'])) {
                    $atencion->estudiosOrdenados()->create([
                        'estudio'   => $item['estudio'],
                        'resultado' => $item['resultado'] ?? null,
                    ]);
                }
            }
        });

        Session::flash('swal', ['icon' => 'success', 'title' => 'Atención registrada', 'text' => 'La atención ha sido guardada correctamente.']);
        return redirect()->route('admin.appointments.show', $appointment);
    }

    public function edit(Atencion $atencion)
    {
        $atencion->load(['appointment.patient', 'appointment.doctor', 'appointment.motivoConsulta', 'estudiosOrdenados']);
        $appointment = $atencion->appointment;

        return view('admin.expediente.atenciones.edit', compact('atencion', 'appointment'));
    }

    public function update(Request $request, Atencion $atencion)
    {
        $data = $request->validate([
            'sintomatologia'        => 'nullable|string',
            'notas'                 => 'nullable|string',
            'peso'                  => 'nullable|string|max:50',
            'altura'                => 'nullable|string|max:50',
            'pc'                    => 'nullable|string|max:50',
            'imc'                   => 'nullable|string|max:50',
            'temperatura'           => 'nullable|string|max:50',
            'fc'                    => 'nullable|string|max:50',
            'fr'                    => 'nullable|string|max:50',
            'presion_arterial'      => 'nullable|string|max:50',
            'diagnostico_posible'   => 'nullable|string',
            'diagnostico_confirmado'=> 'nullable|string',
            'medicacion_indicada'   => 'nullable|string',
            'estudios'              => 'nullable|array',
            'estudios.*.estudio'    => 'required|string|max:255',
            'estudios.*.resultado'  => 'nullable|string',
        ]);

        DB::transaction(function () use ($data, $atencion) {
            $atencion->update([
                'sintomatologia'         => $data['sintomatologia'] ?? null,
                'notas'                  => $data['notas'] ?? null,
                'peso'                   => $data['peso'] ?? null,
                'altura'                 => $data['altura'] ?? null,
                'pc'                     => $data['pc'] ?? null,
                'imc'                    => $data['imc'] ?? null,
                'temperatura'            => $data['temperatura'] ?? null,
                'fc'                     => $data['fc'] ?? null,
                'fr'                     => $data['fr'] ?? null,
                'presion_arterial'       => $data['presion_arterial'] ?? null,
                'diagnostico_posible'    => $data['diagnostico_posible'] ?? null,
                'diagnostico_confirmado' => $data['diagnostico_confirmado'] ?? null,
                'medicacion_indicada'    => $data['medicacion_indicada'] ?? null,
            ]);

            $atencion->estudiosOrdenados()->delete();

            foreach ($data['estudios'] ?? [] as $item) {
                if (!empty($item['estudio'])) {
                    $atencion->estudiosOrdenados()->create([
                        'estudio'   => $item['estudio'],
                        'resultado' => $item['resultado'] ?? null,
                    ]);
                }
            }
        });

        Session::flash('swal', ['icon' => 'success', 'title' => 'Atención actualizada', 'text' => 'Los datos han sido actualizados.']);
        return redirect()->route('admin.appointments.show', $atencion->appointment_id);
    }
}
