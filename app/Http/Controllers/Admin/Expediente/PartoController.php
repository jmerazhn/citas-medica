<?php

namespace App\Http\Controllers\Admin\Expediente;

use App\Http\Controllers\Concerns\UppercasesTextFields;
use App\Http\Controllers\Controller;
use App\Models\Embarazo;
use App\Models\Parto;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PartoController extends Controller
{
    use UppercasesTextFields;

    public function create(Patient $patient)
    {
        abort_if($patient->partos()->exists(), 404);
        return view('admin.expediente.partos.create', compact('patient'));
    }

    public function store(Request $request, Patient $patient)
    {
        abort_if($patient->partos()->exists(), 404);
        $data = $request->validate([
            'fecha_parto'     => 'required|date',
            'lugar'           => 'nullable|string|max:200',
            'cesarea'         => 'nullable|boolean',
            'motivo_cesarea'  => 'nullable|string',
            'posicion'        => 'nullable|in:cefalica,podalica',
            'parto_tipo'      => 'nullable|in:eutocico,distocico',
            'apgar'           => 'nullable|string',
            'parto_gamma'     => 'nullable|string',
            'anestesia'       => 'nullable|in:no,raquidea,peridural,total',
            'observaciones'   => 'nullable|string',
            'peso_rn'         => 'nullable|numeric|min:0',
            'talla_rn'        => 'nullable|numeric|min:0',
            'pc_rn'           => 'nullable|numeric|min:0',
            'ombligo_dias'    => 'nullable|integer|min:0',
            'observaciones_rn'=> 'nullable|string',
        ]);

        $data['cesarea'] = $request->boolean('cesarea');

        $data = $this->uppercase($data, [
            'lugar', 'motivo_cesarea', 'apgar', 'parto_gamma', 'observaciones', 'observaciones_rn',
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
            'fecha_parto'     => 'required|date',
            'lugar'           => 'nullable|string|max:200',
            'cesarea'         => 'nullable|boolean',
            'motivo_cesarea'  => 'nullable|string',
            'posicion'        => 'nullable|in:cefalica,podalica',
            'parto_tipo'      => 'nullable|in:eutocico,distocico',
            'apgar'           => 'nullable|string',
            'parto_gamma'     => 'nullable|string',
            'anestesia'       => 'nullable|in:no,raquidea,peridural,total',
            'observaciones'   => 'nullable|string',
            'peso_rn'         => 'nullable|numeric|min:0',
            'talla_rn'        => 'nullable|numeric|min:0',
            'pc_rn'           => 'nullable|numeric|min:0',
            'ombligo_dias'    => 'nullable|integer|min:0',
            'observaciones_rn'=> 'nullable|string',
        ]);

        $data['cesarea'] = $request->boolean('cesarea');

        $data = $this->uppercase($data, [
            'lugar', 'motivo_cesarea', 'apgar', 'parto_gamma', 'observaciones', 'observaciones_rn',
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
