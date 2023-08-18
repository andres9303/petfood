<?php

namespace App\Http\Controllers\security;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function index()
    {
        return view('security.menu.index');
    }

    public function edit(Menu $menu)
    {
        return view('security.menu.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'text' => ['required', 'string', 'max:200'],
            'icon' => ['required', 'string', 'max:100'],
        ]);
        
        DB::beginTransaction();
        try {
            $menu->text = $request->text;
            $menu->icon = $request->icon;
            $menu->save();

            DB::commit();
            return redirect()->route('menu.index')->with('success', 'Se ha actualizado la información del formulario correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('menu.index')->with('error', 'Ha ocurrido un error al actualizar la información del formulario.');
        }
    }
}
