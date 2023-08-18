<?php

namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Item;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DietController extends Controller
{
    public function index()
    {
        return view('master.diet.index');
    }

    public function create()
    {
        $units = Unit::where('state', 1)->orderBy('name')->get();
        $categories = Item::where('catalog_id', 203)->orderBy('order')->orderBy('name')->get()->prepend(['id' => null, 'name' => '-']);
        $unit_selected = Unit::where('name', 'LIKE', 'gramo%')->first();
        $unit_selected = $unit_selected->id ?? null;

        return view('master.diet.create', compact('units', 'categories', 'unit_selected'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' =>'required|string|max:255',
            'unit_id' =>'required|integer',
        ]);

        DB::beginTransaction();
        try {
            $state = 0;
            if ($request->state)
                $state = $request->state;
            
            Product::create([
                'code' => $request->code,
                'name' => $request->name,
                'factor' => 1,
                'unit_id' => $request->unit_id,
                'isinventory' => 0,
                'type' => 1,
                'valueu' => $request->valueu,
                'state' => $state,
                'class' => $request->class,
            ]);

            DB::commit();
            return redirect()->route('diet.index')->with('success', 'Se ha registrado la dieta correctamente.');
        }
        catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error al registrar la dieta');
        }
    }
    
    public function edit(Product $diet)
    {
        $units = Unit::where('state', 1)->orderBy('name')->get();
        $categories = Item::where('catalog_id', 203)->orderBy('order')->orderBy('name')->get()->prepend(['id' => null, 'name' => '-']);
        $unit_selected = $diet->unit->id;

        return view('master.diet.edit', compact('diet', 'units', 'categories', 'unit_selected'));
    }

    public function update(Request $request, Product $diet)
    {
        $request->validate([
            'name' =>'required|string|max:255',
            'unit_id' =>'required|integer',
        ]);

        DB::beginTransaction();
        try {
            $state = 0;
            if ($request->state) {
                $state = $request->state;
            }

            $diet->update([
                'code' => $request->code,
                'name' => $request->name,
                'unit_id' => $request->unit_id,
                'valueu' => $request->valueu,
                'state' => $state,
                'class' => $request->class,
            ]);

            DB::commit();
            return redirect()->route('diet.index')->with('success', 'Se ha actualizado la dieta correctamente.');
        }
        catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error al actualizar la dieta');
        }
    }
}
