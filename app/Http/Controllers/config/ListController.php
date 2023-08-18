<?php

namespace App\Http\Controllers\config;

use App\Http\Controllers\Controller;
use App\Models\Catalog;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ListController extends Controller
{
    public function index()
    {
        return view('config.list.index');
    }

    public function create()
    {
        $catalogs = Catalog::where('id', '<>', 203)->get();
        $items = Item::where('catalog_id', '<>', 203)->get()->prepend(['id' => null, 'name' => '-']);

        return view('config.list.create', compact('catalogs', 'items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'catalog_id' => 'required'
        ]);

        DB::beginTransaction();
        try
        {
            Item::create([
                'name' => $request->name,
                'text' => $request->text,
                'order' => $request->order ?? 0,
                'factor' => $request->factor,
                'catalog_id' => $request->catalog_id,
                'item_id' => $request->item_id,
            ]);
    
            DB::commit();
            return redirect()->route('list.index')->with('success', 'Se ha registrado el item de la lista correctamente.');
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return redirect()->route('list.index')->with('error', 'Ha ocurrido un error al registrar el item de la lista.');
        }
    }

    public function edit(Item $list)
    {
        $catalogs = Catalog::where('catalog_id', '<>', 203)->get();
        $items = Item::where('catalog_id', '<>', 203)->get()->prepend(['id' => null, 'name' => '-']);

        return view('config.list.edit', compact('list', 'catalogs', 'items'));
    }

    public function update(Request $request, Item $list)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'catalog_id' => 'required'
        ]);

        DB::beginTransaction();
        try
        {
            $list->update([
                'name' => $request->name,
                'text' => $request->text,
                'order' => $request->order ?? 0,
                'factor' => $request->factor,
                'catalog_id' => $request->catalog_id,
                'item_id' => $request->item_id,
            ]);
    
            DB::commit();
            return redirect()->route('list.index')->with('success', 'Se ha actualizado el item de la lista correctamente.');
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return redirect()->route('list.index')->with('error', 'Ha ocurrido un error al actualizar el item de la lista.');
        }
    }

    public function destroy(Item $list)
    {
        $list->delete();

        return redirect()->route('list.index')->with('success', 'Se ha eliminado el item de la lista correctamente.');
    }
}
