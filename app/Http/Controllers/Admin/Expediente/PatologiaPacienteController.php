<?php

namespace App\Http\Controllers\Admin\Expediente;

use App\Http\Controllers\Concerns\UppercasesTextFields;
use App\Http\Controllers\Controller;
use App\Models\Patologia;
use App\Models\Patient;
use App\Models\PatientPatologia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PatologiaPacienteController extends Controller
{
    use UppercasesTextFields;

    public function create(Patient $patient)
    {
        $patologias = Patologia::orderBy('nombre')->get();
        return view('admin.expediente.patologias.create', compact('patient', 'patologias'));
    }

    public function store(Request $request, Patient $patient)
    {
        $data = $request->validate([
            'patologia_id'      => 'required|exists:patologias,id',
            'fecha_diagnostico' => 'nullable|date',
            'estado'            => 'required|in:activa,resuelta',
            'notas'             => 'nullable|string',
        ]);

        $data = $this->uppercase($data, ['notas']);

        $patient->patologias()->create($data);

        Session::flash('swal', ['icon' => 'success', 'title' => 'Patología registrada', 'text' => 'La patología ha sido registrada.']);
        return redirect()->route('admin.patients.show', $patient)->withFragment('patologias');
    }

    public function edit(PatientPatologia $patientPatologia)
    {
        $patient = $patientPatologia->patient;
        $patologias = Patologia::orderBy('nombre')->get();
        return view('admin.expediente.patologias.edit', compact('patientPatologia', 'patient', 'patologias'));
    }

    public function update(Request $request, PatientPatologia $patientPatologia)
    {
        $data = $request->validate([
            'patologia_id'      => 'required|exists:patologias,id',
            'fecha_diagnostico' => 'nullable|date',
            'estado'            => 'required|in:activa,resuelta',
            'notas'             => 'nullable|string',
        ]);

        $data = $this->uppercase($data, ['notas']);

        $patientPatologia->update($data);

        Session::flash('swal', ['icon' => 'success', 'title' => 'Patología actualizada', 'text' => 'Los datos han sido actualizados.']);
        return redirect()->route('admin.patients.show', $patientPatologia->patient_id)->withFragment('patologias');
    }

    public function destroy(PatientPatologia $patientPatologia)
    {
        $patientId = $patientPatologia->patient_id;
        $patientPatologia->delete();
        Session::flash('swal', ['icon' => 'success', 'title' => 'Eliminado', 'text' => 'Patología eliminada del paciente.']);
        return redirect()->route('admin.patients.show', $patientId)->withFragment('patologias');
    }
}
