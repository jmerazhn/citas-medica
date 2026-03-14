<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    private array $permissionGroups = [
        'Gestión'           => ['ver-roles', 'gestionar-roles', 'ver-usuarios', 'gestionar-usuarios'],
        'Pacientes'         => ['ver-pacientes', 'gestionar-pacientes'],
        'Expediente clínico'=> ['ver-expediente', 'gestionar-expediente'],
        'Citas'             => ['ver-citas', 'gestionar-citas', 'confirmar-citas', 'completar-citas', 'cancelar-citas'],
        'Horarios'          => ['gestionar-horarios'],
        'Catálogos'         => ['ver-catalogos', 'gestionar-catalogos'],
    ];

    public function index()
    {
        return view('admin.roles.index');
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);
        Role::create(['name' => $request->name]);

        Session::flash('swal', [
            'title' => 'Rol creado',
            'text'  => 'El rol ha sido creado exitosamente.',
            'icon'  => 'success',
        ]);
        return redirect()->route('admin.roles.index');
    }

    public function show(Role $role)
    {
        return view('admin.roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        $protected = $role->id <= 3;
        $groups    = $this->permissionGroups;

        return view('admin.roles.edit', compact('role', 'protected', 'groups'));
    }

    public function update(Request $request, Role $role)
    {
        $rules = ['permissions' => 'array'];

        if ($role->id > 3) {
            $rules['name'] = 'required|unique:roles,name,' . $role->id;
            $role->update(['name' => $request->name]);
        }

        $request->validate($rules);

        $role->syncPermissions($request->input('permissions', []));

        Session::flash('swal', [
            'title' => 'Rol actualizado',
            'text'  => 'Los permisos del rol han sido actualizados.',
            'icon'  => 'success',
        ]);
        return redirect()->route('admin.roles.edit', $role);
    }

    public function destroy(Role $role)
    {
        if ($role->id <= 3) {
            Session::flash('swal', [
                'title' => 'Acción no permitida',
                'text'  => 'No se puede eliminar este rol predeterminado.',
                'icon'  => 'error',
            ]);
            return redirect()->route('admin.roles.index');
        }

        $role->delete();

        Session::flash('swal', [
            'title' => 'Rol eliminado',
            'text'  => 'El rol ha sido eliminado exitosamente.',
            'icon'  => 'success',
        ]);
        return redirect()->route('admin.roles.index');
    }
}
