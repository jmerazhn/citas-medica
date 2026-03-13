<?php

namespace App\Http\Controllers\Admin\Expediente;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PlanVacunacion;
use App\Models\Vacuna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class VacunaController extends Controller
{
    public function create(Patient $patient)
    {
        $planes = PlanVacunacion::orderBy('nombre')->get();
        return view('admin.expediente.vacunas.create', compact('patient', 'planes'));
    }

    public function store(Request $request, Patient $patient)
    {
        $data = $request->validate([
            'plan_vacunacion_id' => 'nullable|exists:planes_vacunacion,id',
            'vacuna'             => 'required|string|max:255',
            'fecha_aplicacion'   => 'required|date',
            'dosis'              => 'nullable|string|max:100',
            'lote'               => 'nullable|string|max:100',
            'notas'              => 'nullable|string',
        ]);

        $patient->vacunas()->create($data);

        Session::flash('swal', ['icon' => 'success', 'title' => 'Vacuna registrada', 'text' => 'La vacuna ha sido registrada.']);
        return redirect()->route('admin.patients.show', $patient)->withFragment('vacunas');
    }

    public function edit(Vacuna $vacuna)
    {
        $patient = $vacuna->patient;
        $planes = PlanVacunacion::orderBy('nombre')->get();
        return view('admin.expediente.vacunas.edit', compact('vacuna', 'patient', 'planes'));
    }

    public function update(Request $request, Vacuna $vacuna)
    {
        $data = $request->validate([
            'plan_vacunacion_id' => 'nullable|exists:planes_vacunacion,id',
            'vacuna'             => 'required|string|max:255',
            'fecha_aplicacion'   => 'required|date',
            'dosis'              => 'nullable|string|max:100',
            'lote'               => 'nullable|string|max:100',
            'notas'              => 'nullable|string',
        ]);

        $vacuna->update($data);

        Session::flash('swal', ['icon' => 'success', 'title' => 'Vacuna actualizada', 'text' => 'Los datos han sido actualizados.']);
        return redirect()->route('admin.patients.show', $vacuna->patient_id)->withFragment('vacunas');
    }

    public function destroy(Vacuna $vacuna)
    {
        $patientId = $vacuna->patient_id;
        $vacuna->delete();
        Session::flash('swal', ['icon' => 'success', 'title' => 'Eliminado', 'text' => 'Vacuna eliminada.']);
        return redirect()->route('admin.patients.show', $patientId)->withFragment('vacunas');
    }
}
