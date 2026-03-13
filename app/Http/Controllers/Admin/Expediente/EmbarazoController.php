<?php

namespace App\Http\Controllers\Admin\Expediente;

use App\Http\Controllers\Concerns\UppercasesTextFields;
use App\Http\Controllers\Controller;
use App\Models\Embarazo;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class EmbarazoController extends Controller
{
    use UppercasesTextFields;

    public function create(Patient $patient)
    {
        abort_if($patient->embarazos()->exists(), 404);
        return view('admin.expediente.embarazos.create', compact('patient'));
    }

    public function store(Request $request, Patient $patient)
    {
        abort_if($patient->embarazos()->exists(), 404);
        $data = $request->validate([
            'numero_embarazo'  => 'nullable|integer|min:1',
            'obstetra'         => 'nullable|string|max:150',
            'semanas_gestacion'=> 'nullable|integer|min:1|max:45',
            'diabetes'         => 'nullable|boolean',
            'hipertension'     => 'nullable|boolean',
            'traumatismo'      => 'nullable|boolean',
            'infecciones'      => 'nullable|boolean',
            'asma'             => 'nullable|boolean',
            'medicacion'       => 'nullable|string',
            'observaciones'    => 'nullable|string',
        ]);

        // Checkboxes no enviados = false
        foreach (['diabetes','hipertension','traumatismo','infecciones','asma'] as $field) {
            $data[$field] = $request->boolean($field);
        }

        $data = $this->uppercase($data, ['obstetra', 'medicacion', 'observaciones']);

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
            'numero_embarazo'  => 'nullable|integer|min:1',
            'obstetra'         => 'nullable|string|max:150',
            'semanas_gestacion'=> 'nullable|integer|min:1|max:45',
            'diabetes'         => 'nullable|boolean',
            'hipertension'     => 'nullable|boolean',
            'traumatismo'      => 'nullable|boolean',
            'infecciones'      => 'nullable|boolean',
            'asma'             => 'nullable|boolean',
            'medicacion'       => 'nullable|string',
            'observaciones'    => 'nullable|string',
        ]);

        foreach (['diabetes','hipertension','traumatismo','infecciones','asma'] as $field) {
            $data[$field] = $request->boolean($field);
        }

        $data = $this->uppercase($data, ['obstetra', 'medicacion', 'observaciones']);

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
