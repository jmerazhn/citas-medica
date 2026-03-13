<?php

namespace App\Http\Controllers\Admin\Expediente;

use App\Http\Controllers\Controller;
use App\Models\Consulta;
use App\Models\MotivoConsulta;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ConsultaController extends Controller
{
    public function create(Patient $patient)
    {
        $motivos = MotivoConsulta::orderBy('nombre')->get();
        return view('admin.expediente.consultas.create', compact('patient', 'motivos'));
    }

    public function store(Request $request, Patient $patient)
    {
        $data = $request->validate([
            'fecha'              => 'required|date',
            'motivo_consulta_id' => 'nullable|exists:motivos_consulta,id',
            'motivo_detalle'     => 'nullable|string|max:255',
            'peso'               => 'nullable|numeric|min:0|max:300',
            'talla'              => 'nullable|numeric|min:0|max:300',
            'temperatura'        => 'nullable|numeric|min:30|max:45',
            'fc'                 => 'nullable|integer|min:0|max:300',
            'fr'                 => 'nullable|integer|min:0|max:100',
            'spo2'               => 'nullable|integer|min:0|max:100',
            'diagnostico'        => 'nullable|string',
            'tratamiento'        => 'nullable|string',
            'notas'              => 'nullable|string',
        ]);

        $patient->consultas()->create($data);

        Session::flash('swal', ['icon' => 'success', 'title' => 'Consulta registrada', 'text' => 'La consulta ha sido registrada.']);
        return redirect()->route('admin.patients.show', $patient)->withFragment('consultas');
    }

    public function edit(Consulta $consulta)
    {
        $patient = $consulta->patient;
        $motivos = MotivoConsulta::orderBy('nombre')->get();
        return view('admin.expediente.consultas.edit', compact('consulta', 'patient', 'motivos'));
    }

    public function update(Request $request, Consulta $consulta)
    {
        $data = $request->validate([
            'fecha'              => 'required|date',
            'motivo_consulta_id' => 'nullable|exists:motivos_consulta,id',
            'motivo_detalle'     => 'nullable|string|max:255',
            'peso'               => 'nullable|numeric|min:0|max:300',
            'talla'              => 'nullable|numeric|min:0|max:300',
            'temperatura'        => 'nullable|numeric|min:30|max:45',
            'fc'                 => 'nullable|integer|min:0|max:300',
            'fr'                 => 'nullable|integer|min:0|max:100',
            'spo2'               => 'nullable|integer|min:0|max:100',
            'diagnostico'        => 'nullable|string',
            'tratamiento'        => 'nullable|string',
            'notas'              => 'nullable|string',
        ]);

        $consulta->update($data);

        Session::flash('swal', ['icon' => 'success', 'title' => 'Consulta actualizada', 'text' => 'Los datos han sido actualizados.']);
        return redirect()->route('admin.patients.show', $consulta->patient_id)->withFragment('consultas');
    }

    public function destroy(Consulta $consulta)
    {
        $patientId = $consulta->patient_id;
        $consulta->delete();
        Session::flash('swal', ['icon' => 'success', 'title' => 'Eliminado', 'text' => 'Consulta eliminada.']);
        return redirect()->route('admin.patients.show', $patientId)->withFragment('consultas');
    }
}
