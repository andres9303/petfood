<?php

namespace App\Http\Controllers\order;

use App\Http\Controllers\Controller;
use App\Models\Doc;
use App\Models\Mvto;
use App\Models\Pet;
use App\Models\Product;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        return view('order.order.index');
    }

    public function create()
    {
        $now = date('Y-m-d');
        $pets = Pet::where('state', 1)->orderBy('name')->get();
        $diets = Product::where('state', 1)->where('type', 1)->orderBy('name')->get();
        $units = Unit::where('state', 1)->orderBy('name')->get();
        $unit_selected = Unit::where('name', 'LIKE', 'gramo%')->first();
        $unit_selected = $unit_selected->id ?? null;

        return view('order.order.create', compact('pets', 'diets', 'units', 'unit_selected', 'now'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'pet_id' => 'required', 
            'mvtos.0.product_id' => 'required',
            'mvtos.0.unit_id' => 'required',
            'mvtos.0.cant' => 'required',
            'mvtos.0.cant_src' => 'required',           
        ]);

        DB::beginTransaction();
        try
        {
            $pet = Pet::find($request->pet_id);

            $order = Doc::create([
                'code' => 'PED',
                'type' => 'PED',
                'num' => Doc::where('menu_id', 501)->count() + 1,
                'date' => $request->date,
                'menu_id' => 501,
                'person_id' => $pet->person_id,
                'pet_id' => $request->pet_id,
                'state' => 0,
                'text' => $request->text,
                'user_id' => auth()->user()->id,
            ]);

            Mvto::create([
                'doc_id' => $order->id,
                'product_id' => $request->mvtos[0]['product_id'],
                'unit_id' => $request->mvtos[0]['unit_id'],
                'cant' => $request->mvtos[0]['cant'],
                'cant_src' => $request->mvtos[0]['cant_src'],
            ]);

            DB::commit();
            return redirect()->route('order.index')->with('success', 'Se ha registrado el pedido correctamente.');
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error al registrar el pedido');
        }
    }

    public function edit(Doc $order)
    {
        $now = Carbon::parse($order->date)->format('Y-m-d');
        $pets = Pet::where('state', 1)->orderBy('name')->get();
        $diets = Product::where('state', 1)->where('type', 1)->orderBy('name')->get();
        $units = Unit::where('state', 1)->orderBy('name')->get();
        $unit_selected = $order->unit;

        return view('order.order.edit', compact('order', 'pets', 'diets', 'units', 'unit_selected', 'now'));
    }
    
    public function update(Request $request, Doc $order)
    {
        $request->validate([
            'date' => 'required',
            'pet_id' => 'required', 
            'mvtos.0.product_id' => 'required',
            'mvtos.0.unit_id' => 'required',
            'mvtos.0.cant' => 'required',
            'mvtos.0.cant_src' => 'required',           
        ]);

        DB::beginTransaction();
        try
        {
            if ($order->state == 1) {
                DB::rollBack();
                return back()->withInput()->with('error', 'No se puede editar un pedido despachado');
            }

            $pet = Pet::find($request->pet_id);
            $order->update([
                'date' => $request->date,
                'person_id' => $pet->person_id,
                'pet_id' => $request->pet_id,
                'text' => $request->text,
                'user_id' => auth()->user()->id,
            ]);

            $order->mvtos[0]->update([
                'product_id' => $request->mvtos[0]['product_id'],
                'unit_id' => $request->mvtos[0]['unit_id'],
                'cant' => $request->mvtos[0]['cant'],
                'cant_src' => $request->mvtos[0]['cant_src'],
            ]);

            DB::commit();
            return redirect()->route('order.index')->with('success', 'Se ha editado el pedido correctamente.');
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error al editar el pedido');
        }
    }

    public function destroy(Doc $order)
    {
        DB::beginTransaction();
        try
        {
            if ($order->state == 1) {
                DB::rollBack();
                return back()->withInput()->with('error', 'No se puede eliminar un pedido despachado');
            }

            $order->update([
                'state' => -1,
            ]);

            $order->mvtos[0]->update([
                'cant' => 0,
                'cant_src' => 0,
            ]);

            DB::commit();
            return redirect()->route('order.index')->with('success', 'Se ha eliminado el pedido correctamente.');
        }   
        catch (\Exception $e)
        {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error al eliminar el pedido');
        }     
    }
}
