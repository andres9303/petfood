<?php

namespace App\Http\Controllers\pet;

use App\Http\Controllers\Controller;
use App\Models\Doc;
use App\Models\Image;
use App\Models\Person;
use App\Models\Pet;
use App\Models\Race;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PetController extends Controller
{
    public function index()
    {
        return view('pet.pet.index');
    }

    public function create()
    {
        $persons = Person::where('isclient', true)->get();
        $races = Race::where('state', 1)->get();

        return view('pet.pet.create', compact('persons', 'races'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:250',
            'race_id' => 'required',
            'person_id' => 'required',
        ]);

        DB::beginTransaction();
        try
        {
            Pet::create([
                'name' => $request->name,
                'race_id' => $request->race_id,
                'person_id' => $request->person_id,
                'date' => $request->datedate,
                'living' => $request->living ?? '',
                'sib' => $request->sib ?? false,
                'exercise' => $request->exercise ?? '',
                'diet' => $request->diet ?? '',
                'allergy' => $request->allergy ?? '',
                'vaccine' => $request->vaccine ?? '',
                'deworming' => $request->deworming ?? '',
                'health' => $request->health ?? '',
                'reproductive' => $request->reproductive ?? '',
                'weight' => $request->weight ?? 0,
                'text' => $request->text,
                'state' => $request->state ?? 0,
            ]);

            DB::commit();
            return redirect()->route('pet.index')->with('success', 'Mascota registrada correctamente');
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return redirect()->back()->with('error', 'Ocurrio un error al registrar la mascota'. $e->getMessage());
        }
    }

    public function show(Pet $pet)
    {
        $traces = Doc::where('pet_id', $pet->id)->where('menu_id', 403)->where('state', 0)->get();

        return view('pet.pet.show', compact('pet', 'traces'));
    }

    public function edit(Pet $pet)
    {
        $persons = Person::where('isclient', true)->get();
        $races = Race::where('state', 1)->get();

        return view('pet.pet.edit', compact('persons', 'races', 'pet'));
    }

    public function update(Request $request, Pet $pet)
    {
        $request->validate([
            'name' => 'required|min:3|max:250',
            'race_id' => 'required',
            'person_id' => 'required',
        ]);

        DB::beginTransaction();
        try
        {
            $pet->update([
                'name' => $request->name,
                'race_id' => $request->race_id,
                'person_id' => $request->person_id,
                'date' => $request->datedate,
                'living' => $request->living ?? '',
                'sib' => $request->sib ?? false,
                'diet' => $request->diet ?? '',
                'exercise' => $request->exercise ?? '',
                'allergy' => $request->allergy ?? '',
                'vaccine' => $request->vaccine ?? '',
                'deworming' => $request->deworming ?? '',
                'health' => $request->health ?? '',
                'reproductive' => $request->reproductive ?? '',
                'weight' => $request->weight ?? 0,
                'text' => $request->text,
                'state' => $request->state ?? 1,
            ]);

            DB::commit();
            return redirect()->route('pet.index')->with('success', 'Mascota actualizada correctamente');
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return redirect()->back()->with('error', 'Ocurrio un error al actualizar la mascota');
        }
    }

    public function editImage(Pet $pet)
    {
        return view('pet.pet.editImage', compact('pet'));
    }

    public function updateImage(Request $request, Pet $pet)
    {
        $request->validate([
            'image' => 'required|file|mimes:jpeg,jpg,png|max:2048',
        ]);
        
        DB::beginTransaction();
        try {
            $imagename = $request->file('image')->store('/mascota', 'public');

            $image = Image::where('imageable_id', $pet->id)->where('imageable_type', 'App\Models\Pet')->first();

            if ($image) {
                $image->url = $imagename;            
                $image->save();
            }
            else
            {
                $image = Image::create([
                    'url' => $imagename,
                    'imageable_id' => $pet->id,
                    'imageable_type' => 'App\Models\Pet',
                ]);                
            }  
                     
            DB::commit();
            return redirect()->route('pet.index')->with('success', 'Se ha actualizado la imagen de la mascota correctamente.');
        }
        catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error al actualizar la imagen de la mascota');
        }
    }
}
