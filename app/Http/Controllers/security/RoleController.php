<?php

namespace App\Http\Controllers\security;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        return view('security.role.index');
    }

    public function create()
    {
        return view('security.role.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        DB::beginTransaction();
        try {
            Role::create([
                'name' => $request->name,
            ]);

            DB::commit();
            return redirect()->route('role.index')->with('success', 'Se ha registrado el grupo correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('role.index')->with('error', 'Ha ocurrido un error al registrar el grupo.');
        }
    }

    public function edit(Role $role)
    {
        return view('security.role.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        DB::beginTransaction();
        try {
            $role->name = $request->name;
            $role->save();

            DB::commit();
            return redirect()->route('role.index')->with('success', 'Se ha actualizado la información del grupo correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('role.index')->with('error', 'Ha ocurrido un error al actualizar la información del grupo.');
        }
    }

    public function destroy(Role $role)
    {
        DB::beginTransaction();
        try {
            $role->delete();

            DB::commit();
            return redirect()->route('role.index')->with('success', 'Se ha eliminado el grupo correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('role.index')->with('error', 'Ha ocurrido un error al eliminar el grupo.');
        }
    }
}
