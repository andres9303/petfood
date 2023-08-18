<?php

namespace App\Http\Controllers\shopping;

use App\Http\Controllers\Controller;
use App\Models\Doc;
use App\Models\Mvto;
use App\Models\Person;
use App\Models\Product;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
    
    public function index()
    {
        return view('shopping.bill.index');
    }
    
    public function create()
    {
        $now = date('Y-m-d');
        $persons = Person::where(['state' => 1, 'issupplier' => 1])->orderBy('name')->get();
        $products = Product::where('state', 1)->where('isinventory', 0)->whereNull('type')->orderBy('name')->get();
        $units = Unit::where('state', 1)->orderBy('name')->get();
        
        return view('shopping.bill.create', compact('now', 'persons', 'products', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'num' => 'required',
            'date' => 'required',
            'person_id' => 'required', 
            'mvtos.0.product_id' => 'required',
            'mvtos.0.unit_id' => 'required',
            'mvtos.0.cant' => 'required',
            'mvtos.0.valueu' => 'required',      
        ]);

        DB::beginTransaction();
        try
        {
            $bill = Doc::create([
                'code' => $request->code,
                'type' => 'GTO',
                'num' => $request->num,
                'date' => $request->date,
                'menu_id' => 603,
                'person_id' => $request->person_id,
                'state' => 1,
                'text' => $request->text,
                'user_id' => auth()->user()->id,
            ]);

            Mvto::create([
                'doc_id' => $bill->id,
                'product_id' => $request->mvtos[0]['product_id'],
                'unit_id' => $request->mvtos[0]['unit_id'],
                'cant' => $request->mvtos[0]['cant'],
                'valueu' => $request->mvtos[0]['valueu'],
                'iva' => $request->mvtos[0]['iva'] ?? 0,
                'valuet' => $request->mvtos[0]['cant'] * $request->mvtos[0]['valueu'] * (1+($request->mvtos[0]['iva'] ?? 0)/100),
            ]);

            DB::commit();
            return redirect()->route('bill.index')->with('success', 'Se ha registrado el gasto correctamente.');
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return back()->withInput()->with('error', 'Ocurrio un error al registrar el gasto');
        }
    }

    public function edit(Doc $bill)
    {
        $now = Carbon::parse($bill->date)->format('Y-m-d');
        $persons = Person::where(['state' => 1, 'issupplier' => 1])->orderBy('name')->get();
        $products = Product::where('state', 1)->where('isinventory', 0)->whereNull('type')->orderBy('name')->get();
        $units = Unit::where('state', 1)->orderBy('name')->get();

        return view('shopping.bill.edit', compact('bill', 'now', 'persons', 'products', 'units'));
    }

    public function update(Request $request, Doc $bill)
    {
        $request->validate([
            'num' => 'required',
            'date' => 'required',
            'person_id' => 'required', 
            'mvtos.0.product_id' => 'required',
            'mvtos.0.unit_id' => 'required',
            'mvtos.0.cant' => 'required',
            'mvtos.0.valueu' => 'required',      
        ]);

        DB::beginTransaction();
        try
        {
            $bill->update([
                'code' => $request->code,
                'num' => $request->num,
                'date' => $request->date,
                'person_id' => $request->person_id,
                'text' => $request->text,
                'user_id' => auth()->user()->id,
            ]);

            $bill->mvtos()->update([
                'product_id' => $request->mvtos[0]['product_id'],
                'unit_id' => $request->mvtos[0]['unit_id'],
                'cant' => $request->mvtos[0]['cant'],
                'valueu' => $request->mvtos[0]['valueu'],
                'iva' => $request->mvtos[0]['iva'] ?? 0,
                'valuet' => $request->mvtos[0]['cant'] * $request->mvtos[0]['valueu'] * (1+($request->mvtos[0]['iva'] ?? 0)/100),
            ]);

            DB::commit();
            return redirect()->route('bill.index')->with('success', 'Se ha registrado el gasto correctamente.');
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return back()->withInput()->with('error', 'Ocurrio un error al registrar el gasto.');
        }
    }

    public function destroy(Doc $bill)
    {
        DB::beginTransaction();
        try
        {
            $bill->update(['state' => -1]);
            $bill->mvtos()->update(['state' => -1, 'valuet' => 0, 'cant' => 0]);

            DB::commit();
            return redirect()->route('bill.index')->with('success', 'Se ha eliminado el gasto correctamente.');
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return back()->withInput()->with('error', 'Ocurrio un error al eliminar el gasto');
        }
    }
}
