<?php

namespace App\Http\Controllers\pet;

use App\Http\Controllers\Controller;
use App\Models\Doc;
use App\Models\Item;
use App\Models\Pet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TraceController extends Controller
{
    
    public function create(Pet $pet)
    {
        $now = date('Y-m-d');
        $items = Item::where('catalog_id', 403)->get();

        return view('pet.pet.trace.create', compact('pet', 'now', 'items'));
    }

    public function store(Request $request, Pet $pet)
    {
        $request->validate([
            'date' => 'required',
            'item_id' => 'required',
            'text' => 'required',
        ]);

        DB::beginTransaction();
        try
        {
            Doc::create([
                'menu_id' => 403,
                'type' => 'SGP',
                'num' => Doc::where('menu_id', 403)->count() + 1,
                'user_id' => auth()->user()->id,
                'date' => $request->date,
                'item_id' => $request->item_id,
                'text' => $request->text,
                'pet_id' => $pet->id,
                'state' => 0,
            ]);

            DB::commit();
            return redirect()->route('pet.show', $pet)->with('success', 'Seguimiento de la mascota registrado correctamente');
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return redirect()->route('pet.trace.create', $pet)->with('error', 'Ocurrió un error al registrar el seguimiento de la mascota');
        }
    }

    public function edit(Pet $pet, Doc $trace)
    {
        $now = Carbon::parse($trace->date)->format('Y-m-d');
        $items = Item::where('catalog_id', 403)->get();

        return view('pet.pet.trace.edit', compact('pet', 'trace', 'now', 'items'));
    }

    public function update(Request $request, Pet $pet, Doc $trace)
    {
        $request->validate([
            'date' => 'required',
            'item_id' => 'required',
            'text' => 'required',
        ]);

        DB::beginTransaction();
        try
        {
            $trace->update([
                'date' => $request->date,
                'item_id' => $request->item_id,
                'text' => $request->text,
            ]);

            DB::commit();
            return redirect()->route('pet.show', $pet)->with('success', 'Seguimiento de la mascota actualizado correctamente');
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return redirect()->route('pet.trace.edit', [$pet, $trace])->with('error', 'Ocurrió un error al actualizar el seguimiento de la mascota');
        }
    }

    public function destroy(Pet $pet, Doc $trace)
    {
        DB::beginTransaction();
        try
        {
            $trace->update([
                'state' => -1,
            ]);

            DB::commit();
            return redirect()->route('pet.show', $pet)->with('success', 'Seguimiento de la mascota eliminado correctamente');
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return redirect()->route('pet.show', $pet)->with('error', 'Ocurrió un error al eliminar el seguimiento de la mascota');
        }
    }
}
