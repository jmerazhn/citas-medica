<?php

namespace App\Http\Controllers\Admin\Catalogos;

use App\Http\Controllers\Concerns\UppercasesTextFields;
use App\Http\Controllers\Controller;
use App\Models\Patologia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PatologiaCatalogoController extends Controller
{
    use UppercasesTextFields;

    public function index()
    {
        return view('admin.catalogos.patologias.index');
    }

    public function create()
    {
        return view('admin.catalogos.patologias.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'      => 'required|string|max:255|unique:patologias,nombre',
            'descripcion' => 'nullable|string',
        ]);
        $data = $this->uppercase($data, ['nombre', 'descripcion']);
        Patologia::create($data);
        Session::flash('swal', ['icon' => 'success', 'title' => 'Guardado', 'text' => 'Patología creada.']);
        return redirect()->route('admin.catalogos.patologias.index');
    }

    public function edit(Patologia $patologia)
    {
        return view('admin.catalogos.patologias.edit', compact('patologia'));
    }

    public function update(Request $request, Patologia $patologia)
    {
        $data = $request->validate([
            'nombre'      => 'required|string|max:255|unique:patologias,nombre,' . $patologia->id,
            'descripcion' => 'nullable|string',
        ]);
        $data = $this->uppercase($data, ['nombre', 'descripcion']);
        $patologia->update($data);
        Session::flash('swal', ['icon' => 'success', 'title' => 'Actualizado', 'text' => 'Patología actualizada.']);
        return redirect()->route('admin.catalogos.patologias.index');
    }

    public function destroy(Patologia $patologia)
    {
        $patologia->delete();
        Session::flash('swal', ['icon' => 'success', 'title' => 'Eliminado', 'text' => 'Patología eliminada.']);
        return redirect()->route('admin.catalogos.patologias.index');
    }
}
