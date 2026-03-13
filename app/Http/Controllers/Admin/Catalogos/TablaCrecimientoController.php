<?php

namespace App\Http\Controllers\Admin\Catalogos;

use App\Http\Controllers\Controller;
use App\Models\TablaCrecimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TablaCrecimientoController extends Controller
{
    public function index()
    {
        return view('admin.catalogos.tablas-crecimiento.index');
    }

    public function create()
    {
        return view('admin.catalogos.tablas-crecimiento.create', [
            'tipos' => TablaCrecimiento::$tipos,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tipo'        => 'required|in:peso,talla,perimetro_cefalico,imc',
            'sexo'        => 'required|in:M,F',
            'edad_meses'  => 'required|integer|min:0|max:228',
            'p3'  => 'required|numeric',
            'p10' => 'required|numeric',
            'p25' => 'required|numeric',
            'p50' => 'required|numeric',
            'p75' => 'required|numeric',
            'p90' => 'required|numeric',
            'p97' => 'required|numeric',
        ]);

        TablaCrecimiento::updateOrCreate(
            ['tipo' => $data['tipo'], 'sexo' => $data['sexo'], 'edad_meses' => $data['edad_meses']],
            $data
        );

        Session::flash('swal', ['icon' => 'success', 'title' => 'Guardado', 'text' => 'Tabla de crecimiento guardada.']);
        return redirect()->route('admin.catalogos.tablas-crecimiento.index');
    }

    public function edit(TablaCrecimiento $tablaCrecimiento)
    {
        return view('admin.catalogos.tablas-crecimiento.edit', [
            'tabla' => $tablaCrecimiento,
            'tipos' => TablaCrecimiento::$tipos,
        ]);
    }

    public function update(Request $request, TablaCrecimiento $tablaCrecimiento)
    {
        $data = $request->validate([
            'tipo'        => 'required|in:peso,talla,perimetro_cefalico,imc',
            'sexo'        => 'required|in:M,F',
            'edad_meses'  => 'required|integer|min:0|max:228',
            'p3'  => 'required|numeric',
            'p10' => 'required|numeric',
            'p25' => 'required|numeric',
            'p50' => 'required|numeric',
            'p75' => 'required|numeric',
            'p90' => 'required|numeric',
            'p97' => 'required|numeric',
        ]);

        $tablaCrecimiento->update($data);
        Session::flash('swal', ['icon' => 'success', 'title' => 'Actualizado', 'text' => 'Tabla de crecimiento actualizada.']);
        return redirect()->route('admin.catalogos.tablas-crecimiento.index');
    }

    public function destroy(TablaCrecimiento $tablaCrecimiento)
    {
        $tablaCrecimiento->delete();
        Session::flash('swal', ['icon' => 'success', 'title' => 'Eliminado', 'text' => 'Registro eliminado.']);
        return redirect()->route('admin.catalogos.tablas-crecimiento.index');
    }
}
