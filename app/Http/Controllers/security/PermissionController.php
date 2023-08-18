<?php

namespace App\Http\Controllers\security;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    public function index(Role $role)
    {
        return view('security.permission.index', compact('role'));
    }

    public function create(Role $role)
    {
        $menus = Menu::all();
        $permissions = Permission::all();

        return view('security.permission.create', compact('menus', 'permissions', 'role'));
    }

    public function store(Request $request, Role $role)
    {
        $request->validate([
            'permission_id' => 'required|exists:permissions,id',
            'menu_id' => 'required|exists:menus,id',
        ]);

        DB::beginTransaction();
        try {
            if ($role->permissions()->where('permission_id', $request->permission_id)->where('menu_id', $request->menu_id)->exists()) {
                DB::rollBack();
                return redirect()->route('role.permission.create', ['role' => $role])->with('error', 'El permiso ya existe para este menú.');
            }

            $role->permissions()->attach($request->permission_id, ['menu_id' => $request->menu_id]);

            DB::commit();
            return redirect()->route('role.permission.index', ['role' => $role])->with('success', 'Se ha registrado el grupo correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('role.permission.index', ['role' => $role])->with('error', 'Ha ocurrido un error al registrar el grupo.');
        }
    }

    public function destroy(Request $request, Role $role, Permission $permission)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'menu' => 'required|exists:menus,id',
            ]);

            $role->permissions()
                ->wherePivot('menu_id', $request->menu)
                ->detach($permission->id);

            DB::commit();
            return redirect()->route('role.permission.index', ['role' => $role])->with('success', 'Se ha eliminado el permiso correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('role.permission.index', ['role' => $role])->with('error', 'Ha ocurrido un error al eliminar el permiso.');
        }
    }
}
