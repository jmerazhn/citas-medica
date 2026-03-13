<?php

namespace App\Http\Controllers\Admin\Expediente;

use App\Http\Controllers\Controller;
use App\Models\Embarazo;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class EmbarazoController extends Controller
{
    public function create(Patient $patient)
    {
        return view('admin.expediente.embarazos.create', compact('patient'));
    }

    public function store(Request $request, Patient $patient)
    {
        $data = $request->validate([
            'numero_embarazo'          => 'nullable|integer|min:1',
            'fecha_ultima_menstruacion' => 'nullable|date',
            'fecha_probable_parto'      => 'nullable|date',
            'semanas_gestacion'         => 'nullable|integer|min:1|max:45',
            'notas'                     => 'nullable|string',
        ]);

        $patient->embarazos()->create($data);

        Session::flash('swal', ['icon' => 'success', 'title' => 'Embarazo registrado', 'text' => 'El embarazo ha sido registrado.']);
        return redirect()->route('admin.patients.show', $patient)->withFragment('embarazos');
    }

    public function edit(Embarazo $embarazo)
    {
        $patient = $embarazo->patient;
        return view('admin.expediente.embarazos.edit', compact('embarazo', 'patient'));
    }

    public function update(Request $request, Embarazo $embarazo)
    {
        $data = $request->validate([
            'numero_embarazo'          => 'nullable|integer|min:1',
            'fecha_ultima_menstruacion' => 'nullable|date',
            'fecha_probable_parto'      => 'nullable|date',
            'semanas_gestacion'         => 'nullable|integer|min:1|max:45',
            'notas'                     => 'nullable|string',
        ]);

        $embarazo->update($data);

        Session::flash('swal', ['icon' => 'success', 'title' => 'Embarazo actualizado', 'text' => 'Los datos han sido actualizados.']);
        return redirect()->route('admin.patients.show', $embarazo->patient_id)->withFragment('embarazos');
    }

    public function destroy(Embarazo $embarazo)
    {
        $patientId = $embarazo->patient_id;
        $embarazo->delete();
        Session::flash('swal', ['icon' => 'success', 'title' => 'Eliminado', 'text' => 'Embarazo eliminado.']);
        return redirect()->route('admin.patients.show', $patientId)->withFragment('embarazos');
    }
}
