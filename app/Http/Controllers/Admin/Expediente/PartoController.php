<?php

namespace App\Http\Controllers\Admin\Expediente;

use App\Http\Controllers\Controller;
use App\Models\Embarazo;
use App\Models\Parto;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PartoController extends Controller
{
    public function create(Patient $patient)
    {
        $embarazos = $patient->embarazos()->get();
        return view('admin.expediente.partos.create', compact('patient', 'embarazos'));
    }

    public function store(Request $request, Patient $patient)
    {
        $data = $request->validate([
            'embarazo_id'      => 'nullable|exists:embarazos,id',
            'fecha_parto'      => 'required|date',
            'tipo_parto'       => 'required|in:vaginal,cesarea',
            'semanas_gestacion' => 'nullable|integer|min:20|max:45',
            'peso_rn'          => 'nullable|numeric|min:0|max:10',
            'talla_rn'         => 'nullable|numeric|min:0|max:70',
            'apgar_1'          => 'nullable|integer|min:0|max:10',
            'apgar_5'          => 'nullable|integer|min:0|max:10',
            'complicaciones'   => 'nullable|string',
            'notas'            => 'nullable|string',
        ]);

        $patient->partos()->create($data);

        Session::flash('swal', ['icon' => 'success', 'title' => 'Parto registrado', 'text' => 'El parto ha sido registrado.']);
        return redirect()->route('admin.patients.show', $patient)->withFragment('partos');
    }

    public function edit(Parto $parto)
    {
        $patient = $parto->patient;
        $embarazos = $patient->embarazos()->get();
        return view('admin.expediente.partos.edit', compact('parto', 'patient', 'embarazos'));
    }

    public function update(Request $request, Parto $parto)
    {
        $data = $request->validate([
            'embarazo_id'      => 'nullable|exists:embarazos,id',
            'fecha_parto'      => 'required|date',
            'tipo_parto'       => 'required|in:vaginal,cesarea',
            'semanas_gestacion' => 'nullable|integer|min:20|max:45',
            'peso_rn'          => 'nullable|numeric|min:0|max:10',
            'talla_rn'         => 'nullable|numeric|min:0|max:70',
            'apgar_1'          => 'nullable|integer|min:0|max:10',
            'apgar_5'          => 'nullable|integer|min:0|max:10',
            'complicaciones'   => 'nullable|string',
            'notas'            => 'nullable|string',
        ]);

        $parto->update($data);

        Session::flash('swal', ['icon' => 'success', 'title' => 'Parto actualizado', 'text' => 'Los datos han sido actualizados.']);
        return redirect()->route('admin.patients.show', $parto->patient_id)->withFragment('partos');
    }

    public function destroy(Parto $parto)
    {
        $patientId = $parto->patient_id;
        $parto->delete();
        Session::flash('swal', ['icon' => 'success', 'title' => 'Eliminado', 'text' => 'Parto eliminado.']);
        return redirect()->route('admin.patients.show', $patientId)->withFragment('partos');
    }
}
