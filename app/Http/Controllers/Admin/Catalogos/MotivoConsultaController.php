<?php

namespace App\Http\Controllers\Admin\Catalogos;

use App\Http\Controllers\Controller;
use App\Models\MotivoConsulta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MotivoConsultaController extends Controller
{
    public function index()
    {
        return view('admin.catalogos.motivos-consulta.index');
    }

    public function create()
    {
        return view('admin.catalogos.motivos-consulta.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate(['nombre' => 'required|string|max:255|unique:motivos_consulta,nombre']);
        MotivoConsulta::create($data);
        Session::flash('swal', ['icon' => 'success', 'title' => 'Guardado', 'text' => 'Motivo de consulta creado.']);
        return redirect()->route('admin.catalogos.motivos-consulta.index');
    }

    public function edit(MotivoConsulta $motivoConsulta)
    {
        return view('admin.catalogos.motivos-consulta.edit', compact('motivoConsulta'));
    }

    public function update(Request $request, MotivoConsulta $motivoConsulta)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255|unique:motivos_consulta,nombre,' . $motivoConsulta->id,
        ]);
        $motivoConsulta->update($data);
        Session::flash('swal', ['icon' => 'success', 'title' => 'Actualizado', 'text' => 'Motivo de consulta actualizado.']);
        return redirect()->route('admin.catalogos.motivos-consulta.index');
    }

    public function destroy(MotivoConsulta $motivoConsulta)
    {
        $motivoConsulta->delete();
        Session::flash('swal', ['icon' => 'success', 'title' => 'Eliminado', 'text' => 'Motivo de consulta eliminado.']);
        return redirect()->route('admin.catalogos.motivos-consulta.index');
    }
}
