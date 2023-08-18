<?php

namespace App\Http\Controllers\security;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShortcutController extends Controller
{
    public function index()
    {
        return view('security.shortcut.index');
    }

    public function create()
    {
        $roles = Role::all();
        $menus = Menu::whereNotNull('menu_id')->get();

        return view('security.shortcut.create', compact('roles', 'menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'menu_id' => 'required|exists:menus,id',
        ]);

        DB::beginTransaction();
        try{
            $role = Role::find($request->role_id);

            if ($role->shortcuts()->where('shortcuts.menu_id', $request->menu_id)->exists()) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Ya existe el acceso directo para el grupo.');
            }

            $role->shortcuts()->attach($request->menu_id);

            DB::commit();
            return redirect()->route('shortcut.index')->with('success', 'Se ha registrado el acceso directo correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('shortcut.index')->with('error', 'Ha ocurrido un error al registrar el acceso directo.');
        }
    }

    public function destroy(Request $request, Role $shortcut)
    {
        DB::beginTransaction();
        try{
            $shortcut->shortcuts()
                ->detach($request->menu_id);

            DB::commit();
            return redirect()->route('shortcut.index')->with('success', 'Se ha eliminado el acceso directo correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('shortcut.index')->with('error', 'Ha ocurrido un error al eliminar el acceso directo.');
        }
    }
}
