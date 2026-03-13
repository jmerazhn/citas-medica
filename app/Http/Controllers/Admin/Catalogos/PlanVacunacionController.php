<?php

namespace App\Http\Controllers\Admin\Catalogos;

use App\Http\Controllers\Controller;
use App\Models\PlanVacunacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PlanVacunacionController extends Controller
{
    public function index()
    {
        return view('admin.catalogos.planes-vacunacion.index');
    }

    public function create()
    {
        return view('admin.catalogos.planes-vacunacion.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'      => 'required|string|max:255|unique:planes_vacunacion,nombre',
            'descripcion' => 'nullable|string',
        ]);
        PlanVacunacion::create($data);
        Session::flash('swal', ['icon' => 'success', 'title' => 'Guardado', 'text' => 'Plan de vacunación creado.']);
        return redirect()->route('admin.catalogos.planes-vacunacion.index');
    }

    public function edit(PlanVacunacion $planVacunacion)
    {
        return view('admin.catalogos.planes-vacunacion.edit', compact('planVacunacion'));
    }

    public function update(Request $request, PlanVacunacion $planVacunacion)
    {
        $data = $request->validate([
            'nombre'      => 'required|string|max:255|unique:planes_vacunacion,nombre,' . $planVacunacion->id,
            'descripcion' => 'nullable|string',
        ]);
        $planVacunacion->update($data);
        Session::flash('swal', ['icon' => 'success', 'title' => 'Actualizado', 'text' => 'Plan de vacunación actualizado.']);
        return redirect()->route('admin.catalogos.planes-vacunacion.index');
    }

    public function destroy(PlanVacunacion $planVacunacion)
    {
        $planVacunacion->delete();
        Session::flash('swal', ['icon' => 'success', 'title' => 'Eliminado', 'text' => 'Plan de vacunación eliminado.']);
        return redirect()->route('admin.catalogos.planes-vacunacion.index');
    }
}
