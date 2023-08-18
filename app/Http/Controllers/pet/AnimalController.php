<?php

namespace App\Http\Controllers\pet;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnimalController extends Controller
{
    public function index()
    {
        return view('pet.animal.index');
    }

    public function create()
    {
        return view('pet.animal.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' =>'required|string|max:250',
        ]);

        DB::beginTransaction();
        try
        {
            Animal::create([
                'name' => $request->name,
                'state' => $request->state ?? false,
            ]);
            
            DB::commit();
            return redirect()->route('animal.index')->with('success', 'Se guardó correctamente el tipo de mascota.');
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return redirect()->route('animal.index')->with('error', 'Ocurrió un error al guardar el tipo de mascota.');
        }
    }

    public function edit(Animal $animal)
    {
        return view('pet.animal.edit', compact('animal'));
    }

    public function update(Request $request, Animal $animal)
    {
        $request->validate([
            'name' =>'required|string|max:250',
        ]);

        DB::beginTransaction();
        try
        {
            $animal->update([
                'name' => $request->name,
                'state' => $request->state ?? false,
            ]);
            
            DB::commit();
            return redirect()->route('animal.index')->with('success', 'Se actualizó correctamente el tipo de mascota.');
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return redirect()->route('animal.index')->with('error', 'Ocurrió un error al actualizar el tipo de mascota.');
        }
    }

    public function destroy(Animal $animal)
    {
        DB::beginTransaction();
        try
        {
            if ($animal->races()->count() > 0)
            {
                DB::rollBack();
                return redirect()->route('animal.index')->with('error', 'No se puede eliminar el tipo de mascota porque tiene razas asociadas.');
            }
            $animal->delete();
            
            DB::commit();
            return redirect()->route('animal.index')->with('success', 'Se eliminó correctamente el tipo de mascota.');
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return redirect()->route('animal.index')->with('error', 'Ocurrió un error al eliminar el tipo de mascota.');
        }
    }
}
