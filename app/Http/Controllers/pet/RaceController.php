<?php

namespace App\Http\Controllers\pet;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Models\Race;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RaceController extends Controller
{
    public function index()
    {
        return view('pet.race.index');
    }

    public function create()
    {
        $animals = Animal::where('state', 1)->get();

        return view('pet.race.create', compact('animals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' =>'required|string|max:250',
            'animal_id' =>'required|integer',
        ]);

        DB::beginTransaction();
        try
        {
            Race::create([
                'name' => $request->name,
                'animal_id' => $request->animal_id,
                'state' => $request->state ?? false,
            ]);

            DB::commit();
            return redirect()->route('race.index')->with('success', 'Se guardó correctamente la raza de mascota.');
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return redirect()->route('race.index')->with('error', 'Ocurrió un error al guardar la raza de mascotas.');
        }
    }

    public function edit(Race $race)
    {
        $animals = Animal::where('state', 1)->get();

        return view('pet.race.edit', compact('animals', 'race'));
    }

    public function update(Request $request, Race $race)
    {
        $request->validate([
            'name' =>'required|string|max:250',
            'animal_id' =>'required|integer',
        ]);

        DB::beginTransaction();
        try
        {
            $race->update([
                'name' => $request->name,
                'animal_id' => $request->animal_id,
                'state' => $request->state ?? false,
            ]);

            DB::commit();
            return redirect()->route('race.index')->with('success', 'Se actualizó correctamente la raza de mascota.');
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return redirect()->route('race.index')->with('error', 'Ocurrió un error al actualizar la raza de mascotas.');
        }
    }

    public function destroy(Race $race)
    {
        DB::beginTransaction();
        try
        {
            if ($race->pets()->count() > 0)
            {
                DB::rollBack();
                return redirect()->route('race.index')->with('error', 'No se puede eliminar la raza porque tiene mascotas asociadas.');
            }
            $race->delete();
            
            DB::commit();
            return redirect()->route('race.index')->with('success', 'Se eliminó correctamente la raza de mascota.');
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return redirect()->route('race.index')->with('error', 'Ocurrió un error al eliminar la raza de mascotas.');
        }
    }
}
