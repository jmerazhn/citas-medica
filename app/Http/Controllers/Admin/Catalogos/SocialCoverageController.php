<?php

namespace App\Http\Controllers\Admin\Catalogos;

use App\Http\Controllers\Controller;
use App\Models\SocialCoverage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SocialCoverageController extends Controller
{
    public function index()
    {
        return view('admin.catalogos.coberturas-sociales.index');
    }

    public function create()
    {
        return view('admin.catalogos.coberturas-sociales.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:255|unique:social_coverages,name']);
        SocialCoverage::create($data);
        Session::flash('swal', ['icon' => 'success', 'title' => 'Guardado', 'text' => 'Cobertura social creada.']);
        return redirect()->route('admin.catalogos.coberturas-sociales.index');
    }

    public function edit(SocialCoverage $socialCoverage)
    {
        return view('admin.catalogos.coberturas-sociales.edit', compact('socialCoverage'));
    }

    public function update(Request $request, SocialCoverage $socialCoverage)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:social_coverages,name,' . $socialCoverage->id,
        ]);
        $socialCoverage->update($data);
        Session::flash('swal', ['icon' => 'success', 'title' => 'Actualizado', 'text' => 'Cobertura social actualizada.']);
        return redirect()->route('admin.catalogos.coberturas-sociales.index');
    }

    public function destroy(SocialCoverage $socialCoverage)
    {
        $socialCoverage->delete();
        Session::flash('swal', ['icon' => 'success', 'title' => 'Eliminado', 'text' => 'Cobertura social eliminada.']);
        return redirect()->route('admin.catalogos.coberturas-sociales.index');
    }
}
