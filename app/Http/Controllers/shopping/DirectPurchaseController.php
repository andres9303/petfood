<?php

namespace App\Http\Controllers\shopping;

use App\Http\Controllers\Controller;
use App\Models\Doc;
use App\Models\Mvto;
use App\Models\Order;
use App\Models\Person;
use App\Services\UnitConversionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DirectPurchaseController extends Controller
{
    protected $unitConversionService;

    public function __construct(UnitConversionService $unitConversionService)
    {
        $this->unitConversionService = $unitConversionService;
    }
    
    public function index()
    {
        return view('shopping.direct-purchase.index');
    }

    public function create()
    {
        $now = date('Y-m-d');
        $persons = Person::where('issupplier', 1)->get();

        return view('shopping.direct-purchase.create', compact('now', 'persons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'person_id' => 'required',
            'num' => 'required',
        ]);

        DB::beginTransaction();
        try
        {
            $mvtos = Order::where('menu_id', 602)->get();

            $doc = Doc::create([
                'type' => 'COM',
                'menu_id' => 602,
                'person_id' => $request->person_id,
                'code' => $request->code,
                'num' => $request->num,
                'date' => $request->date,
                'text' => $request->text,
                'state' => 0,
                'user_id' => auth()->user()->id,
            ]);

            $subtotal = 0;
            $iva = 0;
            foreach ($mvtos as $mvto) {
                $subtotal += $mvto->cant*$mvto->valueu;
                $iva += $mvto->cant*$mvto->valueu*($mvto->iva/100);

                $valuet = $mvto->cant * $mvto->valueu * (1 + $mvto->iva / 100);
                $cant_base = $this->unitConversionService->convert($mvto->cant, $mvto->unit_id, $mvto->product->unit_id);
                $valueu_base = $mvto->valueu / $cant_base;

                Mvto::create([
                    'doc_id' => $doc->id,
                    'product_id' => $mvto->product_id,
                    'cant' => $mvto->cant,
                    'unit_id' => $mvto->unit_id,
                    'valueu' => $mvto->valueu,
                    'iva' => $mvto->iva,
                    'valuet' => $valuet,
                    'unit2_id' => $mvto->product->unit_id,
                    'cant2' => $cant_base,
                    'valueu2' => $valueu_base,
                    'iva2' => $mvto->iva,
                    'valuet2' => $valuet,
                    'text' => $mvto->text,
                ]);

                $mvto->delete();
            }

            $doc->update([
                'subtotal' => $subtotal,
                'iva' => $iva,
                'total' => $subtotal + $iva,
            ]);

            DB::commit();
            return redirect()->route('direct-purchase.index')->with('success', 'Se registró la compra directa correctamente.');
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return redirect()->back()->with('error', 'No se pudo registrar la compra directa.'.$e->getMessage());
        }
    }

    public function edit(Doc $direct_purchase)
    {
        $now = Carbon::parse($direct_purchase->date)->format('Y-m-d');
        $persons = Person::where('issupplier', 1)->get();

        return view('shopping.direct-purchase.edit', compact('direct_purchase', 'now', 'persons'));
    }

    public function update(Request $request, Doc $direct_purchase)
    {
        $request->validate([
            'date' => 'required',
            'person_id' => 'required',
            'num' => 'required',
        ]);

        DB::beginTransaction();
        try
        {
            $subtotal = $direct_purchase->mvtos->sum(function ($mvto) {
                            return $mvto->cant * $mvto->valueu;
                        });
            $iva = $direct_purchase->mvtos->sum(function ($mvto) {
                            return $mvto->cant * $mvto->valueu * ($mvto->iva / 100);
                        });

            $direct_purchase->update([
                'person_id' => $request->person_id,
                'code' => $request->code,
                'num' => $request->num,
                'date' => $request->date,
                'text' => $request->text,
                'user_id' => auth()->user()->id,
                'subtotal' => $subtotal,
                'iva' => $iva,
                'total' => $subtotal + $iva,
            ]);

            DB::commit();
            return redirect()->route('direct-purchase.index')->with('success', 'Se actualizó la compra directa correctamente.');
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return redirect()->back()->with('error', 'No se pudo actualizar la compra directa.'.$e->getMessage());
        }
    }
}
