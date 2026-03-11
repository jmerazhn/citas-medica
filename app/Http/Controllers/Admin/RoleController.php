<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\session;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);
        Role::create(['name' => $request->name]);

        Session::flash('swal',[
            'title' => 'Role Created',
            'text' => 'The role has been created successfully.',
            'icon' => 'success',
        ]);
        return redirect()->route('admin.roles.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        return view('admin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        if($role->id <= 3){
            Session::flash('swal',[
                'title' => 'Acción no permitida',
                'text' => 'No se puede editar este rol predeterminado.',
                'icon' => 'error',
            ]);
            return redirect()->route('admin.roles.index');
        }

        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
        ]);
        $role->update(['name' => $request->name]);

        Session::flash('swal',[
            'title' => 'Rol actualizado correctamente',
            'text' => 'El rol ha sido actualizado exitosamente.',
            'icon' => 'success',
        ]);
        return redirect()->route('admin.roles.edit', $role);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        if($role->id <= 3){
            Session::flash('swal',[
                'title' => 'Acción no permitida',
                'text' => 'No se puede eliminar este rol predeterminado.',
                'icon' => 'error',
            ]);
            return redirect()->route('admin.roles.index');
        }

        $role->delete();

        Session::flash('swal',[
            'title' => 'Rol Eliminado',
            'text' => 'El rol ha sido eliminado exitosamente.',
            'icon' => 'success',
        ]);
        return redirect()->route('admin.roles.index');
    }
}
