<?php

namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {
        return view('master.category.index');
    }

    public function create()
    {
        return view('master.category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' =>'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $order = 0;
            if ($request->order) {
                $order = $request->order;
            }
            
            Item::create([
                'name' => $request->name,
                'order' => $order,
                'text' => $request->text,
                'catalog_id' => 203,
            ]);

            DB::commit();
            return redirect()->route('category.index')->with('success', 'Se ha registrado la categoría del producto correctamente.');
        }
        catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error al registrar la categoría del producto.');
        }
    }

    public function edit(Item $category)
    {
        return view('master.category.edit', compact('category'));
    }

    public function update(Request $request, Item $category)
    {
        $request->validate([
            'name' =>'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $order = 0;
            if ($request->order) {
                $order = $request->order;
            }

            $category->update([
                'name' => $request->name,
                'order' => $order,
                'text' => $request->text,
            ]);

            DB::commit();
            return redirect()->route('category.index')->with('success', 'Se ha actualizado la categoría del producto correctamente.');
        }
        catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error al actualizar la categoría del producto.');
        }
    }

    public function destroy(Item $category)
    {
        DB::beginTransaction();
        try
        {
            $category->delete();

            DB::commit();
            return redirect()->route('category.index')->with('success', 'Se ha eliminado la categoría de producto correctamente.');
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error al eliminar la categoría de producto.');
        }
    }
}
