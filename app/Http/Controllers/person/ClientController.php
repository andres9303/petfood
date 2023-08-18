<?php

namespace App\Http\Controllers\person;

use App\Http\Controllers\Controller;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function index()
    {
        return view('person.client.index');
    }

    public function create()
    {
        return view('person.client.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' =>'required|string|max:250',
            'code' =>'required|string|max:20',
        ]);

        $type = $request->type;
        $route = $type == 'isclient' ? 'client.index' : 'supplier.index';
        DB::beginTransaction();
        try
        {
            $person = Person::where('code', $request->code)->first();
            if ($person)
            {
                $person->update([
                    'name' => $request->name,
                    'address' => $request->address,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'birth' => $request->birth,
                    $type => true,
                ]);
            }
            else
            {
                Person::create([
                    'code' => $request->code,
                    'name' => $request->name,
                    'address' => $request->address,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'birth' => $request->birth,
                    $type => true,
                ]);
            }            
            
            DB::commit();
            return redirect()->route($route)->with('success', 'Se guardó correctamente la persona.');
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return redirect()->route($route)->with('error', 'No se pudo guardar la persona.');
        }
    }

    public function edit(Person $client)
    {
        return view('person.client.edit', compact('client'));
    }

    public function update(Request $request, Person $client)
    {
        $request->validate([
            'name' =>'required|string|max:250',
            'code' =>'required|string|max:20',
        ]);

        $type = $request->type;
        $route = $type == 'isclient' ? 'client.index' : 'supplier.index';
        DB::beginTransaction();
        try
        {
            $client->update([
                'code' => $request->code,
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email,
                'birth' => $request->birth,
            ]);
            
            DB::commit();
            return redirect()->route($route)->with('success', 'Se actualizó correctamente la persona.');
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return redirect()->route($route)->with('error', 'No se pudo actualizar la persona.');
        }
    }

    public function destroy(Person $client, Request $request)
    {
        $type = $request->type;
        $route = $type == 'isclient' ? 'client.index' : 'supplier.index';
        DB::beginTransaction();
        try
        {
            $client->update([
                $type => false,
            ]);
            
            DB::commit();
            return redirect()->route($route)->with('success', 'Se eliminó correctamente.');
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return redirect()->route($route)->with('error', 'No se pudo eliminar.');
        }
    }
}
