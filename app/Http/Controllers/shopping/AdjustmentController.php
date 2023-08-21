<?php

namespace App\Http\Controllers\shopping;

use App\Http\Controllers\Controller;
use App\Models\Doc;
use App\Models\Mvto;
use App\Models\Person;
use App\Models\Product;
use App\Models\Unit;
use App\Services\UnitConversionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdjustmentController extends Controller
{
    protected $unitConversionService;

    public function __construct(UnitConversionService $unitConversionService)
    {
        $this->unitConversionService = $unitConversionService;
    }
    
    public function index()
    {
        return view('shopping.adjustment.index');
    }
    
    public function create()
    {
        $now = date('Y-m-d');
        $products = Product::where('state', 1)->where('isinventory', 1)->whereNull('type')->orderBy('name')->get();
        $units = Unit::where('state', 1)->orderBy('name')->get();
        $unit_selected = Unit::where('name', 'LIKE', 'gramo%')->first();
        $unit_selected = $unit_selected->id ?? null;
        
        return view('shopping.adjustment.create', compact('now', 'products', 'units', 'unit_selected'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required', 
            'mvtos.0.product_id' => 'required',
            'mvtos.0.unit_id' => 'required',
            'mvtos.0.cant' => 'required',
            'mvtos.0.valueu' => 'required',      
        ]);

        DB::beginTransaction();
        try
        {
            $adjustment = Doc::create([
                'code' => 'AJT',
                'type' => 'AJT',
                'num' => Doc::where('menu_id', 604)->max('num') + 1,
                'date' => $request->date,
                'menu_id' => 604,
                'state' => 1,
                'text' => $request->text,
                'user_id' => auth()->user()->id,
            ]);

            $product = Product::find($request->mvtos[0]['product_id']);
            $valuet = $request->mvtos[0]['cant'] * $request->mvtos[0]['valueu'];
            $cant_base = $this->unitConversionService->convert($request->mvtos[0]['cant'], $request->mvtos[0]['unit_id'], $product->unit_id);
            if ($cant_base == 0)
            {
                DB::rollback();
                return back()->withInput()->with('error', 'La unidad seleccionada no se puede convertir a la unidad base del producto.');
            }

            $valueu_base = $valuet / ($cant_base <> 0 ? $cant_base : 1);

            Mvto::create([
                'doc_id' => $adjustment->id,
                'product_id' => $request->mvtos[0]['product_id'],
                'unit_id' => $request->mvtos[0]['unit_id'],
                'cant' => $request->mvtos[0]['cant'],
                'valueu' => $request->mvtos[0]['valueu'],
                'iva' => 0,
                'valuet' => $valuet,
                'unit2_id' => $product->unit_id,
                'cant2' => $cant_base,
                'valueu2' => $valueu_base,
                'iva2' => 0,
                'valuet2' => $valuet,
            ]);

            DB::commit();
            return redirect()->route('adjustment.index')->with('success', 'Se ha registrado el ajuste correctamente.');
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return back()->withInput()->with('error', 'Ocurrio un error al registrar el ajuste');
        }
    }

    public function edit(Doc $adjustment)
    {
        $now = Carbon::parse($adjustment->date)->format('Y-m-d');
        $products = Product::where('state', 1)->where('isinventory', 1)->whereNull('type')->orderBy('name')->get();
        $units = Unit::where('state', 1)->orderBy('name')->get();
        $unit_selected = $adjustment->unit;

        return view('shopping.adjustment.edit', compact('adjustment', 'now', 'products', 'units', 'unit_selected'));
    }

    public function update(Request $request, Doc $adjustment)
    {
        $request->validate([
            'date' => 'required', 
            'mvtos.0.product_id' => 'required',
            'mvtos.0.unit_id' => 'required',
            'mvtos.0.cant' => 'required',
            'mvtos.0.valueu' => 'required',      
        ]);

        DB::beginTransaction();
        try
        {
            $adjustment->update([
                'date' => $request->date,
                'text' => $request->text,
                'user_id' => auth()->user()->id,
            ]);

            $product = Product::find($request->mvtos[0]['product_id']);
            $valuet = $request->mvtos[0]['cant'] * $request->mvtos[0]['valueu'];
            $cant_base = $this->unitConversionService->convert($request->mvtos[0]['cant'], $request->mvtos[0]['unit_id'], $product->unit_id);
            if ($cant_base == 0)
            {
                DB::rollback();
                return back()->withInput()->with('error', 'La unidad seleccionada no se puede convertir a la unidad base del producto.');
            }

            $valueu_base = $valuet / ($cant_base <> 0 ? $cant_base : 1);

            $adjustment->mvtos()->update([
                'product_id' => $request->mvtos[0]['product_id'],
                'unit_id' => $request->mvtos[0]['unit_id'],
                'cant' => $request->mvtos[0]['cant'],
                'valueu' => $request->mvtos[0]['valueu'],
                'iva' => 0,
                'valuet' => $valuet,
                'unit2_id' => $product->unit_id,
                'cant2' => $cant_base,
                'valueu2' => $valueu_base,
                'iva2' => 0,
                'valuet2' => $valuet,
            ]);

            DB::commit();
            return redirect()->route('adjustment.index')->with('success', 'Se ha registrado el ajuste correctamente.');
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return back()->withInput()->with('error', 'Ocurrio un error al registrar el ajuste.');
        }
    }

    public function destroy(Doc $adjustment)
    {
        DB::beginTransaction();
        try
        {
            $adjustment->update(['state' => -1]);
            $adjustment->mvtos()->update(['state' => -1, 'valuet' => 0, 'cant' => 0, 'cant2' => 0, 'valuet2' => 0]);

            DB::commit();
            return redirect()->route('adjustment.index')->with('success', 'Se ha eliminado el ajuste correctamente.');
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return back()->withInput()->with('error', 'Ocurrio un error al eliminar el ajuste');
        }
    }
}
