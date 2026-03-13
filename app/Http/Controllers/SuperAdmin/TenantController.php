<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::latest()->paginate(15);
        return view('super-admin.consultorios.index', compact('tenants'));
    }

    public function create()
    {
        return view('super-admin.consultorios.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'email'  => ['nullable', 'email', 'max:255'],
            'slug'   => ['required', 'string', 'max:50', 'alpha_dash', 'unique:tenants,id'],
        ]);

        $tenant = Tenant::create([
            'id'     => Str::lower($validated['slug']),
            'nombre' => $validated['nombre'],
            'email'  => $validated['email'],
            'activo' => true,
        ]);

        Session::flash('swal', [
            'icon'  => 'success',
            'title' => '¡Consultorio creado!',
            'text'  => "El consultorio {$tenant->nombre} fue creado exitosamente.",
        ]);

        return redirect()->route('super-admin.consultorios.index');
    }

    public function show(Tenant $consultorio)
    {
        return view('super-admin.consultorios.show', ['tenant' => $consultorio]);
    }

    public function edit(Tenant $consultorio)
    {
        return view('super-admin.consultorios.edit', ['tenant' => $consultorio]);
    }

    public function update(Request $request, Tenant $consultorio)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'email'  => ['nullable', 'email', 'max:255'],
            'activo' => ['boolean'],
        ]);

        $consultorio->update($validated);

        Session::flash('swal', [
            'icon'  => 'success',
            'title' => '¡Actualizado!',
            'text'  => 'El consultorio fue actualizado exitosamente.',
        ]);

        return redirect()->route('super-admin.consultorios.index');
    }

    public function destroy(Tenant $consultorio)
    {
        $consultorio->delete();

        Session::flash('swal', [
            'icon'  => 'success',
            'title' => '¡Eliminado!',
            'text'  => 'El consultorio fue eliminado.',
        ]);

        return redirect()->route('super-admin.consultorios.index');
    }
}
