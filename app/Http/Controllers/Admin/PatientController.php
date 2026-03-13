<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodType;
use App\Models\Patient;
use App\Models\SocialCoverage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PatientController extends Controller
{
    public function index()
    {
        return view('admin.patients.index');
    }

    public function create()
    {
        $bloodTypes = BloodType::all();
        $socialCoverages = SocialCoverage::orderBy('name')->get();

        return view('admin.patients.create', compact('bloodTypes', 'socialCoverages'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombres'             => 'required|string|max:255',
            'apellidos'           => 'required|string|max:255',
            'sexo'                => 'nullable|in:M,F',
            'fecha_nacimiento'    => 'nullable|date',
            'madre'               => 'nullable|string|max:255',
            'padre'               => 'nullable|string|max:255',
            'domicilio'           => 'nullable|string|max:255',
            'ciudad'              => 'nullable|string|max:255',
            'telefono'            => 'nullable|string|max:30',
            'social_coverage_id'  => 'nullable|exists:social_coverages,id',
            'blood_type_id'       => 'nullable|exists:blood_types,id',
            'notas_importantes'   => 'nullable|string',
        ]);

        $patient = Patient::create($data);

        Session::flash('swal', [
            'icon'  => 'success',
            'title' => 'Paciente registrado',
            'text'  => 'El paciente ha sido registrado correctamente.',
        ]);

        return redirect()->route('admin.patients.edit', $patient);
    }

    public function show(Patient $patient)
    {
        $patient->load([
            'bloodType',
            'socialCoverage',
            'vacunas.planVacunacion',
            'patologias.patologia',
            'embarazos',
            'partos.embarazo',
            'appointments' => fn($q) => $q->with(['doctor', 'motivoConsulta', 'atencion.estudiosOrdenados'])
                                          ->orderBy('scheduled_at', 'desc'),
        ]);

        return view('admin.patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        $bloodTypes = BloodType::all();
        $socialCoverages = SocialCoverage::orderBy('name')->get();

        return view('admin.patients.edit', compact('patient', 'bloodTypes', 'socialCoverages'));
    }

    public function update(Request $request, Patient $patient)
    {
        $data = $request->validate([
            'nombres'             => 'required|string|max:255',
            'apellidos'           => 'required|string|max:255',
            'sexo'                => 'nullable|in:M,F',
            'fecha_nacimiento'    => 'nullable|date',
            'madre'               => 'nullable|string|max:255',
            'padre'               => 'nullable|string|max:255',
            'domicilio'           => 'nullable|string|max:255',
            'ciudad'              => 'nullable|string|max:255',
            'telefono'            => 'nullable|string|max:30',
            'social_coverage_id'  => 'nullable|exists:social_coverages,id',
            'blood_type_id'       => 'nullable|exists:blood_types,id',
            'notas_importantes'   => 'nullable|string',
        ]);

        $patient->update($data);

        Session::flash('swal', [
            'icon'  => 'success',
            'title' => 'Paciente actualizado',
            'text'  => 'Los datos del paciente se han actualizado correctamente.',
        ]);

        return redirect()->route('admin.patients.edit', $patient);
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();

        Session::flash('swal', [
            'icon'  => 'success',
            'title' => 'Paciente eliminado',
            'text'  => 'El paciente ha sido eliminado correctamente.',
        ]);

        return redirect()->route('admin.patients.index');
    }
}
