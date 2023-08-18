<?php

namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller
{
    public function index()
    {
        return view('master.unit.index');
    }

    public function create()
    {
        $units = Unit::where('state', 1)->get();

        return view('master.unit.create', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' =>'required|string|max:255',
        ]);

        $state = 0;
        if ($request->state) {
            $state = $request->state;
        }

        DB::beginTransaction();
        try {
            Unit::create([
                'name' => $request->name,
                'unit_id' => $request->unit_id,
                'factor' => $request->factor,
                'state' => $state,
            ]);

            DB::commit();
            return redirect()->route('unit.index')->with('success', 'Se ha registrado la unidad correctamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Ha ocurrido al registrar la unidad');
        }
    }

    public function edit(Unit $unit)
    {
        $units = Unit::where('state', 1)->get();

        return view('master.unit.edit', compact('unit', 'units'));
    }

    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'name' =>'required|string|max:255',
        ]);

        $state = 0;
        if ($request->state) {
            $state = $request->state;
        }

        DB::beginTransaction();
        try {
            $unit->update([
                'name' => $request->name,
                'unit_id' => $request->unit_id,
                'factor' => $request->factor,
                'state' => $state,
            ]);

            DB::commit();
            return redirect()->route('unit.index')->with('success', 'Se ha actualizado la unidad correctamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Ha ocurrido un error al actualizar la unidad');
        }
    }
}
